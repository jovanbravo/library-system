<?php

namespace App\Models;

use App\Traits\RedisCustomMethods;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;
    use RedisCustomMethods;

    protected $fillable = [
        'name',
        'email',
        'date_of_birth',
        'author_bio'
    ];

    protected $casts = [
        'date_of_birth' => 'date'
    ];

    /**
     * Format Date Of Birth
     *
     * @return Attribute
     */
    protected function dateOfBirth(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('Y-m-d'),
            set: fn ($value) => Carbon::parse($value)->format('Y-m-d')
        );
    }

    /**
     * Books Relation
     *
     * @return HasMany
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
