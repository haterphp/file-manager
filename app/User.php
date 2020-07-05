<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    protected $fillable = ['login', 'password', 'role'];
    public $timestamps = false;

    public function tokens()
    {
        return $this->hasMany(AuthToken::class);
    }
//
//    public function createToken()
//    {
//        $token = Hash::make(Str::random(15));
//        $this->tokens()->create([
//            'token' => $token,
//            'date' => Carbon::now(),
//            'last_date' => Carbon::now()
//        ]);
//        return $token;
//    }

    public function createToken()
    {
        $this->api_token = Hash::make(Str::random(15));
        $this->save();
        return $this->api_token;
    }

    public static function createUser($request)
    {
        $password = User::generatePassword();
        User::create([
            'login' => $request->login,
            'password' => Hash::make($password),
            'role' => 2
        ]);
        return $password;
    }

    public static function generatePassword()
    {
        $letters = "qwertyuiopasdfghjklzxcvbnm";
        $numbers = '1234567890';
        $password = Str::of("")
            ->append(substr(str_shuffle(str_repeat($letters, 5)), 0, 5))
            ->append(substr(str_shuffle(str_repeat(strtoupper($letters), 5)), 0, 3))
            ->append(substr(str_shuffle(str_repeat($numbers, 5)), 0, 2));
        return str_shuffle($password);
    }

    public function ability()
    {
        return $this->hasOne(Role::class, 'id');
    }
}
