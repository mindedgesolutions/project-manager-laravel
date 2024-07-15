<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:255',
            'description' => 'nullable|string',
            'dueDate' => 'required|date|after:today',
            'status' => 'required|in:pending,in_progress,completed',
            'image' => 'nullable|image'
        ];
    }

    public function attributes()
    {
        return [
            'dueDate' => 'due date'
        ];
    }

    public function messages()
    {
        return [
            '*.required' => ':Attribute is required',
            '*.min' => ':Attribute must be between 3 to 255 characters',
            '*.max' => ':Attribute must be between 3 to 255 characters',
            'after' => ':Attribute cannot be before today',
            'in' => 'Invalid dropdown value',
            'image' => 'Select an image file to upload'
        ];
    }
}
