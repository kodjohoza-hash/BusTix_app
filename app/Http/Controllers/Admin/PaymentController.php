<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with([
            'ticketReservation.customer',
            'ticketReservation.trip.displacement'
        ])
        ->latest()
        ->paginate(15);

        $totalRevenue  = Payment::sum('amount');
        $todayRevenue  = Payment::whereDate('payment_date', today())->sum('amount');
        $totalPayments = Payment::count();

        return view('admin.payments', compact(
            'payments',
            'totalRevenue',
            'todayRevenue',
            'totalPayments'
        ));
    }
}