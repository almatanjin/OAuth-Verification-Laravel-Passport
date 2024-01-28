<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class Washer extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'washers';
    protected $guard = [];
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_block',
        'status',
        'rating',
        'on_vacation',
        'image',
        'country_code',
        'phone',
        'longitude',
        'latitude',
        'gender',
        'identity_number',
        'identity_type',
        'identity_image',
        'category_id',
        'designation',
        'degrees',
        'languages_spoken',
        'experience_year',
        'wash_fee',
        'about_yourself',
        'educational_journey',
        'vendor_lat',
        'vendor_long',
        'is_notification',
        'total_job_done',
        'wallet',
        'device_token',
        'otp',
        'is_verified',
        'lifetime_earnings',
        'created_at',
        'updated_at'
    ];
}
