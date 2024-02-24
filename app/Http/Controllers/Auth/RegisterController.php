<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'firstname' => ['required', 'string', 'max:255'],
                'surname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'sex' => ['required', 'string', 'max:255'],
                // 'birth' => ['required', 'date'],
                'birthDay' => ['required', 'integer'],
                'birthMonth' => ['required', 'integer'],
                'birthYear' => ['required', 'integer']
            ],
        )->setAttributeNames([
                    'firstname' => 'Имя',
                    'surname' => 'Фамилия',
                    'email' => 'Email',
                    'password' => 'Пароль',
                    'sex' => 'Пол',
                    'birthDay' => 'День',
                    'birthMonth' => 'Месяц',
                    'birthYear' => 'Год',
                ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'firstname' => $data['firstname'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'sex' => $data['sex'],
            'birth' => $data['birthYear'] . "-" . $data['birthMonth'] . "-" . $data['birthDay'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
