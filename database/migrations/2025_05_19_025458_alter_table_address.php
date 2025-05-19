<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('neighborhood', 100)->after('cep');
            $table->string('city', 50)->after('neighborhood');
            $table->string('state', 2)->after('city');
            $table->string('number', 10)->nullable()->after('state');
            $table->string('complement', 100)->nullable()->after('number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('neighborhood');
            $table->dropColumn('complement');
            $table->dropColumn('number');
            $table->dropColumn('city');
            $table->dropColumn('state');
        });
    }
}