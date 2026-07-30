<?php

use App\Enums\Gender;
use App\Enums\NstpComponent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_year_id')->nullable()->constrained()->nullOnDelete();
            $table->string('nstp_component')->default(NstpComponent::CWTS->value);
            $table->string('serial_number', 50)->unique();
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('course', 50)->index();
            $table->string('gender')->default(Gender::OTHER->value);
            $table->date('birth_date')->nullable();
            $table->string('city_address', 200)->nullable();
            $table->string('province_address', 200)->nullable();
            $table->string('contact_number', 50)->nullable();
            $table->string('email')->nullable()->unique();
            $table->timestamps();

            $table->index(['nstp_component', 'last_name', 'first_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};