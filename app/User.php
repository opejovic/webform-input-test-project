<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
     * Add the user information.
     *
     * @param  array $data
     * @return Model $user
     */
    public static function addInformation($data)
    {
        return self::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'address' => $data['address'],
            'zip_code' => $data['zip_code'],
            'photo_path' => request()->file('photo')->store('photos', 'public'),
            'license_document_path' => request()->file('license_document')->store('licenses', 'public')
        ]);
    }

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
