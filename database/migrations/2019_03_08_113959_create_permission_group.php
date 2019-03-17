<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        Schema::create('permission_group', function (Blueprint $table) {

            // Schema
            $table->increments('id_permission_group');
            $table->string('group')->unique();
            $table->unsignedInteger('id_parent')->nullable();
            $table->timestamps();
            
            // index
            $table->index('id_parent');
        });

        Schema::create('permission_group_has_permissions', function (Blueprint $table) use ($tableNames) {

            // Schema
            $table->increments('id');
            $table->unsignedInteger('id_permission_group');
            $table->unsignedInteger('permission_id')->unique();
            
            // Index
            // Foreign
            $table->foreign('id_permission_group')
                ->references('id_permission_group')
                ->on('permission_group')
                ->onDelete('cascade');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

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
        Schema::dropIfExists('permission_group_has_permission');
        Schema::dropIfExists('permission_group');
        Schema::enableForeignKeyConstraints();
    }
}
