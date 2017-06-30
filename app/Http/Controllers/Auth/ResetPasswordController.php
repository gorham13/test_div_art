<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Request;
use Illuminate\Support\Facades\Validator;
use App\User;


class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {
        $input = $request::only(['email', 'password', 'password_confirmation']);
        $validator = Validator::make($input, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors();
            return response()->json($errors, 400);
        }
        $user = User::whereEmail($input['email'])->first();

        if ( ! $user)
        {
            return response()->json(null, 204);
        }

        $user->password = bcrypt($input['password']);
        $user->save();
        return response()->json($user, 200);
    }

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
}
