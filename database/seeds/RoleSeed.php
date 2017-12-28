<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$values = array(
			'Developer',
			'Administrator',
            'Manager',
			'Editor',
			'Staff',
		);

		foreach($values as $role){
			DB::table('roles')->insert([
				'title' => $role,
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
			]);
		}
    }
}
