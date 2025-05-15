<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterForeing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Se necessário, remova a coluna 'profile_id'
            $table->dropColumn('profile_id');
        });

        Schema::table('users', function (Blueprint $table) {
            // Agora adicione novamente a coluna 'profile_id' com a chave estrangeira correta
            $table->foreignId('profile_id')->nullable()->constrained('profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['profile_id']);
            $table->dropColumn('profile_id');
        });
    }
}
