<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = factory(User::class)->create([
            'photo_path' => 'photos/samplePhotoImage.jpeg',
            'license_document_path' => 'photos/sampleLicenseDocument.pdf',
        ]);
    }

    /** @test */
    public function it_can_get_the_formatted_photo_path()
    {
        $this->assertEquals('http://localhost/photos/samplePhotoImage.jpeg', $this->user->photo_path);
    }

    /** @test */
    public function it_can_get_the_formatted_license_document_path()
    {
        $this->assertEquals('http://localhost/photos/sampleLicenseDocument.pdf', $this->user->license_document_path);
    }
}
