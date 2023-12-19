<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createMultiple = [
            ['name'=>'Quiz','status'=>'0'],
            ['name'=>'Structured','status'=>'0',]
        ];
        
        //User::insert($createMultiple); // Eloquent
        \DB::table('question_types')->insert($createMultiple); // Query Builder
    }
}
