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
        Schema::rename('teams', 'persons');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Mengembalikan nama tabel 'persons' menjadi 'team'
        Schema::rename('persons', 'team');
    }
};
