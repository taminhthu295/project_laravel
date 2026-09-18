<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Lấy danh sách thể loại kèm số lượng sách và sắp xếp theo tên
    public function index()
    {
        $categories = Category::withCount('books')->orderBy('name')->get();
        return view('categories.index', compact('categories'));
    }

    // Hiển thị form tạo mới thể loại
    public function create()
    {
        return view('categories.create');
    }

    // Validate và lưu thể loại mới vào database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.unique' => 'Tên thể loại này đã tồn tại.',
        ]);

        Category::create($request->only('name'));

        return redirect()->route('categories.index')
            ->with('success', 'Thêm thể loại thành công.');
    }

    // Hiển thị form chỉnh sửa thể loại
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // Validate và cập nhật thông tin thể loại
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ], [
            'name.unique' => 'Tên thể loại này đã tồn tại.',
        ]);

        $category->update($request->only('name'));

        return redirect()->route('categories.index')
            ->with('success', 'Cập nhật thể loại thành công.');
    }

    // Xóa thể loại khỏi database
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Xóa thể loại thành công.');
    }
}