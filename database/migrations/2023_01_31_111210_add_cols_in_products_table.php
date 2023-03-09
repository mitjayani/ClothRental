<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsInProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('length')->nullable()->after('weight');
            $table->string('breadth')->nullable()->after('length');
            $table->string('height')->nullable()->after('breadth');
            $table->string('local_delivery_charge')->nullable()->after('shipping_cost');
            $table->string('zonal_delivery_charge')->nullable()->after('local_delivery_charge');
            $table->string('national_delivery_charge')->nullable()->after('zonal_delivery_charge');
            $table->integer('variation_parent_product_id')->nullable()->after('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(
                'length',
                'breadth',
                'height',
                'local_delivery_charge',
                'zonal_delivery_charge',
                'national_delivery_charge',
                'variant_parent_product_id'
            );
        });
    }
}
