<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TagsAPITest extends TestCase {

    use DatabaseMigrations;

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testTagsUseJson(){
        $this->withoutMiddleware();

        $this->get('/tag')->seeJson()->seeStatusCode(200);
    }

    /**
     * Test tags in database are listed by API
     *
     * @return void
     */
    public function testTagsInDatabaseAreListedByAPI(){
        $this->withoutMiddleware();

        $this->createFakeTags();
        $this->get('/tag')
            ->seeJsonStructure([
                '*' => [
                    'name',
                    'tran'
                ]
            ])->seeStatusCode(200);
    }

    /**
     * Test tags in database is shown by API
     *
     * @return void
     */
    public function testTagsInDatabaseAreShownByAPI(){
        $this->withoutMiddleware();

        $tag = $this->createFakeTag();
        $this->get('/tag/' . $tag->id)
            ->seeJsonContains(['name' => $tag->name, 'tran' => $tag->tran])
            ->seeStatusCode(200);
    }

    /**
     * Test tags can be posted and saved to database
     *
     * @return void
     */
    public function testTagsCanBePostedAndSavedIntoDatabase() {
        $this->withoutMiddleware();

        $data = ['name' => 'Foobar', 'tran' => false];
        $this->post('/tag',$data)->seeInDatabase('tags',$data);
        $this->get('/tag')->seeJsonContains($data)->seeStatusCode(200);
    }

    /**
     * Test tags can be update and see changes on database
     *
     * @return void
     */
    public function testTagsCanBeUpdatedAndSeeChangesInDatabase() {
        $this->withoutMiddleware();

        $tag = $this->createFakeTag();
        $data = [ 'name' => 'Learn Laravel now!', 'tran' => true];
        $this->put('/tag/' . $tag->id, $data)->seeInDatabase('tags',$data);
        $this->get('/tag')->seeJsonContains($data)->seeStatusCode(200);
    }

    /**
     * Test tags can be deleted and not see on database
     *
     * @return void
     */
    public function testTagsCanBeDeletedAndNotSeenOnDatabase(){
        $this->withoutMiddleware();

        $tag = $this->createFakeTag();
        $data = [ 'name' => $tag->name, 'tran' => $tag->tran];
        $this->delete('/tag/' . $tag->id)->notSeeInDatabase('tags',$data);
        $this->get('/tag')->dontSeeJson($data)->seeStatusCode(200);
    }

    /**
     * Test tags when not auth redirect to auth/login and see message
     *
     * @return void
     */
    public function testTagsReturnLoginPageWhenNotAuth(){
        $this->visit('/tag')
            ->seePageIs('/auth/login')
            ->see('No tens acces a la API');
    }

    /**
     * Create fake tag
     *
     * @return \App\Tag
     */
    private function createFakeTag() {
        $faker = Faker\Factory::create();

        $tag = new \App\Tag();
        $tag->name = $faker->word;
        $tag->tran = $faker->boolean;
        $tag->save();

        return $tag;
    }

    /**
     * Create fake tags
     *
     * @param int $count
     * @return \App\Tag
     */
    private function createFakeTags($count = 10) {
        foreach (range(0,$count) as $number) {
            $this->createFakeTag();
        }
    }
}