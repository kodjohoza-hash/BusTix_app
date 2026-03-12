<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'         => ['required', 'string', 'max:255'],
            'user_surname' => ['required', 'string', 'max:255'],
            'telephone'    => ['required', 'string', 'max:10'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name'         => $data['name'],
            'user_surname' => $data['user_surname'],
            'telephone'    => $data['telephone'],
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'role'         => User::ROLE_CLIENT,
            'actif'        => true,
        ]);

        Customer::create([
            'user_id'   => $user->id,
            'name'      => $data['name'],
            'surname'   => $data['user_surname'],
            'telephone' => $data['telephone'],
            'email'     => $data['email'],
            'id_card'   => 0,
        ]);

        return $user;
    }

    /**
     * Redirection après inscription réussie
     */
    protected function registered(Request $request, $user)
    {
        $redirect = $request->input('redirect');
        if ($redirect) return redirect($redirect);
        return redirect()->route('home');
    }
}