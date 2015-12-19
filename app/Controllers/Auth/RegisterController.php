<?php

    namespace App\Controllers\Auth;

    use App\Models\Model;
    use App\Models\ModelCollection;
    use App\Http\Request\Request;
    use App\Models\User;
    
    class RegisterController extends \App\Controllers\BaseController {

        public function index() {
            return view('auth/register');
        }

        public function register(Request $request) {

            // all fields must be filled in
            if (!($request->input('name')) || !($request->input('email')) || !($request->input('password')) || !($request->input('password_confirmation')))
            {
                session()->flash('please fill out all fields');
                return redirect(SERVER . '/auth/register');
            } 

            // make sure user isn't already registered
            $user = User::where(['email' => $request->input('email')]);
            if ($user != null) {
                session()->flash('this user already exists');
                return redirect(SERVER . '/auth/register');
            }

            // make sure passwords match
            if ($request->input('password') != $request->input('password_confirmation')) {
                session()->flash('your passwords do not match!');
                return redirect(SERVER . '/auth/register');
            }

            $user = new User();

            $user->name = $request->input("name");
            $user->email = $request->input("email");
            $user->salt = random_salt();
            $user->password = create_hash($request->input("password"), $user->salt);

            $user->save();

            session()->flash('congrats on registering!');
            return redirect(SERVER);
        
        }

    }
