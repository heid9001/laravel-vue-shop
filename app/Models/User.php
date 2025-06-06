<?php
namespace App\Models;

use App\Enums\Role;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'firstname',
        'surname',
        'patronymic',
        'password',
        'role',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'role' => Role::class
    ];

    public function isAdmin(): bool
    {
        return $this->role === Role::Admin;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->surname} {$this->name} {$this->patronymic}";
    }
}
