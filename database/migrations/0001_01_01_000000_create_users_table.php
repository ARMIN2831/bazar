<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->foreignId('province_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('village_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('nationalCode')->unique()->nullable();
            $table->string('mobile')->unique()->nullable();
            $table->string('password')->nullable();
            $table->integer('email_active')->default(0);
            $table->integer('mobile_active')->default(0);
            $table->string('type')->nullable();
//provider
            $table->string('postal_code')->nullable();
            $table->integer('postal_active')->default(0);
            $table->string('cart_image')->nullable();
            $table->string('account_number')->nullable();
            $table->string('card_number', 16)->nullable();
            $table->string('iban', 24)->nullable();
//customer
            $table->string('image')->nullable();
            $table->string('birth')->nullable();
            $table->string('father_name')->nullable();
            $table->text('address')->nullable();
            $table->string('email')->unique()->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
