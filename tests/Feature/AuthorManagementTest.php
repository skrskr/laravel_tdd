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

    public function test_author_can_be_created()
    {
        // $this->withoutExceptionHandling();
        $response = $this->post('/authors', $this->data());
        $authors = Author::all();
        $this->assertCount(1, $authors);
        $this->assertInstanceOf(Carbon::class, $authors->first()->dob);
        $this->assertEquals("2000/02/15", $authors->first()->dob->format("Y/m/d"));
    }

    public function test_a_name_required()
    {
        $response = $this->post('/authors', array_merge($this->data(), ['name' => '']));
        $response->assertSessionHasErrors("name");
    }

    public function test_a_dob_required()
    {
        $response = $this->post('/authors', array_merge($this->data(), ['dob' => '']));
        $response->assertSessionHasErrors("dob");
    }

    private function data()
    {
        return [
            'name' => "author name",
            'dob' => '02/15/2000'
        ];
    }
}
