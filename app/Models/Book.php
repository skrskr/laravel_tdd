<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public $guarded = [];

    public function path()
    {
        return "/books/" . $this->id . "-" . \Str::slug($this->title);
    }

    public function setAuthorIdAttribute($author)
    {
        $author = Author::firstOrCreate([
            'name' => $author
        ]);
        $this->attributes["author_id"] = $author->id; 
    }
}
