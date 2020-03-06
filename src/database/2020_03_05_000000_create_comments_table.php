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
        Schema::create(config('comments.tables.comments'), function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('body');
            $table->integer('likes')->default(0);
            $table->smallInteger('type')->default(Type::DEFAULT);
            $table->smallInteger('status')->default(Status::PENDING);
            $table->uuid('comment_id')->nullable();
            $table->foreign('comment_id')
                ->references('id')
                ->on(config('comments.tables.comments'))
                ->onDelete('cascade');
            $table->uuid('commentable_id')->index();
            $table->string('commentable_type');
            $table->uuid('commenter_id')->index();
            $table->string('commenter_type');
            $table->timestamps();
            $table->softDeletes();
            $table->index([
                'status',
                'commentable_id',
                'commentable_type',
                'commenter_id',
                'commenter_type'
            ], 'status_commentable_commenter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('comments.tables.comments'));
    }
}
