<?php

use Illuminate\Database\Seeder;
use Modules\User\Model\User;
use Modules\User\Model\UserRole;

class UserSeeder extends Seeder{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        factory(User::class, 4)
            ->create()
            ->each(function($user){
                $user_role = new UserRole(['user_id' => $user->id, 'role_id' => 2]);
                $user_role->save();
            });
    }
}
