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
            $table->string('repository')->index('repoIndex');
            $table->string('title');
            $table->string('pr_link');
            $table->string('branch_name');
            $table->string('branch_commit_sha');
            $table->timestamp('merged_at');
            $table->string('merge_commit_sha');
            $table->string('user_login');
            $table->string('user_url');
            $table->string('location');
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
