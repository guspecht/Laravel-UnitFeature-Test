<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Notifications\BookCreated;

class BookControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_the_books_index_page_is_rendered_properly()
    {
        $this->withoutExceptionHandling();
        // we want to create a user
        $user = User::factory()->create();

        // act as user
        $this->actingAs($user);

        // we want to go to the /books page
        $response = $this->get('/books');

        // we want to get status 200
        $response->assertStatus(200);
    }

    public function test_users_can_create_books()
    {
        // show the actual error
        $this->withoutExceptionHandling();

        // fake notification
        Notification::fake();

        // we want to create a user
        $user = User::factory()->create();

        // act as user
        $this->actingAs($user);

        // we want to go to the post to the /books route to insert a book
        $response = $this->post('/books', [
            'name' => 'new book',
            'price' => 999
        ]);

        // we  want to make sure user is redirected to the /books page
        $response->assertStatus(302);

        // find the last book created
        $book = Book::get()->last();

        // we only have one book in the database;
        $this->assertEquals(1, Book::count());

        // we want to make sure the book has the proper data
        $this->assertEquals('new book', $book->name);
        $this->assertEquals(999, $book->price);
        $this->assertEquals(0, $book->copies_sold);
        $this->assertEquals($user->id, $book->user->id);
        $this->assertInstanceOf( User::class, $book->user);

        // check if notification was sent(not if the notification works!)
        Notification::assertSentTo( $user, BookCreated::class);
    }
}
