{{-- View: Form nhập tên để thêm mới một thể loại sách --}}
@extends('layouts.app')
@section('title', 'Thêm thể loại')

@section('content')
<h1>Thêm thể loại</h1>

<div class="form-card">
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="field">
            <label>Tên thể loại</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('categories.index') }}" class="btn">Hủy</a>
    </form>
</div>
@endsection