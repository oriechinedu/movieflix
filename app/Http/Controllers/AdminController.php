<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Genre;
use App\Movie;
use Hash;
use App\FeaturedMovie;
use App\MusicSubGenre;
use App\ComedySubGenre;
use App\AudioUserType;
use App\AudioGenre;
use App\Audio;

class AdminController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:admin');
    }

    public function index()
    {
    	return view('admin.dashboard');
    }

    public function users()
    {
    	$users = User::latest()->get();

    	return view('admin.users', compact('users'));
    }

    public function deleteUser(User $user)
    {
    	$user->delete();

    	return back()->with('success', 'Deleted');
    }

    public function uploadVideo(Request $request)
    {
        $this->validate($request, [
        	'title' => 'required',
            'video' => 'required',
        ]);
       
        $video = $request->file('video');
        $ext = $video->getClientOriginalExtension();

        $destination = public_path('/movies/');
        
        $filename = str_slug($request->title).'-'.time().'-'.date('Y-m-d').'.'.$ext;
        $url = asset('movies/'.$filename);

        try {
            $video->move($destination, $filename);
        } catch (\Exception $e) {

            report($e);
            return back()->with('error','Video failed to upload');
            
        }

        $newVideo = new Movie();

        $newVideo->genre_id = $request->genre_id;
        $newVideo->url = $url;
        $newVideo->description = $request->description;
        $newVideo->title = $request->title;
        $newVideo->save();

        if ($newVideo) {
           
            return back()->with('success', 'Uploaded');
        }
            return back()->with('error', 'failed to upload');
    }

    public function uploadMusicVideo(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'video' => 'required',
        ]);
      
        $video = $request->file('video');
        $ext = $video->getClientOriginalExtension();

        $destination = public_path('/movies/music/');
        
        $filename = str_slug($request->title).'-'.time().'-'.date('Y-m-d').'.'.$ext;
        $url = asset('movies/music/'.$filename);

        try {
            $video->move($destination, $filename);
        } catch (\Exception $e) {

            throw $e;
            // return back()->with('error','Video failed to upload');
            
        }

        $newVideo = new Movie();

        $newVideo->genre_id = $request->genre_id;
        $newVideo->url = $url;
        $newVideo->description = $request->description;
        $newVideo->title = $request->title;
        $newVideo->music_id = $request->music_id;
        $newVideo->save();

        if ($newVideo) {
           
            return back()->with('success', 'Uploaded');
        }
            return back()->with('error', 'failed to upload');
    }

    public function uploadComedyVideo(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'video' => 'required',
        ]);
      
        $video = $request->file('video');
        $ext = $video->getClientOriginalExtension();

        $destination = public_path('/movies/comedy/');
        
        $filename = str_slug($request->title).'-'.time().'-'.date('Y-m-d').'.'.$ext;
        $url = asset('movies/comedy/'.$filename);

        try {
            $video->move($destination, $filename);
        } catch (\Exception $e) {

            throw $e;
            // return back()->with('error','Video failed to upload');
            
        }

        $newVideo = new Movie();

        $newVideo->genre_id = $request->genre_id;
        $newVideo->url = $url;
        $newVideo->description = $request->description;
        $newVideo->title = $request->title;
        $newVideo->comedy_id = $request->comedy_id;
        $newVideo->save();

        if ($newVideo) {
           
            return back()->with('success', 'Uploaded');
        }
            return back()->with('error', 'failed to upload');
    }

    public function uploadLiveTv(Request $request)
    {
        $this->validate($request, [
            'title'=> 'required',
            'url' => 'required',
        ]);
        $newVideo = new Movie();

        $newVideo->genre_id = $request->genre_id;
        $newVideo->url = $request->url;
        $newVideo->description = $request->description;
        $newVideo->title = $request->title;
        $newVideo->save();

         if ($newVideo) {
           
            return back()->with('success', 'Live TV added');
        }
            return back()->with('error', 'failed to upload');
    }

    public function editVideo(Request $request, Movie $movie)
    {
        

        $this->validate($request, [
        	'poster' => 'nullable|mimetypes:image/jpeg,image/jpg, image/png, image/gif',
        	'title' => 'required',
        ]);

         if ($request->hasFile('poster')) {
        	$poster = $request->file('poster');
	        $ext = $poster->getClientOriginalExtension();

	        $destination = public_path('/posters/');
	        
	        $filename = str_slug($request->title).'-'.time().'-'.date('Y-m-d').'.'.$ext;
	        $url = asset('posters/'.$filename);

	        try {
	            $poster->move($destination, $filename);
	        } catch (\Exception $e) {

	            report($e);
	            return back()->with('error','Poster failed to upload');
	            
	        }
        }

        $movie->poster = $request->hasFile('poster') ? $url : $movie->poster;
        $movie->description = $request->description;
        $movie->title = $request->title;
        $movie->year = $request->year;
        $movie->casts = $request->casts;
        $movie->rating = $request->rating;

        if ($movie->save()) {
           
            return back()->with('success', 'Edited');
        }
            return back()->with('error', 'failed to upload');
    }
    public function videos()
    {
    	$genres = Genre::latest()->get();
        $videos = Movie::latest()->get();
    	$music = MusicSubGenre::latest()->get();
    	$comedies = ComedySubGenre::latest()->get();

    	return view('admin.videos', compact('genres', 'videos', 'music', 'comedies'));
    }

    public function addGenre(Request $request)
    {
    	
    
    	$this->validate($request, [
    		'types' => 'required',
    		'name' => 'required',
    	]);
    	$genre = new Genre();
    	$genre->name = $request->name;
    	$genre->types = $request->types;
    	$genre->save();

    	return back()->with('success', 'Added');
    }
    public function editGenre(Request $request, Genre $genre)
    {
    	
    	$this->validate($request, [
    		'types' => 'required',
    		'name' => 'required',
    	]);

    	$genre->name = $request->name;
    	$genre->types = $request->types;
    	$genre->save();

    	return back()->with('success', 'edited!');
    }

    public function genres(Request $request)
    {
    	$genres = Genre::latest()->get();

    	return view('admin.genres', compact('genres'));
    }

    public function deleteMovie(Movie $movie, Request $request)
    {	
    	
    	if (strtolower($movie->genre->name) != 'live tv') {

            $file = explode(url('/'), $movie->url);

            $file_path= public_path($file[1]);

            if ($file_path) {

                try {
                    unlink($file_path);
                } catch (\Exception $e) {

                    return back()->with('error', $e->getMessage());
                    
                }
            }

        }
    	if ($movie->poster){

    		$poster_path = public_path(explode(url('/'), $movie->poster)[1]);

    		try {
    			unlink($poster_path);
    		} catch (\Exception $e) {

    			return back()->with('error', $e->getMessage());
    		}
    	}

    	if ($movie->delete()) {

    		return back()->with('success', 'Deleted');
    	}

    	return back()->with('error', 'Failed to delete');
    }

    public function addUser(Request $request)
    {
    	$this->validate($request, [
    		'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:4',
    	]);

	   $user = 	User::create([
		    		'name' => $request->name,
		    		'username' => $request->username,
		    		'password' => Hash::make($request->password),
		    		'type'     => $request->type ?: 'basic',
		    		'email'   => $request->email?: null,
	    		]);
	   if ($user) {
	   	return back()->with('success', 'User created');
	   }
	   	return back()->with('error', 'Failed to create user');

    }

    public function editUser(Request $request, User $user)
    {
    	$this->validate($request, [
    		'username' => 'required|string|max:255',
            'password' => 'nullable|string|min:4',
    	]);

    if ($request->username != $user->username) {
	   $user->username = $request->username;

    }
	   $user->email = $request->email?: $user->email;
	   $user->name	= $request->name ?: $user->name;
	   $user->type	= $request->type ?: $user->type;
	   $user->password = $request->password ? 
	   					 Hash::make($request->password) : $user->password;
	   
	   if ($user->save()) {
	   	return back()->with('success', 'User edited');
	   }
	   	return back()->with('error', 'Failed to edit user');

    }

    public function deleteGenre(Genre $genre)
    {
    	if (count($genre->movies) === 0) {

    		$genre->delete();

    		return back()->with('success', 'Deleted');
    	}

    	return back()->with('error', 'Could not delete genre');

    }

    public function setFeaturedMovie(Request $request)
    {	
    	$this->validate($request, [
    		'type' => 'required',
    		'movie_id' => 'required',
    	]);
    	$featured = new FeaturedMovie();

    	$featured->type = $request->type;
    	$featured->movie_id = $request->movie_id;

    	if ($featured->save()) {

    		return back()->with('success', 'faetured movie set for '. title_case($request->type). ' category');
    	}

    	return back()->with('error', 'failed');
    }

    public function addMusicSubGenre(Request $request)
    {
        $this->validate($request, ['name' => 'required']);

        $sub = MusicSubGenre::create(['name' => $request->name]);

        return back()->with('success', 'added!');
    }
    public function addComedySubGenre(Request $request)
    {
        $this->validate($request, ['name' => 'required']);

        $sub = ComedySubGenre::create(['name' => $request->name]);

        return back()->with('success', 'added!');
    }

    public function search(Request $request)
    {
        dd($request->all());
    }

    public function audio()
    {   
        $types = AudioUserType::first();
        $genres = AudioGenre::all();
        $audios = Audio::latest()->get();
        $data['genre'] = $types;
        $data['genres'] = $genres;
        $data['audios'] = $audios;


        return view('admin.audio', $data);
    }

    public function audioType(Request $request)
    {   
        $this->validate($request, ['types' => 'required']);
        $exists = AudioUserType::first();

        if ($exists) {

            $u = $exists->update(['types' => $request->types]);

            if ($u) return back()->with('success', 'updated');
            return back()->with('error', 'failed');
        }


        $new = AudioUserType::create([

                    'types' => $request->types
                ]);
        if ($new) return back()->with('success', 'Added');

        return back()->with('error', 'failed');
    }

    public function audioGenre(Request $request)
    {   
        $this->validate($request, ['name' => 'required']);

        $new = AudioGenre::firstOrCreate(['name' => $request->name]);

        if ($new) return back()->with('success', 'Added');

        return back()->with('error', 'failed');
    }

    public function audioUpload(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'audio' => 'required|mimetypes:audio/*',
        ]);
      
        $audio = $request->file('audio');
        $ext = $audio->getClientOriginalExtension();

        $destination = public_path('/audio/');
        
        $filename = str_slug($request->name).'-'.time().'-'.date('Y-m-d').'.'.$ext;
        $url = asset('audio/'.$filename);

        try {
            $audio->move($destination, $filename);
        } catch (\Exception $e) {

            report($e);
            return back()->with('error','audio failed to upload');
            
        }

        $newAudio = new Audio();

        $newAudio->genre_id = $request->genre_id;
        $newAudio->url = $url;
        $newAudio->description = $request->description;
        $newAudio->name = $request->name;
        $newAudio->author = $request->author;
        $newAudio->year = $request->year;
        $newAudio->save();

        if ($newAudio) {
           
            return back()->with('success', 'Uploaded');
        }
            return back()->with('error', 'failed to upload');
    }

    public function audioUpdate(Request $request, Audio $audio)
    {   
        $this->validate($request, [
            'poster' => 'nullable|mimetypes:image/jpeg,image/jpg, image/png, image/gif',
            'name' => 'required',
        ]);

        $url = '';
        if ($request->hasFile('poster')) {
            $poster = $request->file('poster');
            $ext = $poster->getClientOriginalExtension();

            $destination = public_path('/audio/posters/');
            
            $filename = str_slug($request->name).'-'.time().'-'.date('Y-m-d').'.'.$ext;
            $url = asset('audio/posters/'.$filename);

            try {
                $poster->move($destination, $filename);
            } catch (\Exception $e) {

                report($e);
                return back()->with('error','Poster failed to upload');
                
            }
        }

        $data = $request->all();
        $data['poster'] = $request->hasFile('poster') ? $url : $audio->poster;
        $updated = $audio->update($data);

        if ($updated) {
           
            return back()->with('success', 'Updated');
        }
            return back()->with('error', 'failed to update');
    }

    public function audioDelete(Audio $audio)
    {

        $file = explode(url('/'), $audio->url);

        $file_path= public_path($file[1]);

        if ($file_path) {

            try {
                unlink($file_path);
            } catch (\Exception $e) {

                return back()->with('error', $e->getMessage());
                
            }
        }

        if ($audio->delete()) return back()->with('success', 'deleted');

        return back()->with('error', 'failed');
    }
}
