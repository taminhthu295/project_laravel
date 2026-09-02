<h1>Sửa thể loại</h1>

<form action="{{ route('categories.update', $category) }}" method="POST">
    @csrf
    @method('PUT')
    <label>Tên thể loại:</label>
    <input type="text" name="name" value="{{ old('name', $category->name) }}">
    @error('name') <p style="color:red">{{ $message }}</p> @enderror

    <button type="submit">Cập nhật</button>
</form>

<a href="{{ route('categories.index') }}">Quay lại</a>