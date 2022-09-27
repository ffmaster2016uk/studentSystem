<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    use App\Database\AuditableTrait;

    return new class extends Migration
    {
        use AuditableTrait;

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            if (!Schema::hasTable('students')) {
                Schema::create('students', function (Blueprint $table) {
                    $table->increments('Id');
                    $table->string('Name', 191)->nullable();
                    $table->string('Surname', 191)->nullable();
                    $table->string('IdentificationNo', 191)->nullable();
                    $table->string('Country', 191)->nullable();
                    $table->dateTime('DateOfBirth')->nullable();
                    $table->dateTime('RegisteredOn')->nullable();
                    $this->auditable($table);
                });
            }

        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('students');
        }
    };
