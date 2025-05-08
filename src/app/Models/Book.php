<?php

namespace App\Models;

use App\Traits\RedisCustomMethods;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;
    use RedisCustomMethods;

    protected $fillable = [
        'author_id',
        'title',
        'isbn',
        'book_description',
        'publication_date',
        'cover_url'
    ];

    protected $casts = [
        'publication_date' => 'date'
    ];

    /**
     * Format Publication Date
     *
     * @return Attribute
     */
    protected function publicationDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('Y-m-d'),
            set: fn ($value) => Carbon::parse($value)->format('Y-m-d')
        );
    }

    /**
     * Relation To Author
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
