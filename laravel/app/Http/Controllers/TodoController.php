<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Enums\TodoStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class TodoController extends Controller {

    public function index() {
        return Todo::orderBy('created_at', 'desc')->get();
    }

    public function create(Request $request) {
        $validator = $request->validate([
            'title' => 'required|max:255',
            'status' => [new Enum(TodoStatus::class)],
        ]);

        if(count(Todo::where('title', $request->title)->get()) > 0){
            return response()->json([""]);
        };

        $todo = new Todo;
        $todo->title = $request->title;
        //Might need to change request->status to TodoStatus enum
        $todo->status = $request->status;
        $todo->save();

        return response()->json(["message" => "Todo created!", "data" => $todo], 200);
    }

    public function read(Request $request) {
        $todo = $this->findTodo($request->id);
        return $todo;
    }

    public function update(Request $request) {
        $validator = $request->validate([
            'title' => 'required|max:255',
            'status' => [new Enum(TodoStatus::class)],
        ]);

        $todo = $this->findTodo($request->id);
        $todo->title = $request->title;
        $todo->status = $request->status;
        $todo->save();

        return response()->json(["message" => "Todo updated!", "data" => $todo], 200);
    }

    public function delete(Request $request) {

        $id = $request->id;
        $this->findTodo($id);
        Todo::destroy($id);

        return response()->json(["message" => "Todo deleted"], 200);

    }

    private function findTodo($id) {
        $todo = Todo::where('id', $id)->first();
        if($todo == null){
            return response()->json(["message" => "No reference found for this todo"], 404);
        }
        return $todo;
    }

}
