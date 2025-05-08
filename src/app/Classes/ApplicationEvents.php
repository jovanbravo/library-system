<?php

namespace App\Classes;

use App\Events\BookCoverEvent;
use Illuminate\Support\Facades\Log;

final class ApplicationEvents
{
    /**
    |--------------------------------------------------------------------------------------
    | Main class for Application events send with Reverb
    |--------------------------------------------------------------------------------------
    | Usage:
    | - Define static methods for the event and call it from anywhere in the application.
    |
    |--------------------------------------------------------------------------------------
     */

    /**
     * Book Cover Broadcast Event
     *
     * @param string $message
     * @param int $id
     * @param string $type
     * @return void
     */
    final public static function book_cover(string $message, int $id, string $type = 'success'): void
    {
        try {
            event(new BookCoverEvent($message, $id, $type));
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
        }

    }
}
