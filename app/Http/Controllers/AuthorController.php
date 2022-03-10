<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function store(Request $request)
    {
        Author::create($this->validateRequest($request));
    }

    public function validateRequest($request)
    {
        return $request->validate([
            'name' => 'required',
            'dob' => 'required'
        ]);
    }
}
