<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use App\Casts\Json;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'opt' => Json::class,
    ];

    public $appends = [
        'role_name'
    ];

    public function surats()
    {
        return $this->hasMany(Surat::class, 'user_id');
    }

    public function getIsAdminAttribute()
    {
        return $this->role == 0;
    }

    public function getRoleNameAttribute()
    {
        return $this->role == 0 ? 'Admin' : 'User';
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($m) {
            $m->uuid = Str::uuid();
        });
    }
}
