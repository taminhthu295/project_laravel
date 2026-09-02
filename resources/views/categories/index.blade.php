@extends('layouts.app')
@section('title', 'Danh sách thể loại')

@section('content')
<div class="actions-bar">
    <h1>Thể loại</h1>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">+ Thêm thể loại</a>
</div>

<table>
    <thead>
        <tr>
            <th>Tên thể loại</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td data-label="Tên">{{ $category->name }}</td>
            <td data-label="Hành động">
                <div class="row-actions">
                    <a href="{{ route('categories.edit', $category) }}">Sửa</a>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Xóa thể loại này?')">Xóa</button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection