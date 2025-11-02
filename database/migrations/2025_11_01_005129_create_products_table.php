<?php

declare(strict_types=1);

use App\Domain\Catalog\Models\Supplier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domain\Catalog\Models\Category;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->fulltext();
            $table->string('sku')->unique()->fulltext();
            $table->foreignIdFor(Category::class)->constrained();
            $table->foreignIdFor(Supplier::class)->constrained()->restrictOnDelete();
            $table->string('ean')->fulltext()->nullable()->unique();
            $table->longText('description')->nullable();
            $table->decimal('weight')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('qty_in_pack')->default(1)->unsigned();
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
