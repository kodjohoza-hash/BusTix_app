<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'user_surname',
        'email',
        'telephone',
        'password',
        'role',
        'admin_type',
        'actif',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'actif'             => 'boolean',
    ];

    const ROLE_ADMIN  = 'admin';
    const ROLE_CLIENT = 'client';
    const TYPE_SUPER_ADMIN = 'super_admin';
    const TYPE_GUICHET     = 'guichet';

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN
            && $this->admin_type === self::TYPE_SUPER_ADMIN;
    }

    public function isGuichet(): bool
    {
        return $this->role === self::ROLE_ADMIN
            && $this->admin_type === self::TYPE_GUICHET;
    }

    public function isActive(): bool
    {
        return $this->actif === true;
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function scopeActive($query)
    {
        return $query->where('actif', true);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    public function scopeClients($query)
    {
        return $query->where('role', self::ROLE_CLIENT);
    }
}