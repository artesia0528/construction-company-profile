<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table): void {
            $table->string('seo_title')->nullable()->after('short_description');
            $table->text('seo_description')->nullable()->after('seo_title');
            $table->string('seo_image')->nullable()->after('seo_description');
            $table->string('canonical_url', 2048)->nullable()->after('seo_image');
            $table->boolean('noindex')->default(false)->after('canonical_url');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table): void {
            $table->dropColumn(['seo_title', 'seo_description', 'seo_image', 'canonical_url', 'noindex']);
        });
    }
};
