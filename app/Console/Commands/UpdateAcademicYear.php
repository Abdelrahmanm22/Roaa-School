<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class UpdateAcademicYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'academic-year:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increases the academic year by one every August';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Get the current academic year setting
        $academicYear = DB::table('settings')->where('key', 'academic_year')->first();

        if ($academicYear) {
            // Increment the academic year by 1
            $newYear = (int) $academicYear->value + 1;

            // Update the value in the database
            DB::table('settings')->where('key', 'academic_year')->update(['value' => $newYear]);
            DB::table('settings')->where('key','last_student_code')->update(['value' => 0]);

            $this->info('Academic year updated successfully to ' . $newYear);
        } else {
            $this->error('Academic year setting not found.');
        }
    }
}
