<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        $content = array_map(
            function (){
                return "<p>" . $this->faker->paragraph(14) . '</p>';
            } 
        ,range(1,5));

        $offset = rand(1, count($content));
        $blockQoute = '<blockquote>"' .  $this->faker->paragraph(2) . '"</blockquote>';
        array_splice($content, $offset, 0, $blockQoute);
        return [
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'content' => implode(' ', $content),
            'status' => $this->faker->randomElement(['published', 'draft']),
            'is_premium' => $this->faker->boolean(),
            'user_id' => UserFactory::new()->create()->id,
        ];
    }
}
