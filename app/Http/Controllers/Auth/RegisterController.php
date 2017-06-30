<?php

namespace App\Http\Controllers\Auth;

use App\Mail\Confirmation;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Request;


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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function registration(Request $request)
    {
        $input = $request::only(['email','name','password', 'password_confirmation']);
        $confirmation_code = str_random(30);
        $input['confirmation_code'] = $confirmation_code;
        
        $validator = $this->validator($input);
        if ($validator->fails())
        {
            $errors = $validator->errors();
            return response()->json($errors, 400);
        }
        $user = $this->create($input);

        Mail::to($user)->send(new Confirmation($confirmation_code));
    }

    public function confirm($confirmation_code)
    {
        if( ! $confirmation_code)
        {
            return response()->json(null, 400);
        }

        $user = User::whereConfirmationCode($confirmation_code)->first();

        if ( ! $user)
        {
            return response()->json(null, 204);
        }

        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();

        return response()->json($user, 200);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'confirmation_code' => $data['confirmation_code'],
        ]);
    }
}
