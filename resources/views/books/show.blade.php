@extends('layouts.app')
@section('title', $book->title)

@section('content')
@php
    $statusClass = match($book->status) {
        'Reading' => 'status--reading',
        'Read' => 'status--read',
        default => 'status--want',
    };
@endphp

<h1>{{ $book->title }}</h1>

<div class="detail">
    <dl>
        <dt>Tác giả</dt>
        <dd>{{ $book->author }}</dd>

        <dt>Thể loại</dt>
        <dd>{{ $book->category->name ?? 'N/A' }}</dd>

        <dt>Mô tả</dt>
        <dd>{{ $book->description ?: '—' }}</dd>

        <dt>Năm xuất bản</dt>
        <dd>{{ $book->published_year ?: '—' }}</dd>

        <dt>Trạng thái</dt>
        <dd><span class="status {{ $statusClass }}">{{ $book->status }}</span></dd>
    </dl>

    <div class="detail-actions">
        <a href="{{ route('books.edit', $book) }}" class="btn btn-primary">Sửa</a>
        <a href="{{ route('books.index') }}" class="btn">Quay lại</a>
    </div>
</div>
@endsection