<?php

namespace Tests\Feature;

use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{

    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function it_a_new_user_in_db()
    {
        $usr = factory(User::class, 2)->create();

        $this->assertEquals(2, $usr->count());

    }

    /**
     * @test
     */
    public function it_can_sign_up()
    {
        // Prepare data [name, mail, passwd]
        $faker = Factory::create();
        $passwd = $faker->password(6, 10);
        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => $passwd,
            'password_confirmation' => $passwd,
        ];
        // Route is valid
        //And Data are valid
        $this->withHeaders(['Accept' => 'application/json'])
            ->json('POST', '/api/auth/sign-up', $data)
            ->assertJson(['message' => 'Successfully created user!'])
            ->assertStatus(201);

        // See User in the DB
        $this->assertDatabaseHas('users', [
            'type_user' => 'guest'
        ]);
    }

    /**
     * @test
     */
    public function it_cant_sign_up_with_no_confirm_password()
    {
        // Prepare data [name, mail, password] using fake data
        $faker = Factory::create();
        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => $faker->password,
            'password_confirmation' => $faker->password,
            'remember_me' => true // for a long duration
        ];
        // Route is valid
        $this->withHeaders(['Accept' => 'application/json'])
            ->json('POST', '/api/auth/sign-up', $data)
            ->assertJsonCount(2)
            ->assertJsonStructure(['errors' => ['password'], 'message'])
            ->assertStatus(422);

    }

    /** @test */
    public function it_can_login_and_get_token()
    {
        // make sure you have a user in the DB
        $usr = factory(User::class)->create(['password' => bcrypt('1234')]);
        $this->assertDatabaseHas('users', ['email' => $usr->email]);


        $login = [
            'email' => $usr->email,
            'password' => '1234',
        ];
        $this->withHeaders(['Accept' => 'application/json'])
            ->json('POST', '/api/auth/login', $login)

            ->assertJsonStructure(['access_token', 'token_type', 'expires_at','user']);
    }
}
