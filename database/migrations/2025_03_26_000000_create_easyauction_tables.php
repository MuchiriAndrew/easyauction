<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Create users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('password');
            $table->boolean('email_verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('status')->nullable();
            $table->bigInteger('confirmation_id')->nullable();
            $table->string('role')->nullable();
            $table->timestamps();
        });

        // Create cars table
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('make');
            $table->string('model');
            $table->year('year');
            $table->enum('style', [
                'saloon', 'suv', 'hatchback', 'coupe', 'convertible', 
                'estate', 'mpv', 'pickup', 'van', 'minibus', 
                'campervan', 'limousine', 'other'
            ]);
            $table->json('features')->nullable();
            $table->enum('transmission', ['automatic', 'manual']);
            $table->enum('fuel_type', ['petrol', 'diesel', 'electric', 'hybrid']);
            $table->string('color');
            $table->integer('mileage');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('vin');
            $table->longText('description');
            $table->string('photo_path');
            $table->string('status');
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('set null');
        });

        // Create auctions table
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('car_id')->nullable();
            $table->decimal('start_price', 10, 2)->nullable();
            $table->decimal('reserve_price', 10, 2)->nullable();
            $table->json('car_ids')->nullable();
            $table->enum('status', ['open', 'closed', 'pending']);
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->timestamps();

            $table->foreign('car_id')->references('id')->on('cars')->onDelete('set null');
        });

        // Create bids table
        Schema::create('bids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auction_id');
            $table->unsignedBigInteger('car_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 10, 2);
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('auction_id')->references('id')->on('auctions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('set null');
        });

        // Create transactions table
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auction_id')->nullable();
            $table->unsignedBigInteger('bid_id')->nullable();
            $table->unsignedBigInteger('car_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('payment_status')->nullable();
            $table->json('transaction_details');
            $table->string('merchant_request_id')->nullable();
            $table->string('dr_cr')->nullable();
            $table->json('callback')->nullable();
            $table->date('transaction_date');
            $table->timestamps();

            $table->foreign('auction_id')->references('id')->on('auctions')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('bid_id')->references('id')->on('bids')->onDelete('set null');
            $table->foreign('car_id')->references('id')->on('cars')->onDelete('set null');
        });

        // Create failed_jobs table
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // Create features table
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('category')->nullable();
        });

        // Create migrations table
        Schema::create('migrations', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->string('migration');
            $table->integer('batch');
        });

        // Create permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        // Create roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        // Create model_has_permissions table
        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        // Create model_has_roles table
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        // Create password_reset_tokens table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Create personal_access_tokens table
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('tokenable_type');
            $table->unsignedBigInteger('tokenable_id');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['tokenable_type', 'tokenable_id']);
        });

        // Create role_has_permissions table
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->primary(['permission_id', 'role_id']);
        });
    }

    public function down()
    {
        // Drop tables in reverse order to avoid foreign key constraints
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('bids');
        Schema::dropIfExists('auctions');
        Schema::dropIfExists('cars');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('features');
        Schema::dropIfExists('migrations');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');
    }
};