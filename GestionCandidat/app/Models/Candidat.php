<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

class Candidat extends Model implements JWTSubject, AuthenticatableContract
{
    use HasFactory, HasApiTokens, Authenticatable;
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'dateNaissance',
        'adresse',
        'formationDesiree',
        'accepted',
        'role',
        'password',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
