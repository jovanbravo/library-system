<?php

namespace App\Jobs;

use App\Classes\ApplicationEvents;
use App\Models\Book;
use App\Services\Custom\FetchBookCoverService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class BookCoverJob implements ShouldQueue
{
    use Queueable;

    private Book $book;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private FetchBookCoverService $service,
        private int $book_id,
        private string $isbn
    )
    {
        $this->book = Book::find($this->book_id);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $generate_cover_url = $this->service->get_cover_url($this->isbn);

            if(!$generate_cover_url) {
                Log::warning('Cover URL not generated for book: ' . $this->book->title);

                ApplicationEvents::book_cover(
                    message: 'Cover URL not generated for book: ' . $this->book->title,
                    id: $this->book->id,
                    type: 'warning'
                );
                return;
            }

            $this->book->update(['cover_url' => $generate_cover_url]);
            Log::info('Cover URL generated for book: ' . $this->book->title);
            ApplicationEvents::book_cover(
                message: 'Cover URL generated for book: ' . $this->book->title,
                id: $this->book->id
            );

        } catch (Throwable $throwable) {
            Log::error($throwable->getMessage());

            $this->fail($throwable);
        }
    }

    /**
     * Failed job handler
     *
     * @param Throwable $throwable
     * @return void
     */
    public function failed(Throwable $throwable): void
    {
        ApplicationEvents::book_cover(
            message: 'Cover URL is not generated for book: ' . $this->book->title,
            id: $this->book->id,
            type: 'error'
        );

        Log::error(
            message: 'Book Cover Job Failed:',
            context: [$throwable->getMessage()]
        );
    }
}
