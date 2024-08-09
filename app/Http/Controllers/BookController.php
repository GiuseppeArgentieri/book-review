<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter');

        // if title is not null  it will run this function
        $books = Book::when($title, function($query, $title){
            return $query->title($title);
        });

        $books = match($filter){
            "popular_last_month"=>$books->popularLastMonth(),
            "popular_last_6months"=>$books->popularLast6Months(),
            "highest_rated_last_month"=>$books->highestRatedLastMonth(),
            "highest_rated_last_6months"=>$books->highestRatedLast6Months(),
            default=>$books->latestPersonal()
        };

        //$books = $books->get();
        $cacheKey = 'books:' . $filter . ':' . $title;
        $books = Cache::remember($cacheKey, 3600, fn() => $books->get());
        return view('books.index', ["books"=>$books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        // Book $books come parametro al posto di $id implementa la route model binding che fa automaticamente
        // una query nel database -> se si vuole cachare per evitare query non è l'ideale
        // lazy loading is the same -> implement several queries -> $book->reviews()
        $cacheKey = 'book:' . $id;
        $book = Cache::remember($cacheKey, 3600, function() use($id){
            // Carica il libro con il conteggio delle recensioni
            $res = Book::withCount('reviews')->withAvg('reviews','rating')->findOrFail($id);
            // Carica le recensioni più recenti
            $res->load(['reviews' => function($query) {
                $query->latest();
            }]);
            return $res;
            }
        );

        return view('books.show', ["book"=>$book]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
