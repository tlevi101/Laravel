<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Form extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'auth_required',
        'expires_at',
    ];
    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }
    public function questions(){
        return $this->hasMany(Question::class);
    }
}
