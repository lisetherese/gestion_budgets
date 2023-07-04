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
    public function testCreateBudgetRoute()
    {
        $response = $this->post('/create-budget', [
            'libelle' => 'abc',
            'montant' => 123,
            'nature' => 'leisure',
            'frequence' => 'weekly',
            'user_id' => 1,
        ]);

        $response->assertRedirect('/');
    }

    public function testLoginRoute()
    {
        $response = $this->post('/login', [
            'name' => 'lise',
            'email' => 'abc@yahoo.com',
            'password' => 'sontra',
        ]);

        $response->assertRedirect('/');
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
