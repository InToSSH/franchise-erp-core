<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('post_code')->nullable();
            $table->string('country')->default('CZ');
            $table->string('cin')->nullable();
            $table->string('tin')->nullable();
            $table->string('bank_account')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
