<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\AdminResetPassword;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    // Update fillable fields to match your table columns
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPassword($token));
    }

    // Optional: Full name accessor
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
