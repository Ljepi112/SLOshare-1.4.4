<?php

namespace App\Http\Controllers\MediaHub;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display All Movies.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('mediahub.movie.index');
    }

    /**
     * Show A Movie.
     */
    public function show(Request $request, int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = $request->user();
        $personalFreeleech = \cache()->rememberForever(
            'personal_freeleech:'.$user->id,
            fn () => $user->personalFreeleeches()->exists()
        );
        $movie = Movie::with(['cast', 'collection', 'genres', 'companies'])->findOrFail($id);

        return \view('mediahub.movie.show', [
            'movie'              => $movie,
            'user'               => $user,
            'personal_freeleech' => $personalFreeleech,
        ]);
    }
}
