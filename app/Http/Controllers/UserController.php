<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(): Response
    {
        return response()->view("user.register", [
            "title" => "Registrasi"
        ]);
    }

    public function doRegister(Request $request): Response|RedirectResponse
    {
        $name = $request->input("name");
        $email = $request->input("email");
        $password = $request->input("password");

        if (empty($name) || empty($email) || empty($password)) {
            return response()->view("user.register", [
                "title" => "Register",
                "error" => "Form tidak boleh kosong"
            ]);
        }

        try {
            $this->userService->register($name, $email, $password);
            return redirect("/login");
        } catch (ValidationException $exception) {
            $errors = $exception->getMessage();

            if ($errors == "The email has already been taken.") {
                return response()->view("user.register", [
                    "title" => "Register",
                    "error" => "User telah terdaftar, buat baru!"
                ]);
            }

            return response()->view("user.register", [
                "title" => "Register",
                "error" => $errors
            ]);
        }
    }

    public function login(): Response
    {
        return response()->view("user.login", [
            "title" => "Login"
        ]);
    }

    public function doLogin(Request $request): Response|RedirectResponse
    {
        $user = $request->input('user');
        $password = $request->input('password');

        // validate input
        if (empty($user) || empty($password)) {
            return response()->view("user.login", [
                "title" => "Login",
                "error" => "User or password is required"
            ]);
        }

        if ($this->userService->login($user, $password)) {
            $request->session()->put("user", $user);
            return redirect("/");
        }

        return response()->view("user.login", [
            "title" => "Login",
            "error" => "User or password is wrong"
        ]);
    }

    public function doLogout(Request $request): RedirectResponse
    {
        $request->session()->forget("user");
        return redirect("/");
    }
}
