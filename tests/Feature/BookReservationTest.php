<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase; 

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_a_book_can_be_added_to_libray()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/books', [
            'title' => 'cool title',
            'author' => 'cool author',
        ]);

        $response->assertStatus(200);

        $this->assertCount(1, Book::all());
    }

    public function test_a_title_is_required()
    {
        $response = $this->post('/books', [
            'title' => '',
            'author' => 'cool author',
        ]);

        $response->assertSessionHasErrors("title");
    }

    public function test_a_author_is_required()
    {
        $response = $this->post('/books', [
            'title' => 'cool title',
            'author' => '',
        ]);

        $response->assertSessionHasErrors("author");
    }

    public function test_a_book_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $this->post('/books', [
            'title' => 'cool title',
            'author' => 'cool author',
        ]);

        $book = Book::first();
        $response = $this->patch("/books/" . $book->id, [
            'title' => "New title",
            'author' => "New author",
        ]);
        $response->assertOk();
        $this->assertEquals('New title', Book::first()->title);
    }
}
