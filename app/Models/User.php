<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User - Représente un utilisateur de l'application
 * 
 * Un utilisateur peut être un Admin ou un Client.
 * Il est lié à un rôle qui définit ses permissions
 * dans l'application BusTix.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nom de la table en base de données
     */
    protected $table = 'users';

    /**
     * Champs autorisés à l'insertion/modification en masse
     */
    protected $fillable = [
        'name',
        'user_surname',
        'email',
        'telephone',
        'password',
        'role',
        'actif',
        'role_id',
    ];

    /**
     * Champs cachés lors de la sérialisation (JSON/API)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast automatique des types de données
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'actif'             => 'boolean',
    ];

    /**
     * Rôles disponibles dans l'application
     */
    const ROLE_ADMIN  = 'admin';
    const ROLE_CLIENT = 'client';

    /**
     * Vérifie si l'utilisateur est un Administrateur
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Vérifie si l'utilisateur est un Client
     */
    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    /**
     * Vérifie si le compte utilisateur est actif
     */
    public function isActive(): bool
    {
        return $this->actif === true;
    }

    /**
     * Relation : Un utilisateur possède un seul profil Client
     */
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    /**
     * Relation : Un utilisateur possède un seul Rôle
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Scope : Filtrer uniquement les utilisateurs actifs
     */
    public function scopeActive($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope : Filtrer uniquement les administrateurs
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    /**
     * Scope : Filtrer uniquement les clients
     */
    public function scopeClients($query)
    {
        return $query->where('role', self::ROLE_CLIENT);
    }
}
