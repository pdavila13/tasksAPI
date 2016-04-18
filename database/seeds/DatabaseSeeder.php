<?php

use App\Tag;
use App\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        Model::unguard();

        // $this->call(UsersTableSeeder::class);

        $faker = Faker\Factory::create();
        $this->seedTasks($faker);
        $this->seedTags($faker);

        Model::reguard();
    }


    public function seedTasks($faker) {
        foreach(range(0,100) as $number) {
            $task = new Task();

            $task->name = $faker->sentence;
            $task->done = $faker->boolean;
            $task->priority = $faker->randomDigit;
            $task->save();
        }
    }

    public function seedTags($faker) {
        foreach(range(0,100) as $number) {
            $tag = new Tag();

            $tag->name = $faker->word;
            $tag->tran = $faker->boolean;
            $tag->save();
        }
    }
}
