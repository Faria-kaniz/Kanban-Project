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

    }

    public function update(Request $request)
    {

    }
}
