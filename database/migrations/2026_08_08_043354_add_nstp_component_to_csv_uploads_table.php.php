<?php

declare(strict_types=1);

use App\Enums\NstpComponent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('csv_uploads', function (Blueprint $table) {
            $table->string('nstp_component')->default(NstpComponent::CWTS->value)->after('school_year_id');
            $table->index('nstp_component');
        });
    }

    public function down(): void
    {
        Schema::table('csv_uploads', function (Blueprint $table) {
            $table->dropIndex(['nstp_component']);
            $table->dropColumn('nstp_component');
        });
    }
};
