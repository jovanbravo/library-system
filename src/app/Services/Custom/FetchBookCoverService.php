<?php

namespace App\Services\Custom;

use Exception;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Throwable;

final class FetchBookCoverService
{
    /**
     * Get Cover URL
     *
     * @param string $isbn
     * @param string $size
     * @return string|null
     * @throws Exception
     */
    final public function get_cover_url(string $isbn, string $size = 'S'): ?string
    {
        if($this->rate_limited())
        {
            throw new Exception(message: 'Too many requests. Please try again later.', code: 429);
        }
        $this->input_validation($size);

        try {
            $generate_url = $this->generate_cover_url($size, $isbn);
            RateLimiter::hit(config('open_library.rate_limit_key'));
            return $this->custom_response(
                response: $generate_url['response'],
                url: $generate_url['url']
            );

        } catch (Throwable $throwable) {
            Log::error($throwable->getMessage());
            return null;
        }
    }

    /**
     * Return Custom Response From Open API
     *
     * @param Response $response
     * @param string $url
     * @return string|null
     */
    private function custom_response(Response $response, string $url): ?string
    {
        return ($response->successful() && $response->header('content-type') == 'image/jpeg')
            ? $url
            : null;
    }

    /**
     * Generate Cover URL
     *
     * @param string $size
     * @param string $isbn
     * @return array
     * @throws Exception
     */
    private function generate_cover_url(string $size, string $isbn): array
    {
        $filtered_isbn = preg_replace('/[^0-9]/', '', $isbn);
        $url = config('open_library.url') . $filtered_isbn . "-$size.jpg";

        try {
            $response = Http::timeout(config('open_library.http_timeout'))
                ->retry(config('open_library.http_retry'), config('open_library.http_retry_sleep'))
                ->get($url);

            return [
                'response' => $response,
                'url' => $url,
            ];
        } catch (Throwable $throwable) {
            throw new Exception($throwable->getMessage());
        }
    }

    /**
     * Check Is Request Rate Limited
     *
     * @return bool
     */
    private function rate_limited(): bool
    {
        if(!RateLimiter::tooManyAttempts(
            config('open_library.rate_limit_key'),
            config('open_library.rate_limit_attempts'))){
            return false;
        }

        return true;
    }

    /**
     * Input Validation
     *
     * @param string $size
     * @return void
     */
    private function input_validation(string $size): void
    {
        if(!in_array($size, config('open_library.valid_sizes'))){
            throw new InvalidArgumentException("Parameter size is not valid.
            Valid size params: " .implode(", ", config('open_library.valid_sizes')));
        }
    }
}
