<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meaning extends Model
{
    use HasFactory;

    protected $fillable = [
        'wordMeaning',
        'vocabulary_id'
    ];

    public function examples() {
        return $this -> hasMany('App\Models\Example');
    }

    public function vocabularies() {
        return $this -> belongsTo('App\Models\Vocabulary');
    }
}
