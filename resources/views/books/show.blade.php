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

<div class="page-header">
    <h1>{{ $book->title }}</h1>
</div>

<div class="detail">
    <div class="detail-content {{ $book->image ? '' : 'no-image' }}">

        {{-- THÔNG TIN SÁCH (BÊN TRÁI) --}}
        <div class="detail-info">
            <dl>
                <dt>Tác giả</dt>
                <dd>{{ $book->author }}</dd>

                <dt>Thể loại</dt>
                <dd>{{ $book->category->name ?? 'N/A' }}</dd>

                <dt>Năm xuất bản</dt>
                <dd>{{ $book->published_year ?: '—' }}</dd>

                <dt>Trạng thái</dt>
                <dd>
                    <span class="status {{ $statusClass }}">
                        {{ $book->status }}
                    </span>
                </dd>

                <dt>Mô tả</dt>
                <dd class="detail-description">
                    <p>{{ $book->description ?: '—' }}</p>
                </dd>
            </dl>
        </div>

        {{-- ẢNH SÁCH (BÊN PHẢI - NẾU CÓ) --}}
        @if($book->image)
            <div class="detail-media">
                <img
                    src="{{ asset('storage/' . $book->image) }}"
                    alt="{{ $book->title }}"
                    class="detail-image"
                >
            </div>
        @endif

    </div>

    <div class="detail-actions">
        <a href="{{ route('books.edit', $book) }}" class="btn btn-primary">Sửa</a>
        <a href="{{ route('books.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</div>
@endsection