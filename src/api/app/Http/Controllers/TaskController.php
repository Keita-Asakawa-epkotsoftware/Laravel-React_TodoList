<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        // return Task::all();  # 追加
        return Task::orderByDesc("id")->get();  # 追加(idカラムを降順でソート)
    }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()  # 削除
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->all());  # 追加
        return $task ? response()->json($task, 201) : response()->json([], 500);  # 追加
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Task $task)  # 削除
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
