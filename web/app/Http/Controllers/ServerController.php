<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Server,
App\Models\User;

class ServerController extends Controller
{
    public function index()
    {
        return view('server.index');
    }

    public function register(Request $request)
    {
        $user = User::whereEmail($request->email)->first();
        if (!$user) {
            return 'login failed, sign up an account? '.url('/register');
        }
        if (!\Hash::check())
        $server = Server::whereName($request->name)->first();
        if (!$server) {
            $newServer = [
                'name'  => $request->name,
                'key'   => md5(uniqid()),
                'owner' => \Auth::user()->id
            ];
            $server = Server::create($newServer);
        }
        return $server->key;
    }

    private function config(Server $server)
    {
        return json_encode($server->toArray());
    }
}
