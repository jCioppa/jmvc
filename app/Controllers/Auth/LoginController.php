<?php

    namespace App\Controllers\Auth;

    use App\Models\Model;
    use App\Models\ModelCollection;
    use App\Models\User;
    use App\Http\Request\Request;
    
    class LoginController extends \App\Controllers\BaseController {

        public function index() {
            return view('auth/login');
        }

        public function login(Request $request) {

            if (app()->user()) {
                session()->flash("user already logged in!");
                return redirect(SERVER . "/auth/login");
            }

            $user = User::where(['email' => $request->input('email')]);
            
            if (!$user) { 
                session()->flash("please register before logging in");
                return redirect(SERVER . "/auth/login");
            }

            $hashed_pass = create_hash(
                $request->input('password'),
                $user->salt
            );

            if ($hashed_pass !== $user->password) {
                session()->flash('incorrect password');
                return redirect(SERVER . '/auth/login');
            }

            $user_browser = $_SERVER['HTTP_USER_AGENT'];
            session('logged_in', true);
            session('user_email', $user->email);
            session('login_string', hash('sha512', $user->password . $user_browser));

            app()->setUser($user);
            session()->flash('logged in!');
            return redirect(SERVER);
        
        }

        public function logout() {
            app()->ClearUser();
            session()->flash('logged out!');
            return redirect(SERVER . "/auth/login");
        }

    }
