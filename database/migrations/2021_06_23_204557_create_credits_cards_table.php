<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditsCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->unsigned();
            $table->string('name'); //nome
            $table->string('cvv'); //cvv
            $table->string('cardNumber'); //numero
            $table->string('brand'); //bandeira
            $table->string('expirationMonth'); //Mês da expiração do cartão
            $table->string('expirationYear'); // Ano da expiração do cartão, é necessário os 4 dígitos.
            $table->string('token')->nullable(); //token gerado no front end
            $table->string('plan_status')->nullable(); //status do plano
            $table->string('plan_token')->nullable(); //token adesão gerado
            $table->string('reference')->nullable(); //referencia única
            $table->integer('status')->unsigned()->nullable(); //status pagamento
            $table->timestamp('validated_at')->nullable(); //validado no dia
            $table->timestamps();
        });

        Schema::table('credits_cards', function ($table) {
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credits_cards');
    }
}
