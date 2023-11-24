<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'mail',
        'password'
    ];

    public static $rules = array(
        'mail' => 'required',
        'password' => 'required'
    );

    public function vocabularies () {
        return $this -> hasMany('App\Models\Vocabulary');
    }
}
