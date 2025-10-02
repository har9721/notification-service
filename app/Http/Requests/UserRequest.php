<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if($this->routeIs('getOtp'))
        {
            return [
                "mobile" => [
                    'required',
                    'numeric',
                    'regex:/^\d{10}$/',
                    'exists:users,mobile'
                ]
            ];
        }else{
            return [
                'name' => [
                    'required',
                    'string',
                    'max:30',
                ],
                'email' => [
                    'required',
                    'email',
                    'unique:users,email',
                ],
                'password' => [
                    'required',
                    'string',
                    'min:6',
                ],
                'mobile' => [
                    'required',
                    'numeric',
                    'regex:/^\d{10}$/',
                    'unique:users,mobile'
                ],
                'alert_via' => [
                    'required',
                    'array',
                    'in:email,sms,whatsapp'
                ],
            ];
        }
    }

    public function messages()
    {
        return [
            'mobile.regex' => 'The mobile number must be a valid 10-digit number.',
            'alert_via.in' => 'The selected alert_via option is invalid. Allowed values are email, sms, whatsapp.',
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
