<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use App\Model\Book;
use App\Model\User;
use App\Model\Borrow;
use App\Model\Category;
use Faker\Factory as Faker;

class SortBooksTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test check existence of button sort.
     *
     * @return void
     */
    public function testButtonSort()
    {
        $this->createUserForLogin();
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                    ->visit('admin/books');
            $elements = $browser->elements('#list-books .sort-element');
            $this->assertCount(4, $elements);
        });
    }

    /*
     * A Dusk test for click button.
     *
     * @return void
     */
    public function testClickButtonsSort()
    {
        $btnSortNames = ['title', 'author', 'rating', 'total_borrowed'];

        $this->createUserForLogin();
        $this->browse(function (Browser $browser) use ($btnSortNames) {

            $browser->loginAs(User::find(1))
                    ->visit('admin/books');

            foreach ($btnSortNames as $name) {
                $browser->press('#btn-sort-'.$name)
                        ->assertQueryStringHas('sort', $name)
                        ->assertQueryStringHas('order', 'asc')
                        ->press('#btn-sort-'.$name)
                        ->assertQueryStringHas('sort', $name)
                        ->assertQueryStringHas('order', 'desc');
            }
        });
    }

   /**
     * Make cases for test.
     *
     * @return void
     */
    public function dataForTest()
    {
        return [
            ['title', 2, 'asc'],
            ['author', 3, 'asc'],
            ['rating', 4, 'asc'],
            ['total_borrowed', 5, 'asc'],
            ['title', 2, 'desc'],
            ['author', 3, 'desc'],
            ['rating', 4, 'desc'],
            ['total_borrowed', 5, 'desc'],
        ];
    }

    /**
     * A Dusk test data.
     *
     * @dataProvider dataForTest
     */
    public function testSortData($name, $columIndex, $order)
    {
        $this->createUserForLogin();
        $this->makeData(5);
        $this->browse(function (Browser $browser) use ($name, $columIndex, $order) {
            $browser->loginAs(User::find(1))
                    ->visit('admin/books')
                    ->resize(1200,1600)
                    ->press('#btn-sort-'.$name);
            $this->assertCount(5, $browser->elements('#list-books tbody tr'));
            if ($order == 'desc') {
                $browser->press('#btn-sort-'.$name);
                $this->assertCount(5, $browser->elements('#list-books tbody tr'));
            }

            $listBooksSorted = Book::leftJoin('borrows', 'books.id', '=', 'borrows.book_id')
                                 ->select('title', 'author', 'rating', DB::raw('COUNT(borrows.id) AS total_borrowed'))
                                 ->groupBy('books.id')
                                 ->orderBy($name, $order)
                                 ->get();

            for ($i = 1; $i <= 5; $i++) {
                $selector = "#list-books tbody tr:nth-child($i) td:nth-child($columIndex)";
                $this->assertTrue($browser->text($selector) == $listBooksSorted[$i - 1]->$name);
            }
        });
    }

    /**
     * A Dusk test data when panigate.
     *
     * @dataProvider dataForTest
     * @return void
     */
    public function testSortDataWhenPanigate($name, $columIndex, $order)
    {
        $this->createUserForLogin();
        $this->makeData(16);
        $this->browse(function (Browser $browser) use ($name, $columIndex, $order) {
            $browser->loginAs(User::find(1))
                    ->visit('admin/books?page=2')
                    ->resize(1200,1600)
                    ->press('#btn-sort-'.$name);

            $this->assertCount(6, $browser->elements('#list-books tbody tr'));
            if ($order == 'desc') {
                $browser->press('#btn-sort-'.$name);
                $this->assertCount(6, $browser->elements('#list-books tbody tr'));
            }

            $listBooksSorted = Book::leftJoin('borrows', 'books.id', '=', 'borrows.book_id')
                                 ->select('title', 'author', 'rating', DB::raw('COUNT(borrows.id) AS total_borrowed'))
                                 ->groupBy('books.id')
                                 ->orderBy($name, $order)
                                 ->offset(10)
                                 ->limit(10)
                                 ->get();

            for ($i = 1; $i <= 6; $i++) {
                $selector = "#list-books tbody tr:nth-child($i) td:nth-child($columIndex)";
                $this->assertTrue($browser->text($selector) == $listBooksSorted[$i - 1]->$name);
            }
        });
    }

    /**
     * Create admin for login
     *
     * @return void
     */
    public function createUserForLogin()
    {
        factory(User::class, 1)->create([
            'is_admin' => 1,
        ]);
    }

    /**
     * Make data  in database for test.
     *
     * @return void
     */
    public function makeData($row)
    {
        $faker = Faker::create();

        factory(User::class, 2)->create();

        factory(Category::class, 2)->create();

        $categoryId = DB::table('categories')->pluck('id')->toArray();
        $employCode = DB::table('users')->pluck('employ_code')->toArray();
        $userId = DB::table('users')->pluck('id')->toArray();

        factory(Book::class, $row)->create([
            'category_id' => $faker->randomElement($categoryId),
            'from_person' => $faker->randomElement($employCode)
        ]);

        $bookId = DB::table('books')->pluck('id')->toArray();

        factory(Borrow::class, $row)->create([
            'book_id' => $faker->randomElement($bookId),
            'user_id' => $faker->randomElement($userId)
        ]);
    }
}
