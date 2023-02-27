<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
class Device extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'platform','session_id','last_used_at'];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($device) {
            $device->ip_address = Request::ip();
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
