<?php

namespace App\Contracts;

interface RequestInterface
{
    public function authorize(): bool;
    public function rules(): array;
}
