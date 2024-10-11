<?php

use App\Enum\ProductType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('parent_id')->unsigned()->nullable()->index();
            $table->string('type', 1)->default(ProductType::PRODUCT->value)->index();
            $table->string('code', 16)->unique();
            $table->string('name', 127);
            $table->string('slug', 127);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('weight')->unsigned()->nullable();
            $table->decimal('calories')->unsigned()->nullable();
            $table->integer('price');
            $table->string('label',16)->nullable();
            $table->integer('active')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index('slug', 'slug_idx', 'hash');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('products');
        Schema::enableForeignKeyConstraints();
    }
};
