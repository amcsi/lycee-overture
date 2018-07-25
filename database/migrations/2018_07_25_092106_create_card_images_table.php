<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'card_images',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('card_id')->unique();
                $table->string('md5');

                // The first timestamp column defined for MySQL 5.7 implicitly has default/onUpdate current_timestamp.
                $table->timestamp('updated_at');
                $table->timestamp('created_at')->useCurrent();

                $table->timestamp('last_uploaded')->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_images');
    }
}
