<?php namespace Firestarter\Shop\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateOrdersTable extends Migration
{

    public function up()
    {
        if (!Schema::hasTable('firestarter_shop_orders'))
        {
            Schema::create('firestarter_shop_orders', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('product_id')->nullable();
                $table->integer('coupon_id')->nullable();
                $table->string('user_name')->nullable();
                $table->string('email')->nullable();
                $table->timestamps();
            });   
        }        
    }

    public function down()
    {
        if (Schema::hasTable('firestarter_shop_orders'))
        {
            Schema::drop('firestarter_shop_orders');
        }
    }

}
