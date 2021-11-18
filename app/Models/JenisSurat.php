<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Casts\Json;

class JenisSurat extends Model
{
    use HasFactory;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'opt' => Json::class,
    ];

    public function surats()
    {
        return $this->hasMany(Surat::class, 'jenis_surat_id');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($m) {
            $m->uuid = Str::uuid();
        });
    }
}
