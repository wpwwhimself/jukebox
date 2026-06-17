<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

if (file_exists(__DIR__.'/Shipyard/shipyard.php')) require __DIR__.'/Shipyard/shipyard.php';

Route::view("/about", "pages.about")->name("about");

Route::controller(MainController::class)->group(function () {
    Route::get("/", "index")->name("home");
    Route::get("/album-data/{id}", "getAlbumData");
    Route::get("/song-data/{id}", "getSongData");
    Route::get("/play/{id}", "getSongForPlayer");
});
