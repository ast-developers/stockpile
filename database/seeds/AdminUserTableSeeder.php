<?php

use Illuminate\Database\Seeder;

class AdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$user = array(
                'email'=>'admin@techvill.net',
                'password'=>'$2y$10$NFl9z/cbBkX8q41bIkZbm.32OT/Ogp2fYKIZXifzgm2M6n1oG5/0C',
                'real_name'=>'Admin',
                'role_id'=>1,
                'inactive'=>0,
                'remember_token'=>'0zY0oKxRmm5cdgLdUHzNZ4tRwzaZl5rqqkXA2EYx2tjcs11D9tbDajIwfht2'
        );
        DB::table('users')->truncate();
		DB::table('users')->insert($user);
    }
}