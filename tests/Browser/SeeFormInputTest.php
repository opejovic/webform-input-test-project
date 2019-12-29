<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class SeeFormInputTest extends DuskTestCase
{
    /** @test*/
    public function see_form_input()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Insert user information')
                ->assertSee('Name')
                ->assertSee('Email')
                ->assertSee('Phone number')
                ->assertSee('Address')
                ->assertSee('Zip Code')
                ->assertSee('Photo')
                ->assertSee('License Document')
                ->assertSee('Submit');
        });
    }
}
