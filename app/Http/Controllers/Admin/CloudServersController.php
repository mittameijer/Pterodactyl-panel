<?php

namespace Pterodactyl\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Prologue\Alerts\AlertsMessageBag;
use Pterodactyl\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CloudServersController extends Controller
{
    
    private $alert;

    public function __construct(AlertsMessageBag $alert)
    {
        $this->alert = $alert;
    }


    // General Settings
    public function index(Request $request): View
    {
        $settings = DB::table('cloudsettings')->where('id', '=', (int) 1)->first();

        return view('admin.cloudservers.index', [
            'settings' => $settings
        ]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'io' => 'required',
            'cpu' => 'required',
            'swap' => 'required',
            'allocation' => 'required',
            'database' => 'required',
            'backup' => 'required',
            'min_memory' => 'required',
            'max_memory' => 'required',
            'min_disk' => 'required',
            'max_disk' => 'required',
        ]);

        $cpu = trim($request->input('cpu'));
        $swap = trim($request->input('swap'));
        $allocation = trim($request->input('allocation'));
        $io = trim($request->input('io'));
        $database = trim($request->input('database'));
        $backup = trim($request->input('backup'));
        $max_memory = trim($request->input('max_memory'));
        $min_memory = trim($request->input('min_memory'));
        $max_disk = trim($request->input('max_disk'));
        $min_disk = trim($request->input('min_disk'));

        DB::table('cloudsettings')->where('id', '=', 1)->update([
            'default_cpu' => $cpu,
            'default_swap' => $swap,
            'default_io' => $io,
            'default_allocation' => $allocation,
            'default_database' => $database,
            'default_backup' => $backup,
            'min_memory' => $min_memory,
            'max_memory' => $max_memory,
            'min_disk' => $min_disk,
            'max_disk' => $max_disk,
        ]);

        $this->alert->success('You have successfully updated the CloudServer settings')->flash();

        return redirect()->route('admin.cloudservers.index');
    }

    // Games
    public function games(Request $request): View
    {
        $eggs = DB::table('cloudeggs')->get();

        return view('admin.cloudservers.games.index', [
            'eggs' => $eggs
        ]);
    }

    public function game_status(Request $request)
    {
        $name = trim($request->input('status'));
        $egg = DB::table('cloudeggs')->where('name', '=', $name)->first();

        if($egg->status == 1) {
            DB::table('cloudeggs')->where('name', '=', $name)->update([
                'status' => (int) 0,
            ]);
        
            $this->alert->danger('You disabled the game.')->flash();
        } elseif($egg->status == 0) {
            DB::table('cloudeggs')->where('name', '=', $name)->update([
                'status' => (int) 1,
            ]);
            
            $this->alert->success('You enabled the game.')->flash();
        }

        return redirect()->route('admin.cloudservers.games');
    }

    public function game_new(): View
    {
        $eggs = DB::table('eggs')->get();

        return view('admin.cloudservers.games.new', [
            'eggs' => $eggs
        ]);
    }

    public function game_create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'img' => 'required',
            'eggid' => 'required',
        ]);

        $name = trim($request->input('name'));
        $img = trim($request->input('img'));
        $eggid = trim($request->input('eggid'));

        DB::table('cloudeggs')->insert([
            'name' => $name,
            'img' => $img,
            'eggid' => $eggid,
        ]);

        $this->alert->success('You success fully created the game.')->flash();
        return redirect()->route('admin.cloudservers.games');
    }

    public function game_edit(Request $request, $id): View
    {
        $id = (int) $id;

        $game = DB::table('cloudeggs')->where('id', '=', $id)->first();
        $eggs = DB::table('eggs')->get();
        
        return view('admin.cloudservers.games.edit', [
            'game' => $game,
            'eggs' => $eggs
        ]);
    }

    public function game_update(Request $request, $id) 
    {
        $id = (int) $id;

        $this->validate($request, [
            'name' => 'required',
            'img' => 'required',
            'eggid' => 'required',
        ]);
        
        $name = $request->input('name');
        $img = $request->input('img');
        $eggid = $request->input('eggid');

        DB::table('cloudeggs')->where('id', '=', $id)->update([
            'name' => $name,
            'img' => $img,
            'eggid' => $eggid,
        ]);

        $this->alert->success('You success fully edited this game.')->flash();
        return redirect()->route('admin.cloudservers.games');
    }

    public function game_delete(Request $request, $id) 
    {
        $id = (int) $id;

        DB::table('cloudeggs')->where('id', '=', $id)->delete();

        $this->alert->success('You success fully deleted this game.')->flash();
        return redirect()->route('admin.cloudservers.games');
    }

    // Logs
    public function logs(Request $request)
    {
        $logs = DB::table('cloudlogs')->get();

        return view('admin.cloudservers.logs', [
            'logs' => $logs
        ]);
    }

    // Users
    public function users(): View
    {
        $users = DB::table('users')->get();

        return view('admin.cloudservers.users', [
            'users' => $users
        ]);
    }

    public function user_update(Request $request, $id)
    {
        $id = (int) $id;

        $this->validate($request, [
            'memory' => 'required|numeric|min:0|max:999999',
            'disk' => 'required|numeric|min:0|max:999999',
        ]);

        $memory = (int) $request->input('memory');
        $disk = (int) $request->input('disk');
        
        if($memory >= 0 || $disk >= 0) {
            DB::table('users')->where('id', '=', $id)->update([
                'memory' => $memory,
                'disk' => $disk,
            ]);
        }

        $this->alert->success('You success fully edited this users memory end disk.')->flash();
        return redirect()->route('admin.cloudservers.users');
    }
    
}