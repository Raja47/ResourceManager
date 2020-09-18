<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('resource_category_id');
            $table->bigInteger('creator_id')->nullable();             
            $table->string('title', 191);
            $table->text('description')->nullable(true);
            $table->longText('keywords')->nullable(true);
            $table->longText('notes')->nullable(true);        
            $table->tinyInteger('status')->default(1);            
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable(true);
            $table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resources');
    }
}
