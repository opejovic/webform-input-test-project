<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubmitInformationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_submit_their_information_photo_and_license_to_the_system()
    {
        $this->withoutExceptionHandling();
        Storage::fake();
        
        $userPhoto = UploadedFile::fake()->image('user-photo.jpg');
        $licenseDocument = UploadedFile::fake()->create('license-document.txt');

        // Given the user submits valid data to the server
        $this->submitUserInformation([
            'name' => 'Miles Davis',
            'email' => 'miles@davis.com',
            'phone_number' => '(123) 456-7899',
            'address' => '958 Jazz Street',
            'zip_code' => '10002',
            'photo' => $userPhoto,
            'license_document' => $licenseDocument
        ]);

        // Then the user should be created
        $this->assertCount(1, User::all());
        $user = User::first();

        $this->assertEquals('Miles Davis', $user->name);
        $this->assertEquals('miles@davis.com', $user->email);
        $this->assertEquals('(123) 456-7899', $user->phone_number);
        $this->assertEquals('958 Jazz Street', $user->address);
        $this->assertEquals('10002', $user->zip_code);

        $photoPath = "photos/{$userPhoto->hashName()}";
        $licenseDocumentPath = "licenses/{$licenseDocument->hashName()}";
        $this->assertEquals(asset($photoPath), $user->photo_path);
        $this->assertEquals(asset($licenseDocumentPath), $user->license_document_path);
        Storage::disk('public')->assertExists($photoPath);
        Storage::disk('public')->assertExists($licenseDocumentPath);
        Storage::disk('public')->delete($photoPath);
        Storage::disk('public')->delete($licenseDocumentPath);
    }

    /** @test */
    public function name_is_required_when_submitting_information()
    {
        $response = $this->submitUserInformation($this->validParams([
            'name' => ''
        ]));

        $response->assertJsonValidationErrors('name');
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function email_is_required_when_submitting_information()
    {
        $response = $this->submitUserInformation($this->validParams([
            'email' => ''
        ]));

        $response->assertJsonValidationErrors('email');
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function email_needs_to_be_a_valid_email_address()
    {
        $response = $this->submitUserInformation($this->validParams([
            'email' => 'not-an-email-address'
        ]));

        $response->assertJsonValidationErrors('email');
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function email_needs_to_be_a_uniqe_email_address_in_the_users_table()
    {
        // Given we have an existing user with the given email
        $user = factory(User::class)->create(['email' => 'miles@davis.com']);
        $this->assertCount(1, User::all());

        // User tries to submit an existing email
        $response = $this->submitUserInformation($this->validParams([
            'email' => 'miles@davis.com'
        ]));

        $response->assertJsonValidationErrors('email');
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function phone_number_is_required()
    {
        $response = $this->submitUserInformation($this->validParams([
            'phone_number' => ''
        ]));

        $response->assertJsonValidationErrors('phone_number');
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function phone_number_needs_to_be_a_valid_phone_number()
    {
        // User tries to submit invalid phone number format
        $response = $this->submitUserInformation($this->validParams([
            'phone_number' => 'aB12345678899'
        ]));

        $response->assertJsonValidationErrors('phone_number');
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function phone_number_needs_to_be_unique()
    {
        // Given we have an existing user with the given phone number
        $user = factory(User::class)->create(['phone_number' => '(123) 456-7899']);
        $this->assertCount(1, User::all());
        
        // User tries to submit information with existing phone number
        $response = $this->submitUserInformation($this->validParams([
            'phone_number' => '(123) 456-7899'
        ]));

        $response->assertJsonValidationErrors('phone_number');
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function address_is_required()
    {
        $response = $this->submitUserInformation($this->validParams([
            'address' => ''
        ]));

        $response->assertJsonValidationErrors('address');
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function zip_code_is_required()
    {
        $response = $this->submitUserInformation($this->validParams([
            'zip_code' => ''
        ]));

        $response->assertJsonValidationErrors('zip_code');
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function zip_code_needs_to_be_5_digits_long()
    {
        $response = $this->submitUserInformation($this->validParams([
            'zip_code' => '1234'
        ]));

        $response->assertJsonValidationErrors('zip_code');
        $this->assertCount(0, User::all());
    }
    
    /** @test */
    public function photo_is_required()
    {
        $response = $this->submitUserInformation($this->validParams([
            'photo' => ''
        ]));

        $response->assertJsonValidationErrors('photo');
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function photo_must_be_a_valid_image_file()
    {
        $response = $this->submitUserInformation($this->validParams([
            'photo' => 'not-an-image'
        ]));

        $response->assertJsonValidationErrors('photo');
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function license_document_is_required()
    {
        $response = $this->submitUserInformation($this->validParams([
            'license_document' => ''
        ]));

        $response->assertJsonValidationErrors('license_document');
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function license_document_must_be_a_valid_txt_or_pdf_document()
    {
        $response = $this->submitUserInformation($this->validParams([
            'license_document' => 'not-a-valid-document'
        ]));

        $response->assertJsonValidationErrors('license_document');
        $this->assertCount(0, User::all());
    }

    private function submitUserInformation($params)
    {
        return $this->json('POST', '/api/user-information', $params);
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'name' => 'Miles Davis',
            'email' => 'miles@davis.com',
            'phone_number' => '(123) 456-7899',
            'address' => '958 Jazz Street',
            'zip_code' => '10002',
            'photo' => UploadedFile::fake()->image('user-photo.jpg'),
            'license_document' => UploadedFile::fake()->create('license-document.txt')
        ], $overrides);
    }
}
