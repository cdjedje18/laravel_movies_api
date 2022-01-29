<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'actors';
    protected $fillable = [
        'id', 'name', 'birthname', 'birthdate', 'birthplace'
    ];
}
