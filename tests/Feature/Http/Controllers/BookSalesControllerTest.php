<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Bus;
use App\Jobs\IncreaseBookCopiesSold;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;

class BookSalesControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_if_increases_books_sales()
    {
        // show the actual error
        $this->withoutExceptionHandling();

        // dispatch job
        Bus::fake();

        // we want to create a user
        $user = User::factory()->create();

        // act as user
        $this->actingAs($user);

        // we want to create a book
        $book = $user->books()->save(Book::factory()->make());

        // make a request to the sales endpoint
        $response = $this->post("books/{$book->id}/sales");

        $response->assertStatus(302);
        $response->assertRedirect('books');

        //assert a job was dispatch (not if the job works!)
        Bus::assertDispatched( IncreaseBookCopiesSold::class, function($job) use ($book){
            return $job->book->id === $book->id;
        });
    }

}
