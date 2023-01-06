<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Comment;
use App\Models\Item;
use App\Models\Label;
use App\Models\User;
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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $users = collect();

        $users->add(User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@szerveroldali.hu',
            'is_admin' => true,
            'password' => bcrypt('adminpwd'),
        ]));

        for($i = 1; $i <= 10; $i++) {
            $users->add(
            User::factory()->create([
                'name' => 'User' . $i,
                'email' => 'user' . $i . '@szerveroldali.hu',
                'password' => bcrypt('password'),
            ])
            );
        }

        $labels = Label::factory(30)->create();
        $items = Item::factory(20)->create();
        $comments = Comment::factory(40)->create();

        $items->each(function($item) use ($labels) {
            $item->labels()->attach($labels->random(rand(1, 3)));
        });

        $comments->each(function($comment) use ($users, $items) {
            $comment->author()->associate($users->random())->save();
            $comment->item()->associate($items->random())->save();
        });

        
    }
}
