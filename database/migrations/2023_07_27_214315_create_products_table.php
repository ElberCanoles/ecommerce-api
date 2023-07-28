<?php

use App\Domain\Products\Enums\ProductStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string(column: 'name', length: 100)->unique();
            $table->string(column: 'slug', length: 150)->unique();
            $table->mediumText(column: 'description')->nullable();
            $table->unsignedDouble(column: 'price');
            $table->unsignedBigInteger(column: 'stock');
            $table->enum(column: 'status', allowed: ProductStatus::toArray())->default(value: ProductStatus::AVAILABLE->value);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
