<h1>Sửa sách</h1>

<form action="{{ route('books.update', $book) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Tên sách:</label>
    <input type="text" name="title" value="{{ old('title', $book->title) }}">
    @error('title') <p style="color:red">{{ $message }}</p> @enderror

    <label>Tác giả:</label>
    <input type="text" name="author" value="{{ old('author', $book->author) }}">
    @error('author') <p style="color:red">{{ $message }}</p> @enderror

    <label>Thể loại:</label>
    <select name="category_id">
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id') <p style="color:red">{{ $message }}</p> @enderror

    <label>Mô tả:</label>
    <textarea name="description">{{ old('description', $book->description) }}</textarea>

    <label>Năm xuất bản:</label>
    <input type="number" name="published_year" value="{{ old('published_year', $book->published_year) }}">
    @error('published_year') <p style="color:red">{{ $message }}</p> @enderror

    <label>Trạng thái:</label>
    <select name="status">
        @foreach(['Want to Read', 'Reading', 'Read'] as $status)
            <option value="{{ $status }}" {{ old('status', $book->status) == $status ? 'selected' : '' }}>
                {{ $status }}
            </option>
        @endforeach
    </select>

    <button type="submit">Cập nhật</button>
</form>

<a href="{{ route('books.index') }}">Quay lại</a>