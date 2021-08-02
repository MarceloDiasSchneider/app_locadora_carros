<?

//MARCAS
public function up()
{
    Schema::create('marcas', function (Blueprint $table) {
        $table->id();
        $table->string('nome', 30)->unique();
        $table->string('imagem', 100)->comment('Logo da marca');
        $table->timestamps();
    });
}

//MODELOS
public function up()
{
    Schema::create('modelos', function (Blueprint $table) {
        $table->id();

        $table->timestamps();

        //foreign key (constraints)
        $table->foreign('marca_id')->references('id')->on('marcas');
    });
}

//CARROS
public function up()
{
    Schema::create('carros', function (Blueprint $table) {
        $table->id();

        $table->timestamps();


    });
}

//CLIENTES
public function up()
{
    Schema::create('clientes', function (Blueprint $table) {
        $table->id();

        $table->timestamps();
    });
}

//LOCACOES
// Renomear a classe para CreateLocacoesTable
// Renomear a tabela para locacoes (ajustar o model)
public function up()
{
    Schema::create('locacoes', function (Blueprint $table) {
        $table->id();

        $table->timestamps();

    });
}
