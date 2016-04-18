<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * Class TagController
 * @package App\Http\Controllers
 */
class TagController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $tag = Tag::all();
        return Reponse::json([
            'data' => $tag->toArray()
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
        $tag = new Tag();

        $this->saveTag($request, $tag);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $tag = Tag::find($id);

        if(!$tag) {
            return Response::json([
                'error' => [
                    'message' => 'Tag does not exist',
                    'code' => 195
                ]
            ],400);
        }

        return Response::json([
            'data' => $tag->toArray()
        ],400);
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

        $tag = Tag::find($id);

        if(!$tag){
            return Response::json([
                'error' => [
                    'message' => 'Tag does not exist',
                    'code' => 195
                ]
            ], 404);
        }

        $this->saveTag($request, $tag);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        Tag::destroy($id);
    }

    /**
     * @param Request $request
     * @param $tag
     */
    protected function saveTag(Request $request, $tag) {
        $tag->name = $request->name;
        $tag->save();
    }
}
