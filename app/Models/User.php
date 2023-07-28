<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;


    protected $fillable = [
        'username',
        'password',
        'income',
        'expense',
        'answer',
        'question',
    ];

    public function categories()
    {
        return $this->hasMany(Category::class, 'user_id_fk');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id_fk');
    }
}
