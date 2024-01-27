<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();
        try{
            $role = Role::create(['name' => 'admin']);
            $role = Role::create(['name' => 'user']);
            $user = User::create([
                'name' => 'admin',
                'email' => config('dental.adminEmail'),
                'password' => Hash::make('12345678')
            ]);
            $user->assignRole('admin');
            $user->save();
            DB::commit();
        }catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
        }
    }
}
