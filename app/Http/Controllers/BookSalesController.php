<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\IncreaseBookCopiesSold;
use App\Models\Book;

class BookSalesController extends Controller
{
    public function store(Request $request, Book $book)
    {
        IncreaseBookCopiesSold::dispatch($book);
        return redirect('books');
    }
}
