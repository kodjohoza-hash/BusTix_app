<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->isSuperAdmin()) return redirect()->route('admin.dashboard');
        if ($user->isGuichet())    return redirect()->route('guichet.dashboard');

        // Redirige vers la page d'origine si présente
        $redirect = $request->query('redirect');
        if ($redirect) return redirect($redirect);

        return redirect()->route('home');
    }

    protected function loggedOut(Request $request)
    {
        return redirect()->route('home');
    }
}