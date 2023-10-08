<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Torrent;

class TorrentHistoryController extends Controller
{
    /**
     * Display History Of A Torrent.
     */
    public function index(int $id): \Illuminate\Contracts\View\Factory|\Illuminate\View\View
    {
        $torrent = Torrent::withAnyStatus()->findOrFail($id);
        $history = History::with(['user'])->where('torrent_id', '=', $id)->latest()->get();

        return \view('torrent.history', ['torrent' => $torrent, 'history' => $history]);
    }
}
