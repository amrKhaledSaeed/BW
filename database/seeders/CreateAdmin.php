<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateAdmin extends Seeder
{
    private $userModel;
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = $this->userModel->create([
            'name' => 'admin1',
            'email'=> 'kamr955@gmail.com',
            'password' => bcrypt('123456789'),
             ]);
             $adminPermissions =[
                ['name' => 'create-transaction-categories'],
                ['name' => 'create-transaction-subcategorie'],
                ['name' => 'create-transactions'],
                ['name' => 'view-transactions'],
                ['name' => 'record-payments'],
                ['name' => 'generate-reports'],
             ];

             $role = Role::create(['name' => 'admin']);
             foreach($adminPermissions as $adminPermission)
             {
                if($adminPermission['name'] != 'view-transactions')
                {
                    $permission = Permission::create($adminPermission);
                    $role->givePermissionTo($permission);
                    $user->givePermissionTo($adminPermission['name']);

                }else{
                    $permission = Permission::where('name','view-transactions')->first();
                    $role->givePermissionTo($permission);
                    $user->givePermissionTo($permission['name']);

                }
             }

             $user->assignRole('admin');
    }
}
