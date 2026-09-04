@extends('layouts.app')
@section('title', 'Sửa sách')

@section('content')

<div class="page-header">
    <h1>Sửa sách</h1>
</div>

<div class="form-card">
    <form
        action="{{ route('books.update', $book) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        {{-- Tên sách --}}
        <div class="field">
            <label for="title">Tên sách</label>

            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $book->title) }}"
                placeholder="Nhập tên sách"
            >

            @error('title')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>


        {{-- Tác giả --}}
        <div class="field">
            <label for="author">Tác giả</label>

            <input
                type="text"
                id="author"
                name="author"
                value="{{ old('author', $book->author) }}"
                placeholder="Nhập tên tác giả"
            >

            @error('author')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>


        {{-- Thể loại --}}
        <div class="field">
            <label for="category_id">Thể loại</label>

            <select id="category_id" name="category_id">
                <option value="">-- Chọn thể loại (Tùy chọn) --</option>

                @foreach($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @error('category_id')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>


        {{-- Mô tả --}}
        <div class="field">
            <label for="description">Mô tả</label>

            <textarea
                id="description"
                name="description"
                placeholder="Nhập mô tả về cuốn sách..."
            >{{ old('description', $book->description) }}</textarea>

            @error('description')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>


        {{-- Năm xuất bản --}}
        <div class="field">
            <label for="published_year">Năm xuất bản</label>

            <input
                type="number"
                id="published_year"
                name="published_year"
                min="1"
                max="{{ date('Y') + 1 }}"
                value="{{ old('published_year', $book->published_year) }}"
                placeholder="Ví dụ: 2024"
            >

            @error('published_year')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>


        {{-- Trạng thái --}}
        <div class="field">
            <label for="status">Trạng thái</label>

            <select id="status" name="status">
                <option
                    value="Want to Read"
                    {{ old('status', $book->status) == 'Want to Read' ? 'selected' : '' }}
                >
                    Want to Read
                </option>

                <option
                    value="Reading"
                    {{ old('status', $book->status) == 'Reading' ? 'selected' : '' }}
                >
                    Reading
                </option>

                <option
                    value="Read"
                    {{ old('status', $book->status) == 'Read' ? 'selected' : '' }}
                >
                    Read
                </option>
            </select>

            @error('status')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>


        {{-- Ảnh sách --}}
        <div class="field">
            <label for="image">Ảnh sách</label>

            <div class="image-upload">
                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/*"
                    onchange="previewBookImage(event)"
                >

                <p class="image-upload__hint">
                    Có thể chọn ảnh bìa mới để thay thế ảnh hiện tại. Dung lượng tối đa 2MB.
                </p>

                <div class="image-preview-wrapper">
                    @if($book->image)
                        <img
                            id="image-preview"
                            class="image-preview"
                            src="{{ asset('storage/' . $book->image) }}"
                            alt="{{ $book->title }}"
                        >
                        <div class="image-remove-option" style="margin-top: 10px;">
                            <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; cursor: pointer; color: var(--rust);">
                                <input type="checkbox" name="remove_image" value="1" id="remove_image">
                                <span>Xóa ảnh bìa hiện tại</span>
                            </label>
                        </div>
                    @else
                        <img
                            id="image-preview"
                            class="image-preview"
                            src=""
                            alt="Xem trước ảnh sách"
                            style="display: none;"
                        >
                    @endif
                </div>
            </div>

            @error('image')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>


        {{-- Buttons --}}
        <div class="form-actions">

            <button type="submit" class="btn btn-primary">
                Cập nhật
            </button>

            <a
                href="{{ route('books.index') }}"
                class="btn btn-secondary"
            >
                Hủy
            </a>

        </div>

    </form>
</div>


<script>
    function previewBookImage(event) {
        const input = event.target;
        const preview = document.getElementById('image-preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection