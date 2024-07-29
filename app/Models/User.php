<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'two_factor_code',
        'two_factor_expires_at'

    ];

    protected $dates = [
        "two_factor_expires_at"
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getToken(String $key){
        return $this->createToken($key)->plainTextToken;
    }// Génère le Token

    public function generateTwoFacteurCode(){
        $this->two_factor_code = rand(100000,999999);
        $this->two_factor_expires_at = now()->addMinutes(10);
        $this->save();
    }//génère le factor code pour la double authentification

    public function resetTwoFactorCode(){
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }// réinitiqlise le tzo factor code

    public function setUserPassword(string $password){
        $this->password = Hash::make($password);
        $this->save();
    }//modifier le mot de passe de l'utilisateur

    public function deleteToken(){
        $this->tokens()->delete();
    }


}
