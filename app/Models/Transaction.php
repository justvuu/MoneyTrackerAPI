<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'transactions';
    protected $primaryKey = 'transaction_id';
    protected $fillable = [
        'amount',
        'category_id_fk',
        'date',
        'description',
        'user_id_fk',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id_fk');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id_fk');
    }
}
