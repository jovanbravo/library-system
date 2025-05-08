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
            'author.store' => $this->author_validation_rules('required'),
            'author.update' => $this->author_validation_rules('sometimes'),
            'author.get' => $this->filter_rules(),
            default => [],
        };
    }

    /**
     * Author Validation Rules
     *
     * @param string $rule_name
     * @return array[]
     */
    private function author_validation_rules(string $rule_name): array
    {
        return [
            'name' => [$rule_name, 'string', 'max:255'],
            'email' => [$rule_name, 'string', 'email', 'max:100', 'unique:authors'],
            'date_of_birth' => [$rule_name, 'date'],
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
