<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->string('cover_alt_text')->nullable()->after('cover_image');
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->string('thumbnail_alt_text')->nullable()->after('thumbnail_image');
        });

        Schema::table('teams', function (Blueprint $table): void {
            $table->string('photo_alt_text')->nullable()->after('profile_photo');
        });

        Schema::table('testimonials', function (Blueprint $table): void {
            $table->string('photo_alt_text')->nullable()->after('client_photo');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $table->dropColumn('cover_alt_text');
        });

        Schema::table('services', function (Blueprint $table): void {
            $table->dropColumn('thumbnail_alt_text');
        });

        Schema::table('teams', function (Blueprint $table): void {
            $table->dropColumn('photo_alt_text');
        });

        Schema::table('testimonials', function (Blueprint $table): void {
            $table->dropColumn('photo_alt_text');
        });
    }
};
