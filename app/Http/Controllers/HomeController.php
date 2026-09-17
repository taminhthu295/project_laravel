<?php

namespace App\Http\Controllers;

use App\Models\Book;

class HomeController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $wantCount = Book::where('status', 'Want to Read')->count();
        $readingCount = Book::where('status', 'Reading')->count();
        $readCount = Book::where('status', 'Read')->count();

        $recentBooks = Book::with('category')->latest()->take(5)->get();

        return view('home', compact(
            'totalBooks', 'wantCount', 'readingCount', 'readCount', 'recentBooks'
        ));
    }
}