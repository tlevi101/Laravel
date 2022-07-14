<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'question'=>$this->faker->text(),
            'answer_type'=> $this->faker->randomElements(['TEXTAREA','ONE_CHOICE','MULTIPLE_CHOICE'])[0],
            'required'=>$this->faker->boolean(),
            //
        ];
    }
}
