<?php namespace Firestarter\Shop\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateCurrenciesTable extends Migration
{

    public function up()
    {
        
        if (!Schema::hasTable('firestarter_shop_currencies'))
        {
            Schema::create('firestarter_shop_currencies', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('sign')->nullable();
                $table->decimal('value', 15, 4);
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
        }        
    }

    public function down()
    {
        if (Schema::hasTable('firestarter_shop_currencies'))
        {
            Schema::drop('firestarter_shop_currencies');
        }
    }

}
