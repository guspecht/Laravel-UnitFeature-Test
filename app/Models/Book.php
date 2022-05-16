<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'copies_sold',
        'user_id'
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }
}
