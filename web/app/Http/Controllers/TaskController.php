<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Requests\Task\StoreRequest;

use App\Models\Task,
App\Models\TaskPermission,
App\Models\Project,
App\Models\Node;

use DB;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::own()->get();
        $projects = Project::own()->get();
        $nodes = Node::own()->get();
        return view('task.index', compact('nodes', 'projects', 'tasks'));
    }

    public function node($id)
    {
        $tasks = Task::with('user', 'node')
        ->node($id)->own()->get();
        $node = Node::find($id);
        return view('task.index', compact('node', 'project', 'tasks'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $new = $request->all();
            $new['key'] = md5(uniqid());
            $new['owner'] = \Auth::user()->id;
            $task = Task::create($new);
            TaskPermission::create([
                    'task_id' => $task->id,
                    'user_id' => \Auth::user()->id
                ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors("Add task {$request->name} failed");
        }
        return back();
    }
}
