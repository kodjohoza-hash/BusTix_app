<?php

namespace App\Http\Controllers\Guichet;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;

class ClientController extends Controller
{
    public function index()
    {
        $customers = Customer::with(['user', 'ticketReservations'])
                             ->latest()
                             ->paginate(15);

        return view('guichet.clients', compact('customers'));
    }
}