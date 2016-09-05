<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Node,
App\Models\NodePermission,
App\Models\User;

use DB;

use App\Http\Requests\Node\StoreRequest,
App\Http\Requests\Node\UpdateRequest;

class NodeController extends Controller
{
    public function index(Request $request)
    {
        $nodes = Node::with('project', 'user')
        ->own()->get();
        return view('node.index', compact('nodes'));
    }

    public function edit($id)
    {
        $node = Node::findOrFail($id);
        return view('node.edit', compact('node'));
    }

    public function update($id, UpdateRequest $request)
    {
        try {
            $node = Node::findOrFail($id);
            $node->name = trim($request->name);
            $node->save();
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
                'name'  => $request->name,
                'key'   => md5(uniqid()),
                'owner' => \Auth::user()->id
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
            return back()->withErrors("Could not delete node: ".$e->getMessage());
        }
        return back()->with('success', ["Node {$node->name} has been removed."]);
    }
}
