<?php namespace Firestarter\Shop\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateCouponsTable extends Migration
{

    public function up()
    {
        if (!Schema::hasTable('firestarter_shop_coupons'))
        {
            Schema::create('firestarter_shop_coupons', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('value');
                $table->smallInteger('discount')->default(0);
                $table->integer('product_id')->nullable();          
                $table->boolean('is_used')->default(false);
                $table->timestamps();
            });
        }        
    }

    public function down()
    {
        
        if (Schema::hasTable('firestarter_shop_coupons'))
        {
            Schema::drop('firestarter_shop_coupons');
        }
    }

}
