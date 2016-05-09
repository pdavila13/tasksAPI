<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TasksAPITest extends TestCase {

    use DatabaseMigrations;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testTasksUseJson(){
        $user = $this->createUser();

        $this->get('/task?api_token=' . $user
                ->api_token)->seeJson()->seeStatusCode(200);
    }

    /**
     * Test tasks in database are listed by API
     *
     * @return void
     */
    public function testTasksInDatabaseAreListedByAPI(){
        $user = $this->createUser();

        $this->createFakeTasks();
        $this->actingAs($user)
            ->get('/task')
            ->seeJsonStructure([
                '*' => [
                    'name',
                    'done'
                ]
            ])->seeStatusCode(200);
    }

    /**
     * Test tasks in database is shown by API
     *
     * @return void
     */
    public function testTasksInDatabaseAreShownByAPI(){
        $user = $this->createUser();

        $task = $this->createFakeTask();
        $this->actingAs($user)->get('/task/' . $task->id)
            ->seeJsonContains(['name' => $task->name, 'done' => $task->done])
            ->seeStatusCode(200);
    }

    /**
     * Test tasks can be posted and saved to database
     *
     * @return void
     */
    public function testTasksCanBePostedAndSavedIntoDatabase() {
        $user = $this->createUser();

        $data = ['name' => 'Foobar', 'done' => false];

        $this->actingAs($user)->post('/task',$data)->seeInDatabase('tasks',$data);
        $this->actingAs($user)->get('/task')->seeJsonContains($data)->seeStatusCode(200);
    }

    /**
     * Test tasks can be update and see changes on database
     *
     * @return void
     */
    public function testTasksCanBeUpdatedAndSeeChangesInDatabase() {
        $user = $this->createUser();

        $task = $this->createFakeTask();

        $data = ['name' => 'Learn Laravel now!', 'done' => true];

        $this->actingAs($user)->put('/task/' . $task->id, $data)->seeInDatabase('tasks',$data);
        $this->actingAs($user)->get('/task')->seeJsonContains($data)->seeStatusCode(200);
    }

    /**
     * Test tasks can be deleted and not see on database
     *
     * @return void
     */
    public function testTasksCanBeDeletedAndNotSeenOnDatabase(){
        $user = $this->createUser();

        $task = $this->createFakeTask();

        $data = ['name' => $task->name, 'done' => $task->done];

        $this->actingAs($user)->delete('/task/' . $task->id)->notSeeInDatabase('tasks',$data);
        $this->actingAs($user)->get('/task')->dontSeeJson($data)->seeStatusCode(200);
    }

    /**
     * Test tasks when not auth redirect to auth/login and see message
     *
     * @return void
     */
    public function testTaskReturnLoginPageWhenNotAuth(){
        $this->visit('/task')
            ->seePageIs('/auth/login')
            ->see('No tens acces a la API');
    }

    /**
     * Create fake task
     *
     * @return \App\Task
     */
    private function createFakeTask() {
        $faker = Faker\Factory::create();

        $task = new \App\Task();
        $task->name = $faker->sentence;
        $task->done = $faker->boolean;
//        $task->priority = $faker->randomDigit;
        $task->save();

        return $task;
    }

    /**
     * Create fake tasks
     *
     * @param int $count
     * @return \App\Task
     */
    private function createFakeTasks($count = 10) {
        foreach (range(0,$count) as $number) {
            $this->createFakeTask();
        }
    }

    /**
     * Create user tasks
     *
     * @return mixed
     */
    public function createUser() {
        $user = factory(App\User::class)->create();
        return $user;
    }
}