<?php

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
    DB::statement("ALTER TABLE appointments MODIFY status ENUM('pending', 'approved', 'rejected', 'cancelled') NOT NULL");
}

public function down(): void
{
    DB::statement("ALTER TABLE appointments MODIFY status ENUM('pending', 'approved', 'rejected') NOT NULL");
}

};
