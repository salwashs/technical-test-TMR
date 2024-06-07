<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->string("id")->nullable(false)->primary();
            $table->string("name")->nullable(false);
            $table->string("province")->nullable(false);
            $table->integer("provinceId")->nullable(false);
            $table->string("regency")->nullable(false);
            $table->integer("regencyId")->nullable(false);
            $table->string("district")->nullable(false);
            $table->integer("districtId")->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
