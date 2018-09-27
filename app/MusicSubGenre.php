<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Movie;

class MusicSubGenre extends Model
{	
	
	protected $guarded = [];

    public function genre()
    {
    	return $this->belongsTo(Genre::class, 'genre_id');
    }

    public function movies()
    {
    	return Movie::where('music_id', $this->id)->orderBy('created_at', 'desc')->get();
    }
}