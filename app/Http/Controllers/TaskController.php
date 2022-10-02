<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        try {
            $allTasks = Task::all()->toArray();
            $allDataArr = [];
            foreach ($allTasks as $task) {
                if ($task['status_id'] == 1) {
                    $allDataArr['todo'][] = $task;
                }
                if ($task['status_id'] == 2) {
                    $allDataArr['inProgress'][] = $task;
                }
                if ($task['status_id'] == 3) {
                    $allDataArr['done'][] = $task;
                }
            }

            return response()->json(['responseCode' => 1, 'status' => 'success', 'message' => 'Data Found', 'data' => $allDataArr]);
        }
        catch (\Exception $e) {
            return response()->json(['responseCode' => -1, 'status' => 'Failed', 'message' => 'Data Not Found', 'data' => []]);
        }
    }

    public function store(Request $request)
    {
        try {
            Task::create([
                'task' => $request['task'],
                'status_id' => 1,
            ]);

            return response()->json(['responseCode' => 1, 'status' => 'success', 'message' => 'Data Saved']);
        }
        catch(\Exception $e){
            return response()->json(['responseCode' => -1, 'status' => 'Failed', 'message' => 'Data Not Saved']);
        }
    }

    public function update(Request $request)
    {
        $todo_list = isset($request['todos_list']) ? $request['todos_list'] : [];
        $in_progress_list = isset($request['in_progress_list']) ? $request['in_progress_list'] : [];
        $done_list = isset($request['done_list']) ? $request['done_list'] : [];

        try {
            if (count($todo_list) > 0){
                Task::whereIn('id',$todo_list)->update(['status_id' => 1]);
            }
            if (count($in_progress_list) > 0){
                Task::whereIn('id',$in_progress_list)->update(['status_id' => 2]);
            }
            if (count($done_list) > 0){
                Task::whereIn('id',$done_list)->update(['status_id' => 3]);
            }

            return response()->json(['responseCode' => 1, 'status' => 'success', 'message' => 'Data Found']);

        }catch(\Exception $e){
            return response()->json(['responseCode' => -1, 'status' => 'Failed', 'message' => 'Data Not Found']);
        }


    }
}
