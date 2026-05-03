<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('umkms', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        foreach (DB::table('umkms')->get() as $row) {
            $base = Str::slug($row->name) ?: 'umkm-'.$row->id;
            $slug = $base;
            $i = 0;
            while (DB::table('umkms')->where('slug', $slug)->exists()) {
                $i++;
                $slug = $base.'-'.$i;
            }
            DB::table('umkms')->where('id', $row->id)->update(['slug' => $slug]);
        }

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('umkm_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::table('umkms', function (Blueprint $table) {
            $table->dropColumn('slug');
        });

        Schema::dropIfExists('categories');
    }
};
