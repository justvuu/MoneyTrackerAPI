<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;
    protected $table = 'categories';
    use HasFactory;

    protected $primaryKey = 'category_id';
    protected $fillable = [
        'category_name',
        'type',
        'user_id_fk',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id_fk');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'category_id_fk');
    }
}
