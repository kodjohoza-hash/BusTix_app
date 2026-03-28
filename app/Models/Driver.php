<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'name', 'surname', 'telephone', 'license_number', 'bus_id'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    // Status calculé automatiquement
    public function getStatusAttribute(): string
    {
        return $this->bus_id ? 'actif' : 'inactif';
    }

    public function isActive(): bool
    {
        return $this->bus_id !== null;
    }
}