<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Hiển thị danh sách sách kèm tìm kiếm, lọc và phân trang 10 cuốn/trang
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

    // Hiển thị form thêm mới sách
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('books.create', compact('categories'));
    }

    // Validate dữ liệu, lưu ảnh và tạo mới sách trong database
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

        // Lưu file ảnh vào storage hoặc lấy đường link ảnh online
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('books', 'public');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->image_url;
        }

        Book::create($data);

        return redirect()->route('books.index')
            ->with('success', 'Thêm sách thành công.');
    }

    // Hiển thị thông tin chi tiết một cuốn sách
    public function show(Book $book)
    {
        $book->load('category');
        return view('books.show', compact('book'));
    }

    // Hiển thị form chỉnh sửa thông tin sách
    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        return view('books.edit', compact('book', 'categories'));
    }

    // Validate, cập nhật thông tin và xử lý thay đổi ảnh bìa của sách
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

        // Xử lý upload ảnh mới, đổi link hoặc xóa ảnh cũ
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

    // Xóa file ảnh trong storage và xóa sách khỏi database
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