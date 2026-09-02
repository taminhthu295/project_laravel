<h1>Danh sách sách</h1>

<a href="{{ route('books.create') }}">+ Thêm sách</a>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<form method="GET" action="{{ route('books.index') }}">
    <input type="text" name="search" placeholder="Tìm theo tên sách" value="{{ request('search') }}">

    <select name="category_id">
        <option value="">-- Tất cả thể loại --</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <select name="status">
        <option value="">-- Tất cả trạng thái --</option>
        <option value="Want to Read" {{ request('status') == 'Want to Read' ? 'selected' : '' }}>Want to Read</option>
        <option value="Reading" {{ request('status') == 'Reading' ? 'selected' : '' }}>Reading</option>
        <option value="Read" {{ request('status') == 'Read' ? 'selected' : '' }}>Read</option>
    </select>

    <button type="submit">Lọc</button>
    <a href="{{ route('books.index') }}">Xóa lọc</a>
</form>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Tên sách</th>
        <th>Tác giả</th>
        <th>Thể loại</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
    </tr>
    @foreach($books as $book)
        <tr>
            <td>{{ $book->id }}</td>
            <td>{{ $book->title }}</td>
            <td>{{ $book->author }}</td>
            <td>{{ $book->category->name ?? 'N/A' }}</td>
            <td>{{ $book->status }}</td>
            <td>
                <a href="{{ route('books.show', $book) }}">Xem</a>
                <a href="{{ route('books.edit', $book) }}">Sửa</a>
                <form action="{{ route('books.destroy', $book) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Xóa sách này?')">Xóa</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>