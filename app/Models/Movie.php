<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'movies';
    protected $hidden = ['pivot'];
    protected $fillable = [
        'id', 'name', 'year', 'runtime', 'releasedate', 'storyline',
    ];


    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'cast', 'movieId', 'actorId');
    }
}
