<?php

namespace Pterodactyl\Http\Controllers\Api\Client;


use Pterodactyl\Http\Requests\Api\Client\CloudServersRequest;
use Illuminate\Http\JsonResponse;
use Prologue\Alerts\AlertsMessageBag;
use Pterodactyl\Models\Nest;
use Pterodactyl\Models\Node;
use Illuminate\Support\Facades\DB;
use Pterodactyl\Services\Servers\ServerCreationService;
use Pterodactyl\Exceptions\DisplayException;

class CloudServersController extends ClientApiController
{
    
    private $alert;

    public function __construct(ServerCreationService $creationService, AlertsMessageBag $alert)
    {
        $this->creationService = $creationService;
        $this->alert = $alert;
    }

    public function index(CloudServersRequest $request): array
    {
        $cloudeggs = DB::table('cloudeggs')->get();

        
        foreach ($cloudeggs as $cloudegg) {

            $eggs = DB::table('eggs')->where('id', '=', $cloudegg->eggid)->first();

            $cloudegg->description = $eggs->description;
        }

        return [
            'success' => true,
            'data' => [
                'eggs' => $cloudeggs,
            ],
        ];
    }

    public function game(CloudServersRequest $request, $id): array
    {
        $id = (int) $id;
        $user = DB::table('users')->select('memory', 'disk')->where('id', '=', $request->user()->id)->get();

        $cloudegg = DB::table('cloudeggs')->where('id', '=', $id)->first();
        $egg = DB::table('eggs')->select('id','name')->where('id', '=', $cloudegg->eggid)->get();

        return [
            'success' => true,
            'data' => [
                'egg' => $egg,
                'user' => $user,
            ],
        ];

    }

    public function create(CloudServersRequest $request): array
    {   

        $settings = DB::table('cloudsettings')->where('id', '=', (int) 1)->first();
        $egg = DB::table('eggs')->where('id', '=', $request->input('egg'))->first();
        $nest = DB::table('nests')->where('id', '=', $egg->nest_id)->first();
        $allocation = $this->getAllocationId();

        $this->validate($request, [
            'name' => 'required',
            'memory' => 'required|numeric|min:'.$settings->min_memory.'|max:'.$settings->max_memory.'',
            'disk' => 'required|numeric|min:'.$settings->min_disk.'|max:'.$settings->max_disk.'',
        ]);

        if($request->user()->memory < $request->input('memory')) {
            throw new DisplayException('You dont have so much disk.');
        }
        if($request->user()->disk < $request->input('disk')) {
            throw new DisplayException('You dont have so much disk.');
        }
        if (!$allocation) throw new DisplayException('We are sorry but at the moment there is no space left on our servers.');
        if (!$egg) return redirect()->back();
    
        $data = [
            'name' => $request->input('name'),
            'owner_id' => $request->user()->id,
            'egg_id' => $egg->id,
            'nest_id' => $nest->id,
            'description' => $request->input('description'),
            'allocation_id' => $allocation,
            'environment' => [],
            'memory' => $request->input('memory'),
            'disk' => $request->input('disk'),
            'cpu' => $settings->default_cpu,
            'swap' => $settings->default_swap,
            'io' => $settings->default_io,
            'cloudserver' => (int) 1,
            'database_limit' => (int) $settings->default_database,
            'allocation_limit' => (int) $settings->default_allocation,
            'backup_limit' => (int) $settings->default_backup,
            'image' => $egg->docker_images[0],
            'startup' => $egg->startup,
            'start_on_completion' => true,
        ];

        foreach (DB::table('egg_variables')->get() as $var) {
            $key = "v{$nest->id}-{$egg->id}-{$var->env_variable}";
            $data['environment'][$var->env_variable] = $request->get($key, $var->default_value);
        }

        $server = $this->creationService->handle($data);
        $server->save();
    
        DB::table('cloudlogs')->insert([
            'creator' => $request->user()->username,
            'type' => $egg->name,
            'name' => $request->input('name'),
            'memory' => $request->input('memory'),
            'disk' => $request->input('disk'),
            'created_at' => date("Y-m-d h:m:s"),
            'updated_at' => date("Y-m-d h:m:s"),
        ]);
    
        DB::table('cloudsettings')->where('id', '=', 1)->update([
            'totalram' => $settings->totalram+$request->input('memory'),
            'totalservers' => $settings->totalservers+1,
        ]);

        DB::table('users')->where('id', '=', $request->user()->id)->update([
            'memory' => $request->user()->memory-$request->input('memory'),
            'disk' => $request->user()->disk-$request->input('disk'),
        ]);


        return [
            'success' => true,
            'data' => [],
        ];
    }

    private function getAllocationId($memory = 0, $attempt = 0)
    {
        
        if ($attempt > 6) return null;
        
        $node = Node::where('nodes.public', true)->where('nodes.maintenance_mode', false)->first();

        if (!$node) return false;

        $allocation = $node->allocations()->where('server_id', null)->inRandomOrder()->first();

        if (!$allocation) return $this->getAllocationId($memory, $attempt+1);

        return $allocation->id;
    }
}