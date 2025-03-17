<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use App\Models\Audit;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/gestionnaire/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login'); 
    } 


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return $this->authenticated($request);
        }

        return back()->with('error', 'Email ou mot de passe incorrect.');
    }



    protected function authenticated(Request $request)
    {

        $user = Auth::user();

        // Enregistrer l'activité dans les audits
        Audit::create([
            'user_id' => $user->id,
            'action' => 'Connexion',
            'details' => "Le gestionnaire {$user->name} s'est connecté.",
        ]);


        $user = Auth::user();
       // dd($user->role);
        // Si l'utilisateur est un gestionnaire et doit changer son mot de passe
        if($user->role === 'admin')
        {
            return redirect()->route('admin.dashboard'); // Rediriger vers le tableau de bord admin
        }
        elseif ($user->role === 'gestionnaire' && !$user->password_changed) {
            return redirect()->route('password.change'); // Rediriger vers le changement de mot de passe
        }
        elseif($user->role === 'gestionnaire' && $user->password_changed)
        {
            return redirect()->route('gestionnaire.dashboard'); // Rediriger vers le tableau de bord admin
        }
        else
        {
            return redirect('/home'); // Redirection par défaut
        }

        

        

    }
}
