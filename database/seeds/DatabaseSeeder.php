<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Thread::class, 20)->create()->each(function($t){
            factory('App\Reply', 5)->create(['thread_id' => $t->id]);
        });
        
        // $this->call(UsersTableSeeder::class);
    }
}
