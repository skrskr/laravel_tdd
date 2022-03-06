<?php

namespace Tests\Feature;

use App\Models\Author;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_autor_can_be_created()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/authors', [
            'name' => "author name",
            'dob' => '02/15/2000'
        ]);
        $authors = Author::all();
        $this->assertCount(1, $authors);
        $this->assertInstanceOf(Carbon::class, $authors->first()->dob);
        $this->assertEquals("2000/02/15", $authors->first()->dob->format("Y/m/d"));
    }
}
