<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i <10 ; $i++) {
            User::factory()->create([
                'email' => 'user'.($i+1).'@szerveroldali.hu',
            ]);
        }
        //
    }
}
