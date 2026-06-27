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
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('site_name')->default('THE LAST DAYS COVENANTS');
        $table->string('primary_color')->default('#00283c');
        $table->string('secondary_color')->default('#FFFFFF');
        $table->string('logo')->nullable();
        $table->string('youtube_channel')->nullable();
        $table->string('contact_email')->nullable();
        $table->softDeletes();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};








