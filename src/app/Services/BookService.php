<?php

namespace App\Services;

use App\Contracts\RepositoryInterface;
use App\Contracts\ServiceInterface;
use App\Http\Resources\BookDetailResource;
use App\Http\Resources\BooksResource;
use App\Http\Responses\CustomResponse;
use App\Jobs\BookCoverJob;
use App\Services\Core\BaseService;
use App\Services\Custom\FetchBookCoverService;
use App\Traits\ServiceBaseMethods;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

final class BookService extends BaseService implements ServiceInterface
{
    use ServiceBaseMethods;
    protected const INDEX_RESOURCE = BooksResource::class;
    protected const DETAIL_RESOURCE = BookDetailResource::class;

    public function __construct(
        protected RepositoryInterface $repository,
        private FetchBookCoverService $fetchBookCoverService
    ){
       parent::__construct($this->repository);
    }

    /**
     * Create a new book record
     *
     * @param array $data
     * @return JsonResponse
     */
    final public function create_record(array $data): JsonResponse
    {
        try {
            if($generate_cover_url = $this->fetchBookCoverService->get_cover_url($data['isbn'])){
                $data['cover_url'] = $generate_cover_url;
            }
            $book = $this->repository->create($data);

            if(!$generate_cover_url) {
               BookCoverJob::dispatch($this->fetchBookCoverService, $book->id, $data['isbn']);
            }

            return CustomResponse::successRequest(
                message: 'Book is created successfully',
                data: $book
            );

        } catch (Throwable $throwable) {
            Log::error($throwable->getMessage());

            return CustomResponse::badRequest('Something went wrong!');
        }
    }

    /**
     * Update Book Record
     *
     * @param int $id
     * @param array $data
     * @return JsonResponse
     */
    final public function update_record(int $id, array $data): JsonResponse
    {
        if(!$this->repository->get_by_id($id)) {
            return CustomResponse::badRequest('Book not found!');
        }

        try {
            if ($generate_url = ($data['isbn'] ?? null) ? $this->fetchBookCoverService->get_cover_url($data['isbn']) : null) {
                $data['cover_url'] = $generate_url;
            }

            $this->repository->update($id, $data);

            if(array_key_exists('isbn', $data) && !array_key_exists('cover_url', $data)){
                BookCoverJob::dispatch($this->fetchBookCoverService, $id, $data['isbn']);
            }

            return CustomResponse::successRequest(
                message: 'Book is updated successfully',
                data: [
                    'book_id' => $id
                ]
            );

        } catch (Throwable $throwable) {
            Log::error($throwable->getMessage());

            return CustomResponse::badRequest('Something went wrong!');
        }
    }
}
