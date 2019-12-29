<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SubmitFormInputTest extends DuskTestCase
{
    use DatabaseMigrations, RefreshDatabase;

    /** @test*/
    public function unsuccessfully_submit_form()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->press('Submit')
                ->assertSee('The name field is required')
                ->assertSee('The email field is required.')
                ->assertSee('The phone number field is required.')
                ->assertSee('The address field is required.')
                ->assertSee('The zip code field is required.')
                ->assertSee('The photo field is required.')
                ->assertSee('The license document field is required.');
        });
    }

    /** @test*/
    public function successfully_submit_form()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->type('name', 'Janis')
                ->type('email', 'janisjoplin@example.com')
                ->type('phone_number', '(081) 636-5822')
                ->type('address', '123 Janisville Road')
                ->type('zip_code', '12345')
                ->attach('photo', storage_path('app/public/testing/test-photo.jpeg'))
                ->attach('license_document', storage_path('app/public/testing/test-license.txt'))
                ->press('Submit')
                ->assertDontSee('The name field is required')
                ->assertDontSee('The email field is required.')
                ->assertDontSee('The phone number field is required.')
                ->assertDontSee('The address field is required.')
                ->assertDontSee('The zip code field is required.')
                ->assertDontSee('The photo field is required.')
                ->assertDontSee('The license document field is required.');
        });
    }
}
