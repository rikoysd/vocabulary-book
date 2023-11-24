<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'account_id'
    ];

    public function meanings() {
        return $this -> hasMany('App\Models\Meaning');
    }

    public function accounts() {
        return $this -> belongsTo('App\Models\Account');
    }
}
