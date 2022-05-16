<?php

namespace Tests\Feature\Jobs;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Jobs\IncreaseBookCopiesSold;

class IncreseBookCopiesSoldTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_if_increases_the_copies_sold()
    {
        // show the actual error
        $this->withoutExceptionHandling();

        // we want to create a user
        $user = User::factory()->create();

        // act as user
        $this->actingAs($user);

        // we want to create a book
        $book = $user->books()->save(Book::factory()->make());

        // make sure copies_sold is 0 after book has been created
        $this->assertEquals(0, $book->copies_sold);

        // we need to execute de job
        IncreaseBookCopiesSold::dispatch($book);

        // refresh the model
        $book->refresh();

        // check if the copies sold amount increased
        $this->assertEquals(1, $book->copies_sold);

    }
}
