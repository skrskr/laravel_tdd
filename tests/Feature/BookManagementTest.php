<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
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
        $response = $this->post('/books', $this->data());

        $book = Book::first();
        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    public function test_a_title_is_required()
    {
        $response = $this->post('/books', array_merge($this->data(), array('title' => '')));

        $response->assertSessionHasErrors("title");
    }

    public function test_a_author_is_required()
    {
        $response = $this->post('/books', array_merge($this->data(), array('author_id' => '')));

        $response->assertSessionHasErrors("author_id");
    }

    public function test_a_book_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $this->post('/books', $this->data());

        $book = Book::first();
        $response = $this->patch($book->path(), $this->data());

        $this->assertEquals('cool title', Book::first()->title);
        $response->assertRedirect($book->fresh()->path());
    }

    public function test_a_book_can_be_deleted()
    {
        $this->withoutExceptionHandling();
        $this->post('/books', $this->data());

        $book = Book::first();
        $this->assertCount(1, Book::all());
        $response = $this->delete($book->path());
        $this->assertCount(0, Book::all());
        $response->assertRedirect("/books");
    }

    public function test_a_new_author_automatically_added()
    {
        $this->post('/books', $this->data());

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    private function data()
    {
        return [
            'title' => 'cool title',
            'author_id' => 'cool author',
        ];
    }
}
