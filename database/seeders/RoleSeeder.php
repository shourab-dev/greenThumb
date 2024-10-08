<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'user',
            'delivery-man',
        ];


        foreach ($roles as $role) {
            Role::create([
                'name' => $role,
            ]);
        }


        $users = User::get();

        foreach ($users as $user) {
            $user->assignRole('user');
        }
    }
}
