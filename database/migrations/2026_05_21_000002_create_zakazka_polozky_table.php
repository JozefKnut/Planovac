<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zakazka_polozky', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zakazka_id')->constrained('zakazky')->onDelete('cascade');
            $table->foreignId('vyrobok_id')->constrained('vyrobky')->onDelete('cascade');
            $table->decimal('mnozstvo', 10, 3);
            $table->decimal('cena_za_jednotku', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zakazka_polozky');
    }
};
