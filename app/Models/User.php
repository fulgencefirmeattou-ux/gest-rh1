<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Employe;
use App\Models\Service;
use App\Models\Departement;

/**
 * @property int|null $employe_id
 * @property string   $role
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'email_verified_at',
        'password',
        'role',
        'login',
        'employe_id',
        'date_creation',
        'date_connexion',
    ];

    public function pointages()
    {
        return $this->hasMany(Pointage::class, 'employe_id');
    }

    public function pointagesValides()
    {
        return $this->hasMany(Pointage::class, 'valide_par');
    }

    public function getNameAttribute(): string
    {
        return $this->nom . ' ' . $this->prenom;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_creation' => 'datetime',
        'date_connexion' => 'datetime',
    ];

    public function employe()
    {
        return $this->hasOne(Employe::class, 'user_id');
    }
    // public function employe()
    // {
    //     return $this->hasOne(Employe::class);
    // }



    // =======================
    // RÔLES SYSTÈME
    // =======================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDG(): bool
    {
        return $this->role === 'dg';
    }

    public function isRH(): bool
    {
        return $this->role === 'rh';
    }

    public function isEmploye(): bool
    {
        return $this->role === 'employe';
    }

    // =======================
    // RESPONSABILITÉS MÉTIER
    // =======================

    public function isResponsableService(): bool
    {
        if (!$this->employe_id) {
            return false;
        }

        return Service::where('responsable_id', $this->employe_id)->exists();
    }
    public function isResponsableDepartement(): bool
    {
        if (!$this->employe_id) {
            return false;
        }

        return Departement::where('responsable_id', $this->employe_id)->exists();

    }

    // =======================
    // RÔLE UNIQUE POUR SIDEBAR
    // =======================

    public function sidebarRole(): string
    {
        if ($this->isAdmin())
            return 'admin';
        if ($this->isDG())
            return 'dg';
        if ($this->isRH())
            return 'rh';
        if ($this->isResponsableDepartement())
            return 'responsable_departement';
        if ($this->isResponsableService())
            return 'responsable_service';
        if ($this->isEmploye())
            return 'employe';

        return 'default';
    }

}
