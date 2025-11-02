<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ScheduleNotificationRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required'],
            'description' => [
                'required',
                // 'max:255'
            ],
            'type' => [
                'required',
                'array',
                'in:email,push,whatsapp'
            ],
            'target_type' => [
                'required',
                'in:all,selected'
            ],
            'schedule_time' => [
                'required',
                'date_format:d-m-Y H:i:s',
                'after_or_equal:today',
            ],
            'users' => [
                'array',
                'required_if:target_type,selected'
            ]
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
