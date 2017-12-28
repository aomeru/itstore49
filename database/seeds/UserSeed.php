<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('users')->insert([
			'role_id' => 1525,

			'email' => 'aomeru@salvicpetroleum.com',
			'password' => bcrypt('spadmin3595?'),

			'firstname' => 'Akpoteheri',
			'lastname' => 'Omeru',
			'gender' => 'male',

			'status' => 'active',

			'staff_id' => 'G17090167',

			'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
			'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
		]);

    }
}
