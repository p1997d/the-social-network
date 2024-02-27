<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->string('name');
        });

        $areas = json_decode(File::get(public_path("json/areas.json")), true);
        $areaslist = [];
        foreach ($areas as $area) {
            $areaslist[] = [
                "id" => $area['id'],
                "parent_id" => $area['parent_id'],
                "name" => $area['name'],
            ];
            foreach ($area['areas'] as $area) {
                $areaslist[] = [
                    "id" => $area['id'],
                    "parent_id" => $area['parent_id'],
                    "name" => $area['name'],
                ];
                foreach ($area['areas'] as $area) {
                    $areaslist[] = [
                        "id" => $area['id'],
                        "parent_id" => $area['parent_id'],
                        "name" => $area['name'],
                    ];
                }
            }
        }
        DB::table('locations')->insert($areaslist);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
