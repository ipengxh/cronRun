<?php

namespace App\Http\Controllers;

use App\Http\Requests\Node\StoreRequest;
use App\Http\Requests\Node\UpdateRequest;
use App\Models\Node;
use App\Models\NodePermission;
use App\Models\Project;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class NodeController extends Controller
{
    public function index(Request $request)
    {
        $nodes = Node::with('project', 'user')
            ->own()->get();
        return view('node.index', compact('nodes'));
    }

    public function create()
    {
        return view('node.create');
    }

    public function edit($id)
    {
        $node = Node::whereOwner(\Auth::user()->id)->findOrFail($id);
        $myNodes = Node::own()->where('id', '!=', $id)->get();
        $users = User::all();
        $managers = NodePermission::with('user')->whereNodeId($id)->get();
        return view('node.edit', compact('node', 'users', 'managers'));
    }

    public function show($id)
    {
        $node = Node::with('user')->whereOwner(\Auth::user()->id)->findOrFail($id);
        $managers = NodePermission::with('user')->whereNodeId($id)->get();
        $projects = Project::whereNodeId($id)->get();
        return view('node.show', compact('node', 'managers', 'projects'));
    }

    public function update($id, UpdateRequest $request)
    {
        try {
            Node::where(['id' => $id])->update(['name' => $request->name]);exit;
            //Node::updateOrCreate(['id' => $id], ['name' => $request->name]);exit;
            $node = Node::findOrFail($id);
            $node->update(['name' => $request->name]);
            //$node->name = trim($request->name);
            //$node->save();
        } catch (\Exception $e) {
            return redirect('/nodes')->withErrors("Update node failed: ");
        }
        return redirect('/nodes');
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $new = [
                'name' => $request->name,
                'key' => md5(uniqid()),
                'owner' => \Auth::user()->id,
            ];
            $node = Node::create($new);
            $nodePermission = [
                'node_id' => $node->id,
                'user_id' => \Auth::user()->id,
            ];
            NodePermission::create($nodePermission);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors("Add node {$request->name} failed.");
        }
        return redirect(route('node:edit', $node->id))->with('success', ["Node {$request->name} added, fill below infornmation please."]);
    }

    public function destroy($id)
    {
        try {
            $node = Node::find($id);
            Node::destroy($id);
        } catch (\Exception $e) {
            return back()->withErrors("Could not delete node: " . $e->getMessage());
        }
        return redirect('/nodes')->with('success', ["Node {$node->name} has been removed."]);
    }
}
