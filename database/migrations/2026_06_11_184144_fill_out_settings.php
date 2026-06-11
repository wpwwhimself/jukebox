<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ([
            "app_name" => "Jukebox",
            "app_logo_path" => ((env("APP_ENV") === "local") ? "http://localhost:8000/" :  "https://jukebox.wpww.pl/") . "media/hydrophilia.svg",
            "app_adaptive_dark_mode" => 1,
            "metadata_title" => "WPWW's Jukebox",
            "metadata_author" => "Wojciech Przybyła",
            "users_login_is" => "none",
            "app_beginning" => 2018,
        ] as $setting => $value) {
            Setting::find($setting)->update(compact("value"));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
