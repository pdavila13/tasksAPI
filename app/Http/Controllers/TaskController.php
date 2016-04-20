<?php

namespace App\Http\Controllers;

use App\Task;
use App\Transformers\TaskTransformer;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Response;

class TaskController extends Controller {

    protected $taskTransformer;

    /**
     * TaskController constructor.
     * @param $taskTransformer
     */
    public function __construct(TaskTransformer $taskTransformer) {
        $this->taskTransformer = $taskTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $tasks = Task::all();
        return Response::json([
            'data' => $this->taskTransformer->transformCollection($tasks)
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $task = new Task();

        $this->saveTask($request, $task);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){

        $task = Task::find($id);

        if(!$task) {
            return Response::json([
                'error' => [
                    'message' => 'Task does not exist',
                    'code' => 195
                ]
            ],400);
        }

        return Response::json([
            'data' => $this->taskTransformer->transform($task)
        ], 400);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $task = Task::find($id);

        if(!$task){
            return Response::json([
                'error' => [
                    'message' => 'Task does not exist',
                    'code' => 195
                ]
            ], 404);
        }

        $this->saveTask($request, $task);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        Task::destroy($id);
    }

    /**
     * @param Request $request
     * @param $task
     */
    protected function saveTask(Request $request, $task) {
        $task->name = $request->name;
        $task->priority = $request->priority;
        $task->done = $request->done;
        $task->save();
    }
}
