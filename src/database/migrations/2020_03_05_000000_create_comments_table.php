<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Rockbuzz\LaraComments\Enums\{Status, Type};

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $columns = config('comments.tables.morph_columns');

        Schema::create('comments', function (Blueprint $table) use ($columns) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('body');
            $table->integer('likes')->default(0);
            $table->smallInteger('type')->default(Type::DEFAULT);
            $table->smallInteger('status')->default(Status::PENDING);
            $table->uuid('comment_id')->nullable();
            $table->foreign('comment_id')
                ->references('id')
                ->on('comments')
                ->onDelete('cascade');
            $table->uuid($columns['commentable_id'])->index();
            $table->string($columns['commentable_type']);
            $table->uuid($columns['commenter_id'])->index();
            $table->string($columns['commenter_type']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
