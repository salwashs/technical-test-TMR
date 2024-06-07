<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::delete("delete from users");

        $this->seed(UserSeeder::class);
    }

    public function testRegisterPage()
    {
        $this->get('/register')
            ->assertSeeText("Registrasi")
            ->assertSeeText("Nama")
            ->assertSeeText("Email")
            ->assertSeeText("Password");
    }

    public function testRegisterSuccess()
    {
        $data = [
            "name" => "user",
            "email" => "user@mail.com",
            "password" => "userrahasia"
        ];

        $this->post("/register", $data)->assertRedirect("/login");
    }

    public function testRegisterNameEmpty()
    {
        $data = [
            "name" => "",
            "email" => "user@mail.com",
            "password" => "userrahasia"
        ];

        $this->post("/register", $data)
            ->assertSeeText("Form tidak boleh kosong")
            ->assertSeeText("Register")
            ->assertSeeText("Nama")
            ->assertSeeText("Email")
            ->assertSeeText("Password");
    }

    public function testRegisterEmailEmpty()
    {
        $data = [
            "name" => "user",
            "email" => "",
            "password" => "userrahasia"
        ];

        $this->post("/register", $data)
            ->assertSeeText("Form tidak boleh kosong")
            ->assertSeeText("Register")
            ->assertSeeText("Nama")
            ->assertSeeText("Email")
            ->assertSeeText("Password");
    }

    public function testRegisterPasswordEmpty()
    {
        $data = [
            "name" => "user",
            "email" => "",
            "password" => "userrahasia"
        ];

        $this->post("/register", $data)
            ->assertSeeText("Form tidak boleh kosong")
            ->assertSeeText("Register")
            ->assertSeeText("Nama")
            ->assertSeeText("Email")
            ->assertSeeText("Password");
    }

    public function testRegisterDuplicate()
    {
        $data = [
            "name" => "User Admin",
            "email" => "admin@localhost",
            "password" => "rahasia"
        ];

        $this->post("/register", $data)
            ->assertSeeText("User telah terdaftar, buat baru!")
            ->assertSeeText("Register")
            ->assertSeeText("Nama")
            ->assertSeeText("Email")
            ->assertSeeText("Password");
    }


    public function testLoginPage()
    {
        $this->get('/login')
            ->assertSeeText("Login");
    }

    public function testLoginPageForMember()
    {
        $this->withSession([
            "user" => "salwa"
        ])->get('/login')
            ->assertRedirect("/");
    }

    public function testLoginSuccess()
    {
        $this->post('/login', [
            "user" => "admin@localhost",
            "password" => "rahasia"
        ])->assertRedirect("/")
            ->assertSessionHas("user", "admin@localhost");
    }

    public function testLoginForUserAlreadyLogin()
    {
        $this->withSession([
            "user" => "admin@localhost"
        ])->post('/login', [
            "user" => "admin@localhost",
            "password" => "rahasia"
        ])->assertRedirect("/");
    }

    public function testLoginValidationError()
    {
        $this->post("/login", [])
            ->assertSeeText("User or password is required");
    }

    public function testLoginFailed()
    {
        $this->post('/login', [
            'user' => "wrong",
            "password" => "wrong"
        ])->assertSeeText("User or password is wrong");
    }

    public function testLogout()
    {
        $this->withSession(["user" => "admin@localhost"])->post("/logout")->assertRedirect("/")->assertSessionMissing("user");
    }

    public function testLogoutGuest()
    {
        $this->post('/logout')
            ->assertRedirect("/");
    }
}
