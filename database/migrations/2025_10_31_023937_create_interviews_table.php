
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('sous_titre')->nullable();
            $table->string('image')->nullable();
            $table->string('nom_interviewe');
            $table->string('poste_interviewe');
            $table->string('club_equipe')->nullable();
            $table->text('introduction')->nullable();
            $table->longText('contenu');
            $table->date('date_interview');
            $table->timestamp('date_publication')->nullable();
            $table->string('auteur')->nullable();
            $table->enum('categorie', ['joueur', 'entraineur', 'dirigeant', 'arbitre', 'journaliste', 'autre'])->default('joueur');
            $table->enum('statut', ['brouillon', 'publie', 'archive'])->default('brouillon');
            $table->unsignedInteger('vues')->default(0);
            $table->foreignId('admin_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('interviews');
    }
};
