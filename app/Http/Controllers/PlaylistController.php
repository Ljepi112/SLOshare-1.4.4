<?php

namespace App\Http\Controllers;

use App\Helpers\Bencode;
use App\Models\Movie;
use App\Models\Cartoon;
use App\Models\Playlist;
use App\Models\PlaylistTorrent;
use App\Models\Torrent;
use App\Models\Tv;
use App\Models\CartoonTv;
use App\Repositories\ChatRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use ZipArchive;

/**
 * @see \Tests\Todo\Feature\Http\Controllers\PlaylistControllerTest
 */
class PlaylistController extends Controller
{
    /**
     * PlaylistController Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
    }

    /**
     * Display All Playlists.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $playlists = Playlist::with('user')->withCount('torrents')->where(function ($query) {
            $query->where('is_private', '=', 0)
                ->orWhere(function ($query) {
                    $query->where('is_private', '=', 1)->where('user_id', '=', \auth()->id());
                });
        })->oldest('name')->paginate(24);

        return \view('playlist.index', ['playlists' => $playlists]);
    }

    /**
     * Show Playlist Create Form.
     */
    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        return \view('playlist.create');
    }

    /**
     * Store A New Playlist.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $user = \auth()->user();

        $playlist = new Playlist();
        $playlist->user_id = $user->id;
        $playlist->name = $request->input('name');
        $playlist->description = $request->input('description');
        $playlist->cover_image = null;

        if ($request->hasFile('cover_image') && $request->file('cover_image')->getError() === 0) {
            $image = $request->file('cover_image');
            $filename = 'playlist-cover_'.\uniqid('', true).'.'.$image->getClientOriginalExtension();
            $path = \public_path('/files/img/'.$filename);
            Image::make($image->getRealPath())->fit(400, 225)->encode('png', 100)->save($path);
            $playlist->cover_image = $filename;
        }

        $playlist->position = $request->input('position');
        $playlist->is_private = $request->input('is_private');

        $v = \validator($playlist->toArray(), [
            'user_id'     => 'required',
            'name'        => 'required',
            'description' => 'required',
            'is_private'  => 'required',
        ]);

        if ($v->fails()) {
            return \to_route('playlists.create')
                ->withInput()
                ->withErrors($v->errors());
        }

        $playlist->save();
        // Announce To Shoutbox
        $appurl = \config('app.url');
        if ($playlist->is_private != 1) {
            $this->chatRepository->systemMessage(
                \sprintf('Uporabnik [url=%s/', $appurl).$user->username.'.'.$user->id.']'.$user->username.\sprintf('[/url] je ustvaril nov seznam predvajanja [url=%s/playlists/', $appurl).$playlist->id.']'.$playlist->name.'[/url] preveri zdaj! :slight_smile:'
            );
        }

        return \to_route('playlists.show', ['id' => $playlist->id])
            ->withSuccess(\trans('playlist.published-success'));
    }

    /**
     * Show A Playlist.
     */
    public function show(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $playlist = Playlist::findOrFail($id);

        if ($playlist->is_private) {
            \abort_unless($playlist->user_id === \auth()->id(), 403, \trans('playlist.private-error'));
        }

        $random = PlaylistTorrent::query()
            ->where('playlist_id', '=', $playlist->id)
            ->whereHas('torrent')
            ->inRandomOrder()
            ->first();

        $meta = null;

        if (isset($random)) {
            $torrent = Torrent::where('id', '=', $random->torrent_id)->firstOrFail();

            if ($torrent->category->tv_meta && ($torrent->tmdb || $torrent->tmdb != 0)) {
                $meta = Tv::with('genres', 'networks', 'seasons')->where('id', '=', $torrent->tmdb)->first();
            }

            if ($torrent->category->cartoontv_meta && ($torrent->tmdb || $torrent->tmdb != 0)) {
                $meta = CartoonTv::with('genres', 'networks', 'seasons')->where('id', '=', $torrent->tmdb)->first();
            }

            if ($torrent->category->movie_meta && ($torrent->tmdb || $torrent->tmdb != 0)) {
                $meta = Movie::with('genres', 'cast', 'companies', 'collection')->where('id', '=', $torrent->tmdb)->first();
            }

            if ($torrent->category->cartoon_meta && ($torrent->tmdb || $torrent->tmdb != 0)) {
                $meta = Cartoon::with('genres', 'cast', 'companies', 'collection')->where('id', '=', $torrent->tmdb)->first();
            }
        }

        $torrents = PlaylistTorrent::with(['torrent:id,name,category_id,resolution_id,type_id,tmdb,seeders,leechers,times_completed,size,anon,created_at'])
            ->where('playlist_id', '=', $playlist->id)
            ->whereHas('torrent')
            ->orderBy(function ($query) {
                $query->select('name')
                    ->from('torrents')
                    ->whereColumn('id', 'playlist_torrents.torrent_id')
                    ->latest()
                    ->limit(1);
            })
            ->paginate(26);

        return \view('playlist.show', ['playlist' => $playlist, 'meta' => $meta, 'torrents' => $torrents]);
    }

    /**
     * Show Playlist Update Form.
     */
    public function edit(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $user = \auth()->user();
        $playlist = Playlist::findOrFail($id);

        \abort_unless($user->id == $playlist->user_id || $user->group->is_modo, 403);

        return \view('playlist.edit', ['playlist' => $playlist]);
    }

    /**
     * Update A Playlist.
     */
    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $user = \auth()->user();
        $playlist = Playlist::findOrFail($id);

        \abort_unless($user->id == $playlist->user_id || $user->group->is_modo, 403);

        $playlist->name = $request->input('name');
        $playlist->description = $request->input('description');
        $playlist->cover_image = null;

        if ($request->hasFile('cover_image') && $request->file('cover_image')->getError() === 0) {
            $image = $request->file('cover_image');
            $filename = 'playlist-cover_'.\uniqid('', true).'.'.$image->getClientOriginalExtension();
            $path = \public_path('/files/img/'.$filename);
            Image::make($image->getRealPath())->fit(400, 225)->encode('png', 100)->save($path);
            $playlist->cover_image = $filename;
        }

        $playlist->position = $request->input('position');
        $playlist->is_private = $request->input('is_private');

        $v = \validator($playlist->toArray(), [
            'name'        => 'required',
            'description' => 'required',
            'is_private'  => 'required',
        ]);

        if ($v->fails()) {
            return \to_route('playlists.edit', ['id' => $playlist->id])
                ->withInput()
                ->withErrors($v->errors());
        }

        $playlist->save();

        return \to_route('playlists.show', ['id' => $playlist->id])
            ->withSuccess(\trans('playlist.update-success'));
    }

    /**
     * Delete A Playlist.
     *
     * @throws \Exception
     */
    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $user = \auth()->user();
        $playlist = Playlist::with('torrents')->findOrFail($id);

        \abort_unless($user->id == $playlist->user_id || $user->group->is_modo, 403);

        foreach ($playlist->torrents as $playlistTorrent) {
            $playlistTorrent->delete();
        }

        $playlist->delete();

        return \to_route('playlists.index')
            ->withSuccess(\trans('playlist.deleted'));
    }

    /**
     * Download All Playlist Torrents.
     */
    public function downloadPlaylist(int $id): \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        //  Extend The Maximum Execution Time
        \set_time_limit(300);

        // Playlist
        $playlist = Playlist::with('torrents')->findOrFail($id);

        // Authorized User
        $user = \auth()->user();

        // Define Dir Folder
        $path = \getcwd().'/files/tmp_zip/';

        // Check Directory exists
        if (! File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true, true);
        }

        // Zip File Name
        $zipFileName = \sprintf('[%s]%s.zip', $user->username, $playlist->name);

        // Create ZipArchive Obj
        $zipArchive = new ZipArchive();

        // Get Users History
        $playlistTorrents = PlaylistTorrent::where('playlist_id', '=', $playlist->id)->get();

        if ($zipArchive->open($path.'/'.$zipFileName, ZipArchive::CREATE) === true) {
            $failCSV = '"Name","URL","ID"';
            $failCount = 0;

            foreach ($playlistTorrents as $playlistTorrent) {
                // Get Torrent
                $torrent = Torrent::withAnyStatus()->find($playlistTorrent->torrent_id);

                // Define The Torrent Filename
                $tmpFileName = \sprintf('%s.torrent', Str::slug($torrent->title));

                // The Torrent File Exist?
                if (! \file_exists(\getcwd().'/files/torrents/'.$torrent->file_name)) {
                    $failCSV .= '"'.$torrent->name.'","'.\route('torrent', ['id' => $torrent->id]).'","'.$torrent->id.'"
';
                    $failCount++;
                } else {
                    // Delete The Last Torrent Tmp File If Exist
                    if (\file_exists(\getcwd().'/files/tmp/'.$tmpFileName)) {
                        \unlink(\getcwd().'/files/tmp/'.$tmpFileName);
                    }

                    // Get The Content Of The Torrent
                    $dict = Bencode::bdecode(\file_get_contents(\getcwd().'/files/torrents/'.$torrent->file_name));
                    // Set the announce key and add the user passkey
                    $dict['announce'] = \route('announce', ['passkey' => $user->passkey]);
                    // Remove Other announce url
                    unset($dict['announce-list']);

                    $fileToDownload = Bencode::bencode($dict);
                    \file_put_contents(\getcwd().'/files/tmp/'.$tmpFileName, $fileToDownload);

                    // Add Files To ZipArchive
                    $zipArchive->addFile(\getcwd().'/files/tmp/'.$tmpFileName, $tmpFileName);
                }
            }

            if ($failCount > 0) {
                $CSVtmpName = \sprintf('%s.zip', $playlist->name).'-missingTorrentFiles.CSV';
                \file_put_contents(\getcwd().'/files/tmp/'.$CSVtmpName, $failCSV);
                $zipArchive->addFile(\getcwd().'/files/tmp/'.$CSVtmpName, 'missingTorrentFiles.CSV');
            }

            // Close ZipArchive
            $zipArchive->close();

            $zipFile = $path.'/'.$zipFileName;

            if (\file_exists($zipFile)) {
                return \response()->download($zipFile)->deleteFileAfterSend(true);
            }
        }

        return \redirect()->back()->withErrors(\trans('common.something-went-wrong'));
    }
}