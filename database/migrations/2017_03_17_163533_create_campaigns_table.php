<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('campaign_owner_commission')->nullable();
            $table->decimal('goal')->nullable();
            $table->decimal('min_amount')->nullable();
            $table->decimal('max_amount')->nullable();
            $table->decimal('recommended_amount')->nullable();
            $table->string('amount_prefilled')->nullable();
            $table->string('end_method', 20)->nullable();
            $table->integer('views')->nullable();
            $table->string('video')->nullable();
            $table->string('feature_image')->nullable();
            $table->tinyInteger('status')->nullable(); //0:pending,1:approve,2:blocked
            $table->mediumInteger('country_id')->nullable();
            $table->string('address')->nullable();

            $table->tinyInteger('is_funded')->nullable(); //0:pending,1:approve,2:blocked
            $table->tinyInteger('is_staff_picks')->nullable(); //1:staff Picks
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();


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
        Schema::dropIfExists('campaigns');
    }
}
