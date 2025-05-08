<?php

namespace Integration;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookApiIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set Up
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        exec('php artisan reverb:start --port=8080 > /dev/null 2>&1 &');
        config(['database.redis.default' => config('database.redis.testing')]);

        sleep(1);
    }

    /**
     * Get All Books
     *
     * @return void
     */
    public function test_get_all_books(): void
    {
        Book::factory()
            ->count(5)
            ->create();

        $response = $this->getJson('/api/book/get');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'author_id',
                            'title',
                            'isbn',
                            'book_description',
                            'publication_date',
                            'cover_url'
                        ]
                    ],
                    'links' => ['first', 'last', 'prev', 'next'],
                    'meta' => ['current_page', 'from', 'last_page', 'total']
                ],
                'message',
                'status',
                'type'
            ]);

        $response->assertJson([
            'status' => 200,
            'type' => 'general_success'
        ]);
    }

    /**
     * Show Book
     *
     * @return void
     */
    public function test_show_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/book/get/{$book->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'author_id',
                    'title',
                    'isbn',
                    'book_description',
                    'publication_date',
                    'cover_url'
                ],
                'message',
                'status',
                'type'
            ]);
    }

    /**
     * Create Book
     *
     * @return void
     */
    public function test_create_book(): void
    {
        $author = Author::factory()->create();

        $bookData = [
            'author_id' => $author->id,
            'title' => 'One of a Kind',
            'isbn' => '3219874563213',
            'book_description' => 'Book description for One of a Kind',
            'publication_date' => '2022-01-01'
        ];

        $response = $this->postJson(
            uri: '/api/book/create',
            data: $bookData
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'author_id',
                    'title',
                    'isbn',
                    'book_description',
                    'publication_date',
                    'updated_at',
                    'created_at',
                    'id'
                ],
                'message',
                'status',
                'type'
            ]);
    }

    /**
     * Update Book
     *
     * @return void
     */
    public function test_update_book(): void
    {
        $author = Author::factory()->create();

        $book = Book::factory()->create();

        $updatedData = [
            'author_id' => $author->id,
            'title' => 'One of a Kind',
            'isbn' => '3219874563213',
            'book_description' => 'Book description for One of a Kind',
            'publication_date' => '2022-05-09'
        ];

        $response = $this->patchJson(
            uri: "/api/book/update/{$book->id}",
            data: $updatedData
        );

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'book_id' => $book->id,
                ],
                'message' => 'Book is updated successfully',
            ]);
    }

    /**
     * Delete Book
     *
     * @return void
     */
    public function test_delete_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson("/api/book/delete/{$book->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    /**
     * Tear Down
     *
     * @return void
     */
    protected function tearDown(): void
    {
        exec('pkill -f "reverb:start"');
        parent::tearDown();
    }
}
