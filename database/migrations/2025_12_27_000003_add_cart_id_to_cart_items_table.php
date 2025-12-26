<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('cart_id')->nullable()->after('id');
        });

        $now = now();
        $userIds = DB::table('cart_items')->select('user_id')->distinct()->pluck('user_id');

        foreach ($userIds as $userId) {
            $cartId = DB::table('carts')->where('user_id', $userId)->value('id');

            if (! $cartId) {
                $cartId = DB::table('carts')->insertGetId([
                    'user_id' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            DB::table('cart_items')
                ->where('user_id', $userId)
                ->update(['cart_id' => $cartId]);
        }

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'product_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign('cart_id')->references('id')->on('carts')->cascadeOnDelete();
            $table->unique(['cart_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique(['cart_id', 'product_id']);
            $table->dropForeign(['cart_id']);
            $table->foreignId('user_id')->nullable()->after('id');
        });

        $now = now();
        $cartRows = DB::table('cart_items')->select('cart_id')->distinct()->pluck('cart_id');

        foreach ($cartRows as $cartId) {
            $userId = DB::table('carts')->where('id', $cartId)->value('user_id');

            DB::table('cart_items')
                ->where('cart_id', $cartId)
                ->update(['user_id' => $userId]);
        }

        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['user_id', 'product_id']);
            $table->dropColumn('cart_id');
        });

        Schema::dropIfExists('carts');
    }
};
