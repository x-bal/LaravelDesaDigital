<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class RoleUserPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:roleuserpermission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for role user permission';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $users = User::get();
            foreach ($users as $data) {
                $this->line(' | ' . $data->id . ' | ' . $data->name . ' | ' . $data->email . ' | ');
            }
            $id = $this->ask('Choose According To id');
            $roles = Role::get();
            foreach ($roles as $data) {
                $this->line(' | ' . $data->id . ' | ' . $data->name . ' | ');
            }
            $role = $this->ask('What Role?, Choose By Id');
            User::find($id)->syncRoles([]);
            $response = User::find($id)->assignRole($role);
            $this->info($response);
        } catch (\Throwable $th) {
            $this->error('Something went wrong! : ' . $th->getMessage());
        }
        return Command::SUCCESS;
    }
}
