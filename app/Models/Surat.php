<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\Json;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Surat extends Model
{
    use HasFactory;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'tanggal' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'opt' => Json::class,
    ];

    public function setTanggalAttribute($value)
    {
        return $this->attributes['tanggal'] = Carbon::createFromFormat('d-m-Y', $value)->toDateTimeString();
    }

    public function getTanggalAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function jenis_surat()
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($m) {
            $m->uuid = Str::uuid();
            $m->user_id = auth()->user()->uuid;
        });
        static::addGlobalScope(function (Builder $builder) {
            $builder->orderBy('tanggal', 'desc');
        });
    }
}
