<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => Author::factory(),
            'title' => $this->generate_book_name(),
            'isbn' => $this->faker->unique()->isbn13(),
            'book_description' => $this->faker->paragraphs(nb:4, asText: true),
            'publication_date' => $this->faker->date(),
            'cover_url' => 'https://covers.openlibrary.org/b/isbn/' . $this->faker->isbn13() . '-M.jpg',
        ];
    }

    private function generate_book_name()
    {
        $adjectives = ['Mysterious', 'Great', 'Forgotten', 'Ancient', 'New', 'Terrifying', 'Beautiful'];
        $nouns = ['World', 'Castle', 'Island', 'Journey', 'King', 'Day', 'Night'];
        $prefixes = ['The Secret of', 'The History of', 'The Last', 'The Lost', 'The Legend of'];

        $pattern = rand(1, 4);

        return match ($pattern) {
            1 => sprintf('%s %s',
                $this->faker->randomElement($prefixes),
                $this->faker->randomElement($nouns)
            ),
            2 => sprintf('%s %s',
                $this->faker->randomElement($adjectives),
                $this->faker->randomElement($nouns)
            ),
            3 => sprintf('%s %s and %s',
                $this->faker->randomElement($adjectives),
                $this->faker->randomElement($nouns),
                $this->faker->randomElement($nouns)
            ),
            default => $this->faker->catchPhrase,
        };
    }
}
