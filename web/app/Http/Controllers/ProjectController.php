<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Requests\Project\StoreRequest;

use App\Models\Project,
App\Models\ProjectPermission,
App\Models\Node,
App\Models\NodePermission;

use DB;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::own()->get();
        $nodes = Node::own()->get();
        return view('project.index', compact('nodes', 'projects'));
    }

    public function node($id)
    {
        if (!NodePermission::permission($id)->exists()) {
            return redirect('/nodes')
            ->withErrors("You have no permission to access this node, these are nodes you can.");
        }
        $node = Node::find($id);
        $projects = Project::with('user', 'node')
        ->node($id)->own()->get();
        return view('project.index', compact('node', 'projects'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $new = $request->all();
            $new['key'] = md5(uniqid());
            $new['owner'] = \Auth::user()->id;
            $project = Project::create($new);
            ProjectPermission::create([
                    'project_id' => $project->id,
                    'user_id' => \Auth::user()->id
                ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors("Add project {$request->name} failed");
        }
        return back();
    }
}
