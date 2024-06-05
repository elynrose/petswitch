<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'last_name'=> ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'    => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'invitation_code' => ['required', 'string', 'exists:users,invitation_code'],
        ]);
    }

         /**
          * Create a new user instance after a valid registration.
          *
          * @param  array  $data
          * @return \App\User
          */
         protected function create(array $data)
         {
            //Check if invitation code exist, if not return error
            $invitation_code = $data['invitation_code'];
            $user = User::where('invitation_code', $invitation_code)->first();
            if($user){
                $invited_by_id = $user->id;    
            } else {
                $invited_by_id = 0;  
            }
            //Generate an alphanumerical code for the user (max of 6 characters) for the user
            $new_inivitation_code = strtoupper(Str::random(6));

            //Award the user that invited the new user 3 points
            $user->points += 3;
            $user->save();


            //Send an email to the user that invited the new user
            $details = [
                'title' => 'New User Registration',
                'body' => 'A new user has registered with your invitation code. You have been awarded 3 points. Your new points balance is '.$user->points,
            ];
            \Mail::to($user->email)->send(new \App\Mail\SendMail($details));

            //Create user
             return User::create([
                 'name'     => $data['name'],
                 'email'    => $data['email'],
                 'password' => Hash::make($data['password']),
                 'phone'    => $data['phone'],
                 'country'  => $data['country'],
                 'city'     => $data['city'],
                 'state'    => $data['state'],
                 'zip'      => $data['zip'],
                 'invitation_code' => $new_inivitation_code,
                 'invited_by_id' => $invited_by_id,
             ]);
         }
}
