<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nik',
        'phone',
        'email',
        'password',
        'terms_accepted',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'terms_accepted' => 'boolean',
        ];
    }

    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn (string $value): string => in_array($value, ['operator', 'admin'], true) ? 'petugas' : $value,
        );
    }

    public function getPeranLayananAttribute(): ?string
    {
        $rawRole = strtolower($this->getRawOriginal('role') ?? '');
        $role = strtolower($this->role ?? '');

        if (in_array($rawRole, ['camat', 'masyarakat'], true) || in_array($role, ['camat', 'masyarakat'], true)) {
            return null;
        }

        if ($rawRole === 'operator' || stripos($this->name, 'operator') !== false || stripos($this->email, 'operator') !== false) {
            return 'Operator';
        }

        return 'Petugas';
    }
}
