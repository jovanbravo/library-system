<?php

namespace Integration;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AuthorApiIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get All Authors
     *
     * @return void
     */
    public function test_get_all_authors(): void
    {
        Author::factory()
            ->count(6)
            ->create();

        $response = $this->getJson('/api/author/get');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'data' => [
                        '*' => ['author_name', 'author_email']
                    ],
                    'links' => ['first', 'last', 'prev', 'next'],
                    'meta' => ['current_page', 'per_page', 'total']
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
     * Show Author
     *
     * @return void
     */
    public function test_show_author(): void
    {
        $author = Author::factory()->create();

        $response = $this->getJson("/api/author/get/{$author->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'author_id',
                    'author_name',
                    'author_email',
                    'date_of_birth',
                    'author_bio',
                    'books'
                ],
                'message',
                'status',
                'type'
            ]);
    }

    /**
     * Create Author
     *
     * @return void
     */
    public function test_create_author(): void
    {
        $authorData = [
            'name' => 'Susan Smith',
            'email' => 'susan@example.com',
            'date_of_birth' => '1966-12-02',
            'author_bio' => 'A test biography for Susan Smith'
        ];

        $response = $this->postJson(
            uri:'api/author/create',
            data: $authorData
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'date_of_birth',
                    'author_bio',
                    'created_at',
                    'updated_at'
                ],
                'message',
                'status',
                'type'
            ]);
    }

    /**
     * Update Author
     *
     * @return void
     */
    public function test_update_author(): void
    {
        $author = Author::factory()->create();
        $updatedData = [
            'name' => 'Beth Serrano',
            'email' => 'bethrserrano@rhyta.com',
            'date_of_birth' => '1989-11-05',
            'author_bio' => 'A test biography for Beth Serrano'
        ];

        $response = $this->patchJson(
            uri:"/api/author/update/{$author->id}",
            data: $updatedData
        );

        $response->assertStatus(200)
            ->assertJson([
                'data' => ['id' => $author->id],
            ]);
    }

    /**
     * Delete Author
     *
     * @return void
     */
    public function test_delete_author(): void
    {
        $author = Author::factory()->create();

        $response = $this->deleteJson("/api/author/delete/{$author->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }
}
