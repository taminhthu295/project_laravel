<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $books = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'published_year' => 'nullable|integer|min:1|max:' . (date('Y') + 1),
            'status' => 'required|in:Want to Read,Reading,Read',
            'image' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url|max:2048',
        ]);

        $data = $request->except(['image', 'image_url']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('books', 'public');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        Book::create($data);

        return redirect()->route('books.index')
            ->with('success', 'Thêm sách thành công.');
    }

    public function show(Book $book)
    {
        $book->load('category');
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'published_year' => 'nullable|integer|min:1|max:' . (date('Y') + 1),
            'status' => 'required|in:Want to Read,Reading,Read',
            'image' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url|max:2048',
        ]);

        $data = $request->except(['image', 'image_url', 'remove_image']);

        $isLocalImage = $book->image && !str_starts_with($book->image, 'http://') && !str_starts_with($book->image, 'https://');

        if ($request->hasFile('image')) {
            if ($isLocalImage) {
                Storage::disk('public')->delete($book->image);
            }
            $data['image'] = $request->file('image')->store('books', 'public');
        } elseif ($request->filled('image_url')) {
            if ($isLocalImage) {
                Storage::disk('public')->delete($book->image);
            }
            $data['image'] = $request->image_url;
        } elseif ($request->boolean('remove_image')) {
            if ($isLocalImage) {
                Storage::disk('public')->delete($book->image);
            }
            $data['image'] = null;
        }

        $book->update($data);

        return redirect()->route('books.index')
            ->with('success', 'Cập nhật sách thành công.');
    }

    public function destroy(Book $book)
    {
        if ($book->image && !str_starts_with($book->image, 'http://') && !str_starts_with($book->image, 'https://')) {
            Storage::disk('public')->delete($book->image);
        }
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Xóa sách thành công.');
    }
}