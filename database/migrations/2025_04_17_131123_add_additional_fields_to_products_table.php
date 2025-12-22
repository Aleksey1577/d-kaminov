<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'material')) {
                $table->string('material')->nullable();
            }
            if (!Schema::hasColumn('products', 'vysota')) {
                $table->string('vysota')->nullable();
            }
            if (!Schema::hasColumn('products', 'shirina')) {
                $table->string('shirina')->nullable();
            }
            if (!Schema::hasColumn('products', 'glubina')) {
                $table->string('glubina')->nullable();
            }
            if (!Schema::hasColumn('products', 'ves')) {
                $table->string('ves')->nullable();
            }
            if (!Schema::hasColumn('products', 'tsvet')) {
                $table->string('tsvet')->nullable();
            }
            if (!Schema::hasColumn('products', 'garantiya')) {
                $table->string('garantiya')->nullable();
            }
            if (!Schema::hasColumn('products', 'image_url_1')) {
                $table->string('image_url_1')->nullable();
            }
            if (!Schema::hasColumn('products', 'image_url_2')) {
                $table->string('image_url_2')->nullable();
            }
            if (!Schema::hasColumn('products', 'image_url_3')) {
                $table->string('image_url_3')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'material', 'vysota', 'shirina', 'glubina', 'ves', 'tsvet', 'garantiya',
                'image_url_1', 'image_url_2', 'image_url_3'
            ]);
        });
    }
}
