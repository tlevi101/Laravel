<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Answer;
use App\Models\User;
use App\Models\Choice;
use App\Models\Form;
use App\Models\Question;
use Faker\Factory as FakerFactory;
class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $forms= Form::all();
        foreach ($forms as $form){
            $user=  $users[rand(0, count($users)-1)];
            if(rand(0, 3)!==0){
                for ($i=0; $i <rand(0, 10); $i++) {
                    foreach ($form->questions as $question){
                        if($question["answer_type"]!=='TEXTAREA'){
                            $j=0;
                            $fix=rand(0, count($question->choices));
                            if($question->answer_type!='ONE_CHOICE'){
                                foreach ($question->choices as $key => $choice) {
                                    if($question["required"]===1 && $j===$fix || rand(0,3)>2){
                                        Answer::factory()
                                        ->create([
                                            'user_id' => $form['auth_required']===0? null:$user->id,
                                            'choice_id'=> $choice->id,
                                            'question_id'=>$question->id,
                                            'answer' => null,
                                        ]);
                                        $j++;
                                    }
                                }
                            }
                            else{
                                foreach ($question->choices as $key => $choice) {
                                    if($question["required"]===1 && $j===$fix){
                                        Answer::factory()
                                        ->create([
                                            'user_id' => $form['auth_required']===0? null:$user->id,
                                            'choice_id'=> $choice->id,
                                            'question_id'=>$question->id,
                                            'answer' => null,
                                        ]);
                                        $j++;
                                    }
                                }
                            }

                        }
                        else{
                            if($question["required"]===1 || rand(0,3)>1){
                                $faker = FakerFactory::create();
                                Answer::factory()
                                    ->create([
                                        'user_id' => $form['auth_required']===0 ? null:$user->id,
                                        'choice_id'=>null,
                                        'question_id'=>$question->id,
                                        'answer' => $faker->sentence(),
                                    ]);
                            }
                        }
                    }
                }
            }

        }
        // for ($i=0; $i < 300; $i++) {
        //     $user=  $users[rand(0, count($users)-1)];
        //     $question=$questions[rand(0, count($questions)-1)];
        //     $choices=(DB::table('choices')->where('question_id', '=',$question->id))->get();
        //     $choice=null;
        //     if(count($choices)!=0){
        //         if(count($choices)>1){
        //             $choice=$choices[rand(0,count($choices)-1)]->id;
        //         }
        //         else{
        //             $choice=$choices[0]->id;
        //         }
        //     }
        //     $faker = FakerFactory::create();

        //     Answer::factory()
        //         ->for($user)
        //         ->create([
        //             'user_id' => $user->id,
        //             'choice_id'=>$choice,
        //             // 'choice_id'=>count($choices)!=0 ? $choices[0]->id:null,
        //             // 'choice_id'=>null,
        //             'question_id'=>$question->id,
        //             'answer' => $question->answer_type=='TEXTAREA' ? $faker->sentence() : null,
        //         ]);
        // }
        //
    }
}
