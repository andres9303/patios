<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Master\Company;
use App\Models\Security\Role;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 
        'username', 
        'email', 
        'password',
        'telegram_chat_id',
        'telegram_code',
        'telegram_code_expires_at',
        'telegram_linked_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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
            'telegram_code_expires_at' => 'datetime',
            'telegram_linked_at' => 'datetime',
        ];
    }

    public function belongsToCompany($company): bool
    {
        return $this->companies()->where('company_id', $company->id)->exists() ||
               $this->companies()->where('name', 'Todos')->exists();
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user')->withPivot('role_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'company_user', 'user_id', 'role_id')
            ->withPivot('company_id');
    }

    public function menu(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => collect(json_decode($value)),
        );
    }

    public function currentCompany()
    {
        return $this->belongsTo(Company::class, 'current_company_id');
    }

    public function rolesInCompanies($companyIds)
    {
        return $this->belongsToMany(Role::class, 'company_user', 'user_id', 'role_id')
            ->withPivot('company_id')
            ->wherePivotIn('company_id', $companyIds);
    }
}
