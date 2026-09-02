<h1>Danh sách thể loại</h1>

<a href="{{ route('categories.create') }}">+ Thêm thể loại</a>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Tên thể loại</th>
        <th>Hành động</th>
    </tr>
    @foreach($categories as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td>
                <a href="{{ route('categories.edit', $category) }}">Sửa</a>
                <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Xóa thể loại này?')">Xóa</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>