<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Node,
App\Models\NodePermission,
App\Models\User;
use DB;

class NodeController extends Controller
{
    public function index(Request $request)
    {
        $nodes = NodePermission::own();
        return view('node.index', compact('nodes'));
    }

    public function register(Request $request)
    {
        $user = User::whereEmail($request->email)->first();
        if (!$user) {
            return 'login failed, sign up an account? '.url('/register');
        }
        if (!\Hash::check($request->password, $user->password)) {
            return 'Email or password incorrect.';
        }
        $node = Node::whereName($request->name)->first();
        if (!$node) {
            $newNode = [
                'name'  => $request->name,
                'key'   => md5(uniqid()),
                'owner' => \Auth::user()->id
            ];
            try {
                DB::beginTransaction();
                $node = Node::create($newNode);
                $nodePermission = [
                    'node_id' => $node->id,
                    'user_id' => \Auth::user()->id,
                    'role' => 'manager'
                ];
                NodePermission::create($nodePermission);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json($e->getMessage(), 422);
            }

        }
        return response()->json($node->key);
    }
}
