<?php

namespace App\Http\Requests;

use App\Contracts\RequestInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest implements RequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $route = $this->route()->getName();

        return match ($route) {
            'book.store' => $this->store_rules(),
            'book.update' => $this->update_rules(),
            'book.get' => $this->get_all_rules(),
            default => [],
        };
    }

    /**
     * Store Rules
     *
     * @return array[]
     */
    private function store_rules(): array
    {
        return [
            'author_id' => ['required', 'integer', 'exists:authors,id'],
            'title' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'regex:/^(?:\d{10}|\d{13})$/', 'unique:books,isbn'],
            'book_description' => ['nullable', 'string'],
            'publication_date' => ['required', 'date'],
        ];
    }

    /**
     * Update Rules
     *
     * @return array[]
     */
    private function update_rules(): array
    {
        return [
            'author_id' => ['sometimes', 'integer', 'exists:authors,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'isbn' => ['sometimes', 'string', 'regex:/^(?:\d{10}|\d{13})$/', 'unique:books,isbn,' . $this->id],
            'book_description' => ['nullable', 'string'],
            'publication_date' => ['sometimes', 'date'],
        ];
    }

    /**
     * Get all call - rules
     *
     * @return array[]
     */
    private function get_all_rules(): array
    {
        return [
            'per_page' => ['nullable', 'integer', 'min:1'],
            'title' => ['nullable', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'regex:/^(?:\d{10}|\d{13})$/'],
            'author_id' => ['nullable', 'integer', 'exists:authors,id']
        ];
    }
}
