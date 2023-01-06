<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensor_units', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('name', 32);
            $table->string('type');
            $table->string('description', 256)->nullable();
            $table->string('auth_key')->nullable();
            $table->decimal('long', 11, 7);
            $table->decimal('lat', 10, 7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sensor_units');
    }
};
