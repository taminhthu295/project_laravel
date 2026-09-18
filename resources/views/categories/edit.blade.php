{{-- View: Form chỉnh sửa tên thể loại, hiển thị sẵn tên cũ vào ô input --}}
@extends('layouts.app')
@section('title', 'Sửa thể loại')

@section('content')
<h1>Sửa thể loại</h1>

<div class="form-card">
    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="field">
            <label>Tên thể loại</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}">
            @error('name') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('categories.index') }}" class="btn">Hủy</a>
    </form>
</div>
@endsection