<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class Admin extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function sendEmailVerificationNotification()
    {
        $this->notify(new class ($this) extends VerifyEmail {
            public $admin;

            public function __construct($admin)
            {
                $this->admin = $admin;
            }

            protected function verificationUrl($notifiable)
            {
                $temporarySignedURL = URL::temporarySignedRoute(
                    'admin.verification.verify', // custom route we'll create
                    Carbon::now()->addMinutes(60),
                    [
                        'id' => $this->admin->getKey(),
                        'hash' => sha1($this->admin->getEmailForVerification()),
                    ]
                );

                return $temporarySignedURL;
            }
        });
    }

}
