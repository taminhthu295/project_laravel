@extends('layouts.app')
@section('title', 'Sửa sách')

@section('content')
<h1>Sửa sách</h1>

<div class="form-card">
    <form action="{{ route('books.update', $book) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="field">
            <label>Tên sách</label>
            <input type="text" name="title" value="{{ old('title', $book->title) }}">
            @error('title') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label>Tác giả</label>
            <input type="text" name="author" value="{{ old('author', $book->author) }}">
            @error('author') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label>Thể loại</label>
            <select name="category_id">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label>Mô tả</label>
            <textarea name="description">{{ old('description', $book->description) }}</textarea>
        </div>

        <div class="field">
            <label>Năm xuất bản</label>
            <input type="number" name="published_year" value="{{ old('published_year', $book->published_year) }}">
            @error('published_year') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label>Trạng thái</label>
            <select name="status">
                @foreach(['Want to Read', 'Reading', 'Read'] as $status)
                    <option value="{{ $status }}" {{ old('status', $book->status) == $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('books.index') }}" class="btn">Hủy</a>
    </form>
</div>
@endsection