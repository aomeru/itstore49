<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DepartmentSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values = array(
            'Strategy & Planning',
            'Corporate Affairs',
            'HSSEQ',
            'Corporate Services',
            'Commercial',
            'Technical',
            'Operations',
            'Joint Venture',
            'Legal',
            'Finance',
        );

        foreach($values as $dept){
            DB::table('departments')->insert([
                'title' => $dept,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
