<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Choice;
use App\Models\Question;
class ChoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = Question::all();
        foreach ($questions as $question) {
            if($question->answer_type!='TEXTAREA'){
                Choice::factory()
                ->for($question)
                ->create(['question_id' => $question->id]);
                $count = rand(1,6);
                for ($i=0; $i < $count; $i++) {
                    Choice::factory()
                    ->for($question)
                    ->create(['question_id' => $question->id]);
                }
            }

        }
        //
    }
}
