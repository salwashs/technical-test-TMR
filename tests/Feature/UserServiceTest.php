<?php

namespace Tests\Feature;

use App\Services\UserService;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;

    protected function setUp(): void
    {
        parent::setUp();

        DB::delete("delete from users");

        $this->userService = $this->app->make(UserService::class);

        $this->seed(UserSeeder::class);
    }

    public function testLoginSuccess()
    {
        self::assertTrue($this->userService->login("admin@localhost", "rahasia"));
    }

    public function testLoginUserNotFound()
    {
        self::assertFalse($this->userService->login("lala", "lala"));
    }

    public function testLoginWrongPassword()
    {
        self::assertFalse($this->userService->login("admin@localhost", "salah"));
    }

    public function testRegisterWithValidData()
    {
        $name = "John Doe";
        $email = "john@example.com";
        $password = "password123";

        $result = $this->userService->register($name, $email, $password);

        $this->assertTrue($result);
        $this->assertDatabaseHas('users', [
            'email' => $email
        ]);
    }

    public function testRegisterWithDuplicateEmail()
    {
        $this->expectException(ValidationException::class);

        $name = "John Doe";
        $email = "john@example.com";
        $password = "password123";

        $this->userService->register($name, $email, $password);

        $this->userService->register($name, $email, $password);
    }

    public function testRegisterWithInvalidEmail()
    {
        $this->expectException(ValidationException::class);

        $name = "John Doe";
        $email = "invalid-email";
        $password = "password123";

        $this->userService->register($name, $email, $password);
    }
}
