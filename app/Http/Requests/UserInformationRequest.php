<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class UserInformationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'             => ['required'],
            'email'            => ['required', 'email', 'unique:users,email'],
            'phone_number'     => ['required', 'unique:users,phone_number', new PhoneNumber],
            'address'          => ['required'],
            'zip_code'         => ['required', 'min:5'],
            'photo'            => ['required', 'image'],
            'license_document' => ['required', 'file', 'mimes:txt,pdf'],
        ];
    }
}
