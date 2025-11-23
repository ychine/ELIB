<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Seeder;

class UpdateExistingStudentsSeeder extends Seeder
{
    public function run(): void
    {
        // Create courses if they don't exist
        $bsitCourse = Course::firstOrCreate(
            ['code' => 'BSIT'],
            ['name' => 'Bachelor of Science in Information Technology']
        );

        $bsaCourse = Course::firstOrCreate(
            ['code' => 'BSA'],
            ['name' => 'Bachelor of Science in Agriculture']
        );

        $bsleaCourse = Course::firstOrCreate(
            ['code' => 'BSLEA'],
            ['name' => 'Bachelor of Science in Law Enforcement Administration']
        );

        // Get all students without course_id or student_number
        $students = Student::whereNull('course_id')
            ->orWhereNull('student_number')
            ->get();

        $counter = 1;
        $courses = [$bsitCourse, $bsaCourse, $bsleaCourse];

        foreach ($students as $student) {
            $updateData = [];

            // Set course_id randomly if not set
            if (! $student->course_id) {
                $updateData['course_id'] = $courses[array_rand($courses)]->id;
            }

            // Set student_number to 23-xxx format if not set
            if (! $student->student_number) {
                $updateData['student_number'] = '23-'.str_pad($counter, 3, '0', STR_PAD_LEFT);
                $counter++;
            }

            if (! empty($updateData)) {
                $student->update($updateData);
            }
        }

        $this->command->info('Updated '.$students->count().' students with courses (BSIT, BSA, BSLEA) and student numbers.');
    }
}
