<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Resources\UserResource;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

#[Fillable(['name', 'email', 'password', 'number'])]
#[Hidden(['password', 'role'])]
#[UseResource(UserResource::class)]
class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Role hierarchy levels (higher number = more permissions).
     */
    public const ROLE_LEVELS = [
        'customer' => 0,
        'employee' => 1,
        'admin' => 2,
        'owner' => 3,
        'developer' => 4,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Return the identifier stored in the JWT subject claim.
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return custom claims to be added to the JWT.
     *
     * @return array<string, mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Check if this user has at least the given role level.
     */
    public function hasRoleLevel(string $minimumRole): bool
    {
        $userLevel = self::ROLE_LEVELS[$this->role] ?? -1;
        $requiredLevel = self::ROLE_LEVELS[$minimumRole] ?? PHP_INT_MAX;

        return $userLevel >= $requiredLevel;
    }

    public function isCustomer(): bool
    {
        return $this->hasRoleLevel('customer');
    }

    public function isEmployee(): bool
    {
        return $this->hasRoleLevel('employee');
    }

    public function isAdmin(): bool
    {
        return $this->hasRoleLevel('admin');
    }

    public function isOwner(): bool
    {
        return $this->hasRoleLevel('owner');
    }

    public function isDeveloper(): bool
    {
        return $this->hasRoleLevel('developer');
    }

    /**
     * Get the addresses associated with the user.
     *
     * @return HasMany<EnderecoModel>
     */
    public function enderecos(): HasMany
    {
        return $this->hasMany(EnderecoModel::class, 'user_id');
    }

    /**
     * Get the orders placed by the user.
     *
     * @return HasMany<OrderModel>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(OrderModel::class, 'user_id');
    }
}
