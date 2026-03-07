<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Permission - Représente une permission dans l'application
 */
class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'permission_name',
    ];

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_permission',
            'permission_id',
            'role_id'
        );
    }

    const MANAGE_BUSES         = 'manage_buses';
    const CREATE_BUS           = 'create_bus';
    const EDIT_BUS             = 'edit_bus';
    const DELETE_BUS           = 'delete_bus';
    const MANAGE_TRIPS         = 'manage_trips';
    const CREATE_TRIP          = 'create_trip';
    const EDIT_TRIP            = 'edit_trip';
    const DELETE_TRIP          = 'delete_trip';
    const MANAGE_CUSTOMERS     = 'manage_customers';
    const VIEW_CUSTOMERS       = 'view_customers';
    const DELETE_CUSTOMER      = 'delete_customer';
    const MANAGE_RESERVATIONS  = 'manage_reservations';
    const VIEW_RESERVATIONS    = 'view_reservations';
    const CANCEL_RESERVATION   = 'cancel_reservation';
    const MAKE_RESERVATION     = 'make_reservation';
    const VIEW_OWN_RESERVATION = 'view_own_reservation';
    const MAKE_PAYMENT         = 'make_payment';
    const VIEW_OWN_PAYMENT     = 'view_own_payment';
}
