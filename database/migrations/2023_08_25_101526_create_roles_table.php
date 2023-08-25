<?php

use App\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        //creazione di un array con le categorie da inserire 
        $roles = ['admin','revisor','user'];
        //foreach per l'inserimento di un nuovo record per ogni elemento delle nostre categorie nella tabella
        foreach ($roles as $role){
            Role::create(['name'=>$role]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
