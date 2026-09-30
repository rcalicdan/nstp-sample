<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('post_images', function (Blueprint $table) {
            $table->string('width', 20)->default('100%')->after('mime_type');
            $table->string('alignment', 20)->default('center')->after('width');
            $table->text('caption')->nullable()->after('alignment');
        });
    }

    public function down(): void
    {
        Schema::table('post_images', function (Blueprint $table) {
            $table->dropColumn(['width', 'alignment', 'caption']);
        });
    }
};
