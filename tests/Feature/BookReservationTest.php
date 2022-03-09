<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_a_book_can_be_checked_out_by_signed_in_user()
    {
        $this->withoutExceptionHandling();

        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post("/checkout/" . $book->id);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    public function test_only_signed_in_user_can_be_checked_out()
    {
        $book = Book::factory()->create();

        $this->post("/checkout/" . $book->id)->assertRedirect("/login");

        $this->assertCount(0, Reservation::all());
    }

    public function test_only_real_book_can_be_checkout()
    {
        // $this->withoutExceptionHandling();
        
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post("/checkout/123")
            ->assertStatus(404);

        $this->assertCount(0, Reservation::all());
    }

    public function test_a_book_can_be_checked_in_by_signed_in_user()
    {
        $this->withoutExceptionHandling();

        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post("/checkout/" . $book->id);

        
        $this->actingAs($user)
            ->post("/checkin/" . $book->id);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_in_at);
    }

    public function test_only_signed_in_user_can_be_checked_in()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post("/checkout/" . $book->id);

        Auth::logout();

        $this->post("/checkin/" . $book->id)->assertRedirect("/login");

        $this->assertCount(1, Reservation::all());
        $this->assertNull(Reservation::first()->checked_in_at);
    }

    public function test_a_404_is_thrown_if_a_book_is_not_checkedout_first()
    {
        $this->withoutExceptionHandling();
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post("/checkin/" . $book->id)
            ->assertStatus(404);

        $this->assertCount(0, Reservation::all());
    }

}
