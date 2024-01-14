<?php

namespace Database\Factories;
use App\Models\KnowledgeBase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KnowledgeBase>
 */
class KnowledgeBaseFactory extends Factory
{

    protected $model = KnowledgeBase::class;

    protected function withFaker()
    {
        return \Faker\Factory::create('en');
    }
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $topic  = $this->faker->topic;
        $desc = $this->faker->description;
        $text = $this->faker->text;
        return [
            'topic' => $topic,
            'description' => $desc,
            'text' => $text,
        ];
    }
}
