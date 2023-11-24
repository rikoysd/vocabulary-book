<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Example extends Model
{
    use HasFactory;

    protected $fillable = [
        'sentence',
        'meaning',
        'meaning_id'
    ];

    public function meanings() {
        return $this -> belongsTo('App\Models\Meaning');
    }
}
