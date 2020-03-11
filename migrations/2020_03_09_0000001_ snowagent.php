<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class Snowagent extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->create('snowagent', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial_number')->unique();
            $table->string('version')->nullable();
            $table->string('version_long')->nullable();
            $table->string('build')->nullable();
            $table->string('rev')->nullable();
            $table->string('sitename')->nullable();
            $table->string('configname')->nullable();
            $table->string('server_address')->nullable();
            $table->string('client_cert')->nullable();
            $table->string('client_cert_password')->nullable();
            $table->text('drop_location')->nullable();
            $table->boolean('software_scan_running_processes')->nullable();
            $table->boolean('software_scan_jar')->nullable();
            $table->boolean('http_ssl_verify')->nullable();
           
            $table->index('version');
            $table->index('build');
            $table->index('sitename');
            $table->index('configname');
            $table->index('server_address');
            $table->index('software_scan_running_processes');
            $table->index('http_ssl_verify');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->dropIfExists('snowagent');
    }
}
