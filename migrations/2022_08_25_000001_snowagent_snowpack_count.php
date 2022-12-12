<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class SnowagentSnowpackCount extends Migration
{
    private $tableName = 'snowagent';

    public function up()
    {
        $capsule = new Capsule();

        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->integer('snowpack_count')->nullable();
        });
    }

    public function down()
    {
        $capsule = new Capsule();

        $capsule::schema()->table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('snowpack_count');
        });
    }
}
