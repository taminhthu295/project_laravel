<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Danh sách sách + tìm kiếm + lọc
    public function index(Request $request)
    {
        $query = Book::with('category');

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Lọc theo thể loại
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $books = $query->latest()->get();
        $categories = Category::all();

        return view('books.index', compact('books', 'categories'));
    }

    // Form thêm sách
    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    // Lưu sách mới
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'published_year' => 'nullable|digits:4|integer',
            'status' => 'required|in:Want to Read,Reading,Read',
        ]);

        Book::create($request->all());

        return redirect()->route('books.index')
            ->with('success', 'Thêm sách thành công.');
    }

    // Xem chi tiết sách
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    // Form sửa sách
    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    // Cập nhật sách
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'published_year' => 'nullable|digits:4|integer',
            'status' => 'required|in:Want to Read,Reading,Read',
        ]);

        $book->update($request->all());

        return redirect()->route('books.index')
            ->with('success', 'Cập nhật sách thành công.');
    }

    // Xóa sách
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Xóa sách thành công.');
    }
}