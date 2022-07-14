<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Form;
use App\Models\User;
class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        for ($i=0; $i < 100; $i++) {
            $user=  $users[rand(0, count($users)-1)];
            Form::factory()->for($user)
                ->create();
        }
        //
    }
}
