<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserInformationRequest;

class UserInformationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserInformationRequest $request)
    {
        $validated = $request->validated();

        User::addInformation($validated);
        
        return response(['Information saved to database.'], 201);
    }
}
