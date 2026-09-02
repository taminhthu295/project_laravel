<h1>Thêm sách</h1>

<form action="{{ route('books.store') }}" method="POST">
    @csrf

    <label>Tên sách:</label>
    <input type="text" name="title" value="{{ old('title') }}">
    @error('title') <p style="color:red">{{ $message }}</p> @enderror

    <label>Tác giả:</label>
    <input type="text" name="author" value="{{ old('author') }}">
    @error('author') <p style="color:red">{{ $message }}</p> @enderror

    <label>Thể loại:</label>
    <select name="category_id">
        <option value="">-- Chọn thể loại --</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id') <p style="color:red">{{ $message }}</p> @enderror

    <label>Mô tả:</label>
    <textarea name="description">{{ old('description') }}</textarea>

    <label>Năm xuất bản:</label>
    <input type="number" name="published_year" value="{{ old('published_year') }}">
    @error('published_year') <p style="color:red">{{ $message }}</p> @enderror

    <label>Trạng thái:</label>
    <select name="status">
        <option value="Want to Read">Want to Read</option>
        <option value="Reading">Reading</option>
        <option value="Read">Read</option>
    </select>

    <button type="submit">Lưu</button>
</form>

<a href="{{ route('books.index') }}">Quay lại</a>