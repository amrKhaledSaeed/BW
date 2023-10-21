<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateCustomer extends Seeder
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
            'name' => 'customer1',
            'email'=> 'kamr944@gmail.com',
            'password' => bcrypt('123456789'),
             ]);

             $role = Role::create(['name' => 'customer']);
             $permission = Permission::create(['name' => 'view-transactions-customer']);
             $role->givePermissionTo($permission);
             $user->assignRole('customer');
             $user->syncPermissions($permission['name']);


    }
}
