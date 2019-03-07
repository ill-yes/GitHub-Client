<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePullrequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pullrequests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('title');
            $table->unsignedInteger('pr_link');
            $table->timestamp('branch_name');
            $table->integer('branch_commit_sha');
            $table->integer('merged_at');
            $table->integer('merge_commit_sha');
            $table->integer('user_login');
            $table->integer('user_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pullrequests');
    }
}
