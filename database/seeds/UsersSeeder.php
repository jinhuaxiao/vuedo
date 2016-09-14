<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        //factory(App\User::class, 2)->create();
        $admin = factory(App\User::class, 'admin')->create(['email' => 'jinhua2026@gmail.com']);

        $this->command->info("New Admin created. Username: $admin->email,  Password: jinhua");
    }
}
