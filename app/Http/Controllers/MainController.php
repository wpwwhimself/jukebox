<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Song;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $albumGroups = Album::visible()
            ->get()
            ->groupBy("is_normal")
            ->sortKeysDesc();

        return view("pages.index", compact(
            "albumGroups",
        ));
    }

    public function getAlbumData(int $id)
    {
        $album = Album::with("songs")->findOrFail($id);

        return response()->json([
            "data" => $album,
            "html" => view("components.album-details", compact("album"))->render(),
        ]);
    }

    public function getSongData(int $id)
    {
        $song = Song::with("album")->findOrFail($id);

        return response()->json([
            "data" => $song,
            "html" => view("components.song-details", compact("song"))->render(),
        ]);
    }
}
