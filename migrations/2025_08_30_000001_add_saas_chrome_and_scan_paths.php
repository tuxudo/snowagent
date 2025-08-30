<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

class AddSaasChromeAndScanPaths extends Migration
{
    public function up()
    {
        $capsule = new Capsule();
        $capsule::schema()->table('snowagent', function (Blueprint $table) {
            $table->boolean('saas_chrome_enabled')->nullable()->after('software_scan_jar');
            $table->text('scan_include_paths')->nullable()->after('saas_chrome_enabled');
            $table->text('scan_exclude_paths')->nullable()->after('scan_include_paths');
        });
    }
    
    public function down()
    {
        $capsule = new Capsule();
        $capsule::schema()->table('snowagent', function (Blueprint $table) {
            $table->dropColumn(['saas_chrome_enabled', 'scan_include_paths', 'scan_exclude_paths']);
        });
    }
}
