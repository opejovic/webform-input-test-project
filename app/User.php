<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address',
        'zip_code',
        'photo_path',
        'license_document_path',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Return the users photo path.
     *
     * @return string
     */
    public function getPhotoPathAttribute($photo)
    {
        return asset($photo);
    }

    /**
     * Return the users license document path.
     *
     * @return string
     */
    public function getLicenseDocumentPathAttribute($licenseDocument)
    {
        return asset($licenseDocument);
    }
}
