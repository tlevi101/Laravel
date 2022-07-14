<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Form;
class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $forms = Form::all();
        foreach ($forms as $key => $form) {
            for ($i=0; $i < rand(1, 5); $i++) {
                Question::factory()
                ->for($form)
                ->create(['form_id' => $form->id]);
            }
        }
        // for ($i=0; $i < 10; $i++) {
        //     $form=  $forms[rand(0, count($forms)-1)];
        //     Question::factory()
        //         ->for($form)
        //         ->create(['form_id' => $form->id]);
        // }
        //
    }
}
