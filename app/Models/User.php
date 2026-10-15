<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

#[Fillable(['name', 'email', 'password', 'tel'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;


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

    public function postTravails(): BelongsToMany
    {
        return $this->belongsToMany(PostTravail::class)
            ->withPivot('is_default')
            ->withTimestamps();
    }

    public function defaultPostTravail(): ?PostTravail
    {
        return $this->postTravails()
            ->wherePivot('is_default', true)
            ->first()
            ?? $this->postTravails()->first();
    }

    public function approvisionnements(): HasMany
    {
        return $this->hasMany(Approvisionnement::class);
    }

    public function receptionsApprovisionnement(): HasMany
    {
        return $this->hasMany(Approvisionnement::class, 'receptionUser');
    }

    public static function assignableRoleLabels(): array
    {
        $defaults = [
            'chef_poste' => 'Chef de poste',
            'agent' => 'Agent',
            'lecteur' => 'Lecteur',
        ];

        return \Spatie\Permission\Models\Role::query()
            ->where('name', '!=', 'admin')
            ->orderBy('name')
            ->pluck('name')
            ->mapWithKeys(fn (string $name) => [
                $name => $defaults[$name] ?? ucwords(str_replace('_', ' ', $name)),
            ])
            ->all();
    }

    // Méthodes obligatoires pour JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
