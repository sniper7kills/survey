<?php
/* 
 * Migrations generated by: Skipper (http://www.skipper18.com)
 * Migration id: 75a43dfd-81f0-4e37-b3ac-aab9be29486e
 * Migration datetime: 2019-12-30 22:35:06.753758
 */ 

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SkipperMigrationsSurvey2019123022350675 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->uuid('id')->unique()->unsigned()->storedAs('text');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable(true);
            $table->boolean('guests')->nullable(true)->default(True);
            $table->timestamp('end_at')->nullable(true);
            $table->timestamp('created_at')->nullable(true);
            $table->timestamp('updated_at')->nullable(true);
            $table->timestamp('deleted_at')->nullable(true);
        });
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->uuid('survey_id');
            $table->string('question');
            $table->enum('type', ["text","radio","select","checkbox"])->default("text");
            $table->boolean('required')->nullable(true)->default(True);
            $table->timestamp('created_at')->nullable(true);
            $table->timestamp('updated_at')->nullable(true);
            $table->timestamp('deleted_at')->nullable(true);
        });
        Schema::create('options', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->uuid('question_id');
            $table->string('value');
            $table->integer('order')->nullable(true)->unsigned();
            $table->timestamp('created_at')->nullable(true);
            $table->timestamp('updated_at')->nullable(true);
            $table->timestamp('deleted_at')->nullable(true);
        });
        Schema::create('responses', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement()->unsigned();
            $table->uuid('survey_id');
            $table->bigInteger('userable_id')->unsigned();
            $table->string('userable_type');
            $table->timestamp('created_at')->nullable(true);
            $table->timestamp('updated_at')->nullable(true);
            $table->timestamp('deleted_at')->nullable(true);
        });
        Schema::create('answers', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement()->unsigned();
            $table->bigInteger('response_id')->unsigned();
            $table->uuid('question_id');
            $table->text('answer')->nullable(true);
            $table->timestamp('created_at')->nullable(true);
            $table->timestamp('updated_at')->nullable(true);
            $table->timestamp('deleted_at')->nullable(true);
        });
        Schema::create('survey_guests', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement()->unsigned();
            $table->ipAddress('ip');
            $table->string('agent');
            $table->timestamp('created_at')->nullable(true);
            $table->timestamp('updated_at')->nullable(true);
            $table->timestamp('deleted_at')->nullable(true);
        });
        Schema::create('survey_option_answers', function (Blueprint $table) {
            $table->bigInteger('answer_id')->unsigned();
            $table->uuid('option_id');
            $table->primary(['answer_id','option_id']);
        });
        Schema::table('questions', function (Blueprint $table) {
            $table->foreign('survey_id')->references('id')->on('surveys');
        });
        Schema::table('options', function (Blueprint $table) {
            $table->foreign('question_id')->references('id')->on('questions');
        });
        Schema::table('responses', function (Blueprint $table) {
            $table->foreign('survey_id')->references('id')->on('surveys');
        });
        Schema::table('answers', function (Blueprint $table) {
            $table->foreign('response_id')->references('id')->on('responses');
        });
        Schema::table('answers', function (Blueprint $table) {
            $table->foreign('question_id')->references('id')->on('questions');
        });
        Schema::table('survey_option_answers', function (Blueprint $table) {
            $table->foreign('answer_id')->references('id')->on('answers');
            $table->foreign('option_id')->references('id')->on('options');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_option_answers', function (Blueprint $table) {
            $table->dropForeign('answer_id');
            $table->dropForeign('option_id');
        });
        if (Schema::hasTable('answers')) {
            Schema::table('answers', function (Blueprint $table) {
                $table->dropForeign(['question_id']);
            });
        }
        if (Schema::hasTable('answers')) {
            Schema::table('answers', function (Blueprint $table) {
                $table->dropForeign(['response_id']);
            });
        }
        if (Schema::hasTable('responses')) {
            Schema::table('responses', function (Blueprint $table) {
                $table->dropForeign(['survey_id']);
            });
        }
        if (Schema::hasTable('options')) {
            Schema::table('options', function (Blueprint $table) {
                $table->dropForeign(['question_id']);
            });
        }
        if (Schema::hasTable('questions')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->dropForeign(['survey_id']);
            });
        }
        Schema::dropIfExists('survey_option_answers');
        Schema::dropIfExists('survey_guests');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('responses');
        Schema::dropIfExists('options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('surveys');
    }
}
