<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserInformationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => ['required'],
            'email'            => ['required', 'email', 'unique:users,email'],
            'phone_number'     => ['required', 'unique:users,phone_number', new PhoneNumber],
            'address'          => ['required'],
            'zip_code'         => ['required', 'min:5'],
            'photo'            => ['required', 'image'],
            'license_document' => ['required', 'file', 'mimes:txt,pdf'],
        ]);

        User::create([
            'name'                  => $validated['name'],
            'email'                 => $validated['email'],
            'phone_number'          => $validated['phone_number'],
            'address'               => $validated['address'],
            'zip_code'              => $validated['zip_code'],
            'photo_path'            => $request->file('photo')->store('photos', 'public'),
            'license_document_path' => $request->file('license_document')->store('licenses', 'public')
        ]);

        return response(['Information saved to database.'], 201);
    }
}
