@extends('layouts.app')
@section('title', 'Thêm sách')

@section('content')
<h1>Thêm sách</h1>

<div class="form-card">
    <form action="{{ route('books.store') }}" method="POST">
        @csrf

        <div class="field">
            <label>Tên sách</label>
            <input type="text" name="title" value="{{ old('title') }}">
            @error('title') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label>Tác giả</label>
            <input type="text" name="author" value="{{ old('author') }}">
            @error('author') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label>Thể loại</label>
            <select name="category_id">
                <option value="">-- Chọn thể loại --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label>Mô tả</label>
            <textarea name="description">{{ old('description') }}</textarea>
        </div>

        <div class="field">
            <label>Năm xuất bản</label>
            <input type="number" name="published_year" value="{{ old('published_year') }}">
            @error('published_year') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="field">
            <label>Trạng thái</label>
            <select name="status">
                <option value="Want to Read">Want to Read</option>
                <option value="Reading">Reading</option>
                <option value="Read">Read</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('books.index') }}" class="btn">Hủy</a>
    </form>
</div>
@endsection