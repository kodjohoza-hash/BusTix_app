<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Redirection après inscription
     */
    protected $redirectTo = '/home';

    /**
     * Constructeur - accessible uniquement aux invités
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validation des données d'inscription
     */
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

    /**
     * Création du nouvel utilisateur + profil client
     */
    protected function create(array $data)
    {
        // Création du compte utilisateur
        $user = User::create([
            'name'         => $data['name'],
            'user_surname' => $data['user_surname'],
            'telephone'    => $data['telephone'],
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'role'         => User::ROLE_CLIENT,
            'actif'        => true,
        ]);

        // Création automatique du profil client
        Customer::create([
            'user_id'   => $user->id,
            'name'      => $data['name'],
            'surname'   => $data['user_surname'],
            'telephone' => $data['telephone'],
            'email'     => $data['email'],
            'id_card'   => 0, // À compléter plus tard
        ]);

        return $user;
    }
}