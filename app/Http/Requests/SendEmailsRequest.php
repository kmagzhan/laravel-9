<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'emails' => 'required|array',
            'emails.*.email' => 'required|email',
            'emails.*.subject' => 'required|string',
            'emails.*.body' => 'required|string'
        ];
    }
}
