<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zakazky', function (Blueprint $table) {
            $table->id();
            $table->string('zakaznik');
            $table->decimal('celkom', 10, 2)->default(0);
            $table->enum('stav', ['nevybavena', 'vybavena'])->default('nevybavena');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zakazky');
    }
};
