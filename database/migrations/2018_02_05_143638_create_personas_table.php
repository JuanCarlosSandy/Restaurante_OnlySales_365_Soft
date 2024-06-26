<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 100)->unique();
            $table->string('tipo_documento', 20)->nullable();
            $table->string('num_documento', 20)->nullable();
            $table->string('complemento_id', 20)->nullable();
            $table->string('direccion', 70)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 50)->nullable();
            $table->boolean('estadoCli')->default(true); // Agrega el campo estadoCli de tipo booleano con valor predeterminado true
            $table->timestamps();
        });
        DB::table('personas')->insert(array('id' => '1', 'nombre' => 'root', 'tipo_documento' => 'NA', 'num_documento' => '00000000', 'direccion' => 'NA', 'telefono' => '000000', 'email' => 'root@gmail.com', 'estadoCli' => false ));

        DB::table('personas')->insert(array('id' => '2', 'nombre' => 'vend', 'tipo_documento' => 'NA', 'num_documento' => '00000001', 'direccion' => 'NA', 'telefono' => '000000', 'email' => 'vend@gmail.com', 'estadoCli' => false ));

        DB::table('personas')->insert(array('id' => '3', 'nombre' => 'alma', 'tipo_documento' => 'NA', 'num_documento' => '00000002', 'direccion' => 'NA', 'telefono' => '000000', 'email' => 'alma@gmail.com', 'estadoCli' => false ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}