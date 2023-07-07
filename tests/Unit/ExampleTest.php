<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        //  $this->assertTrue(true);
    $this->browse(function (Browser $browser) {
            $browser->visit('logout')
                    ->visit('login')
                    ->type('email','admin@spondonit.com')
                    ->type('password','123456')
                    ->click('#btnsubmit')
                    ->waitForText('Welcome to')
                    ->assertSee('admin-dashboard');
        });
    }
}
