<?php namespace Firestarter\Shop\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateVendorsTable extends Migration
{

    public function up()
    {
        if (!Schema::hasTable('firestarter_shop_vendors'))
        {
            Schema::create('firestarter_shop_vendors', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('slug')->index()->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });   
        }        
    }

    public function down()
    {
        if (Schema::hasTable('firestarter_shop_vendors'))
        {
            Schema::dropIfExists('firestarter_shop_vendors');
        }

    }

}
