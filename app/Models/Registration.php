<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Registration extends Model
{
    use HasFactory;

    protected $table = 'registrations';

    protected $fillable = [
        'customer_name',
        'phone_number',
        'address',
        'children',
        'reservation_datetime',
        'booking_no',
    ];

    protected $casts = [
        'children' => 'array',
        'reservation_datetime' => 'datetime',
    ];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($reservation) {
            $reservation->booking_no = Str::upper(Str::random(8));
        });
    }
}
