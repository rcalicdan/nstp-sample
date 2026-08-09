<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('csv_upload_id')->nullable()->after('school_year_id')->constrained('csv_uploads')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['csv_upload_id']);
            $table->dropColumn('csv_upload_id');
        });
    }
};
