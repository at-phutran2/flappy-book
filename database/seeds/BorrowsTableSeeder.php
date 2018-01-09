<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;

class BorrowsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $bookId = DB::table('books')->pluck('id')->toArray();
        $userId = DB::table('users')->pluck('id')->toArray();
        $faker = Faker::create();
        factory(App\Model\Borrow::class, 15)->create([
            'book_id' => $faker->randomElement($bookId),
            'user_id' => $faker->randomElement($userId)
        ]);
    }
}