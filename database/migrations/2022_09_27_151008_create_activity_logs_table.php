<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    use App\Database\AuditableTrait;

    class CreateActivityLogsTable extends Migration
    {
        use AuditableTrait;

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            if (!Schema::hasTable('activity_logs')) {
                Schema::create('activity_logs', function (Blueprint $table) {
                    $table->increments('id');
                    $table->integer('loggable_id')->unsigned();
                    $table->string('loggable_type', 255);
                    $table->string('type')->nullable();
                    $table->string('subject', 191)->nullable();
                    $table->longText('data_submitted')->nullable();
                    $table->longText('data_changed')->nullable();
                    $table->longText('previous_data')->nullable();
                    $table->longText('response')->nullable();
                    $this->auditable($table);
                    $table->index(['loggable_id', 'loggable_type']);
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
            Schema::dropIfExists('activity_logs');
        }
    }
