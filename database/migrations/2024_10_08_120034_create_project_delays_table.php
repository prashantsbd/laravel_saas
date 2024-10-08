<?php

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_delays', function (Blueprint $table) {
            $table->id();
            $table->date('delay_from')->nullable(false);
            $table->integer('delay_interval')->nullable(false);
            $table->foreignIdFor(Project::class,'project_id')->index('projects_project_id_foreign')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_delays');
    }
};
