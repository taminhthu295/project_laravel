<h1>Thêm thể loại</h1>

<form action="{{ route('categories.store') }}" method="POST">
    @csrf
    <label>Tên thể loại:</label>
    <input type="text" name="name" value="{{ old('name') }}">
    @error('name') <p style="color:red">{{ $message }}</p> @enderror

    <button type="submit">Lưu</button>
</form>

<a href="{{ route('categories.index') }}">Quay lại</a>