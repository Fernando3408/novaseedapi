<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradesTable extends Migration{
    public function up(): void
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->increments('id');
            $table->date('trade_date')->index();

            $table->string('player1');
            $table->string('player2');
            $table->string('team1')->nullable();
            $table->string('team2')->nullable();

            $table->unsignedTinyInteger('ht1')->default(0);
            $table->unsignedTinyInteger('ht2')->default(0);

            $table->decimal('odds', 8, 2)->nullable();
            $table->unsignedTinyInteger('parcial1')->nullable();    
            $table->unsignedTinyInteger('parcial2')->nullable();

            $table->unsignedTinyInteger('ft1')->default(0);
            $table->unsignedTinyInteger('ft2')->default(0);

            // spread u otro indicador
            $table->decimal('sp', 10, 2)->nullable();

            // stake/monto operado (para calcular profit % si no entregas profit_pct)
            $table->decimal('stake', 14, 2)->nullable();

            // TP / SL / BE
#            $table->enum('result', ['tp', 'sl', 'be'])->index();
            $table->enum('wl', ['win','loss','be'])->index();

            // Si quieres guardar el % exacto de esa operación
            $table->decimal('profit_pct', 8, 2)->nullable();

            // Si quieres guardar el monto neto de esa operación
            $table->decimal('profit_amount', 14, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
