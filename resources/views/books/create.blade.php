{{-- View: Form nhập thông tin để thêm mới một cuốn sách vào thư viện --}}
@extends('layouts.app')
@section('title', 'Thêm sách')

@section('content')

<div class="page-header">
    <h1>Thêm sách</h1>
</div>

<div class="form-card">
    <form
        action="{{ route('books.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        {{-- Tên sách --}}
        <div class="field">
            <label for="title">Tên sách</label>

            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title') }}"
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
                value="{{ old('author') }}"
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
                <option value="">-- Chọn thể loại --</option>

                @foreach($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}
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
            >{{ old('description') }}</textarea>

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
                value="{{ old('published_year') }}"
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
                    {{ old('status', 'Want to Read') == 'Want to Read' ? 'selected' : '' }}
                >
                    Want to Read
                </option>

                <option
                    value="Reading"
                    {{ old('status') == 'Reading' ? 'selected' : '' }}
                >
                    Reading
                </option>

                <option
                    value="Read"
                    {{ old('status') == 'Read' ? 'selected' : '' }}
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
            <label>Ảnh bìa sách (Tùy chọn)</label>

            <div class="image-upload">
                {{-- Cách 1: Tải file từ máy tính --}}
                <div class="image-upload-group">
                    <label for="image">1. Tải ảnh từ máy tính (Tối đa 2MB)</label>
                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept="image/*"
                        onchange="previewBookFile(event)"
                    >
                </div>

                <div class="image-divider">
                    <span>HOẶC</span>
                </div>

                {{-- Cách 2: Dán liên kết ảnh trực tuyến --}}
                <div class="image-upload-group">
                    <label for="image_url">2. Dán liên kết ảnh trực tuyến (URL)</label>
                    <input
                        type="url"
                        id="image_url"
                        name="image_url"
                        value="{{ old('image_url') }}"
                        placeholder="Ví dụ: https://images.unsplash.com/... hoặc link ảnh từ Goodreads, Tiki..."
                        oninput="previewBookUrl(this.value)"
                    >
                </div>

                <div class="image-preview-wrapper">
                    <img
                        id="image-preview"
                        class="image-preview"
                        src="{{ old('image_url') ?: '' }}"
                        alt="Xem trước ảnh bìa sách"
                        style="{{ old('image_url') ? '' : 'display: none;' }}"
                        onerror="handleImageError(this)"
                    >
                    <p id="image-error-text" class="field-error" style="display: none; margin-top: 6px;">Không thể tải ảnh từ link này. Vui lòng kiểm tra lại URL.</p>
                </div>
            </div>

            @error('image')
                <div class="field-error">{{ $message }}</div>
            @enderror
            @error('image_url')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>


        {{-- Buttons --}}
        <div class="form-actions">

            <button type="submit" class="btn btn-primary">
                Lưu
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
    function previewBookFile(event) {
        const input = event.target;
        const preview = document.getElementById('image-preview');
        const errorText = document.getElementById('image-error-text');
        const urlInput = document.getElementById('image_url');

        if (errorText) errorText.style.display = 'none';

        if (input.files && input.files[0]) {
            if (urlInput) urlInput.value = '';

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else if (!urlInput || !urlInput.value) {
            preview.src = '';
            preview.style.display = 'none';
        }
    }

    function previewBookUrl(url) {
        const preview = document.getElementById('image-preview');
        const errorText = document.getElementById('image-error-text');
        const fileInput = document.getElementById('image');

        if (errorText) errorText.style.display = 'none';

        if (url && url.trim() !== '') {
            if (fileInput) fileInput.value = '';

            preview.src = url.trim();
            preview.style.display = 'block';
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }

    function handleImageError(img) {
        img.style.display = 'none';
        const errorText = document.getElementById('image-error-text');
        if (errorText && document.getElementById('image_url') && document.getElementById('image_url').value.trim() !== '') {
            errorText.style.display = 'block';
        }
    }
</script>

@endsection