<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['review', 'rating'];
    // $fillable -> in order to create reviews like this:
    // $book = \App\Models\Book::find(1);
    // $review = $book->reviews()->create(['review'=>'sample review', 'rating'=>5]);
    public function book(){
        return $this->belongsTo(Book::class);
    }
}
