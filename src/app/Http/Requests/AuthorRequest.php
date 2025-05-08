<?php

namespace App\Http\Requests;

use App\Contracts\RequestInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AuthorRequest extends FormRequest implements RequestInterface
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
            'author.store' => $this->author_validation_create_rules(),
            'author.update' => $this->author_validation_update_rules(),
            'author.get' => $this->filter_rules(),
            default => [],
        };
    }

    /**
     * Author Validation Rules
     *
     * @return array[]
     */
    private function author_validation_create_rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:authors'],
            'date_of_birth' => ['required', 'date'],
            'author_bio' => ['nullable', 'string'],
        ];
    }

    /**
     * Author Validation Rules
     *
     * @return array[]
     */
    private function author_validation_update_rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:100', 'unique:authors,email,' . $this->id],
            'date_of_birth' => ['sometimes', 'date'],
            'author_bio' => ['nullable', 'string'],
        ];
    }

    /**
     * Filter Rules
     *
     * @return array[]
     */
    private function filter_rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:1']
        ];
    }
}
