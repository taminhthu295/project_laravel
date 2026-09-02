@extends('layouts.app')
@section('title', 'Danh sách sách')

@section('content')
<div class="actions-bar">
    <h1>Sách</h1>
    <a href="{{ route('books.create') }}" class="btn btn-primary">+ Thêm sách</a>
</div>

<form method="GET" action="{{ route('books.index') }}" class="filter-bar">
    <input type="text" name="search" placeholder="Tìm theo tên sách" value="{{ request('search') }}">

    <select name="category_id">
        <option value="">Tất cả thể loại</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <select name="status">
        <option value="">Tất cả trạng thái</option>
        <option value="Want to Read" {{ request('status') == 'Want to Read' ? 'selected' : '' }}>Want to Read</option>
        <option value="Reading" {{ request('status') == 'Reading' ? 'selected' : '' }}>Reading</option>
        <option value="Read" {{ request('status') == 'Read' ? 'selected' : '' }}>Read</option>
    </select>

    <button type="submit" class="btn btn-sm">Lọc</button>
    <a href="{{ route('books.index') }}" class="btn btn-sm">Xóa lọc</a>
</form>

<table>
    <thead>
        <tr>
            <th>Tên sách</th>
            <th>Tác giả</th>
            <th>Thể loại</th>
            <th>Trạng thái</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($books as $book)
        @php
            $statusClass = match($book->status) {
                'Reading' => 'status--reading',
                'Read' => 'status--read',
                default => 'status--want',
            };
        @endphp
        <tr>
            <td data-label="Tên">{{ $book->title }}</td>
            <td data-label="Tác giả">{{ $book->author }}</td>
            <td data-label="Thể loại">{{ $book->category->name ?? 'N/A' }}</td>
            <td data-label="Trạng thái"><span class="status {{ $statusClass }}">{{ $book->status }}</span></td>
            <td data-label="Hành động">
                <div class="row-actions">
                    <a href="{{ route('books.show', $book) }}">Xem</a>
                    <a href="{{ route('books.edit', $book) }}">Sửa</a>
                    <form action="{{ route('books.destroy', $book) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Xóa sách này?')">Xóa</button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection