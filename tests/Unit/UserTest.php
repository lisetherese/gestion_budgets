<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testHomePageRoute()
    {
        $response = $this->call('GET','/');

        $response->assertViewIs('home');
    }

    public function testLoginRoute()
    {
        $response = $this->post('/login', [
            'name' => 'lise',
            'email' => 'abc@yahoo.com',
            'password' => 'sontra',
        ]);

        $response->assertRedirect('/');
        // $response->assertViewHas();
    }


    public function testRegisterRoute()
    {
        $response = $this->post('/register', [
            'name' => 'abc',
            'email' => 'def@yahoo.com',
            'password' => 'sontra',
        ]);

        $response->assertRedirect('/');
    }

    public function testLogoutRoute()
    {
        $response = $this->call('POST','/logout');

        $response->assertRedirect('/');
    }

}
