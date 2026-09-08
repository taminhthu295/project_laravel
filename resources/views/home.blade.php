@extends('layouts.app')
@section('title', 'Thư viện sách')

@section('content')
<div class="hero">
    <h1>Thư viện sách của bạn</h1>
    <p class="hero__tagline">Ghi lại hành trình đọc — từ những cuốn đang chờ, đến những cuốn đã gấp lại trang cuối.</p>
    <div class="hero__actions">
        <a href="{{ route('books.create') }}" class="btn btn-primary">+ Thêm sách</a>
        <a href="{{ route('categories.create') }}" class="btn">+ Thêm thể loại</a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-card__number">{{ $totalBooks }}</div>
        <div class="stat-card__label">Tổng số sách</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__number">{{ $wantCount }}</div>
        <div class="stat-card__label">Muốn đọc</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__number">{{ $readingCount }}</div>
        <div class="stat-card__label">Đang đọc</div>
    </div>
    <div class="stat-card">
        <div class="stat-card__number">{{ $readCount }}</div>
        <div class="stat-card__label">Đã đọc</div>
    </div>
</div>

<div class="section-heading">
    <h2>Mới thêm gần đây</h2>
    <a href="{{ route('books.index') }}">Xem tất cả sách →</a>
</div>

@if($recentBooks->isEmpty())
    <p class="empty-note">Chưa có sách nào. <a href="{{ route('books.create') }}">Thêm cuốn đầu tiên</a>.</p>
@else
<div class="recent-list">
    @foreach($recentBooks as $book)
    @php
        $statusClass = match($book->status) {
            'Reading' => 'status--reading',
            'Read' => 'status--read',
            default => 'status--want',
        };
    @endphp
    <a href="{{ route('books.show', $book) }}" class="recent-item">
        <div class="recent-item__thumb">
            @if($book->image_url)
                <img src="{{ $book->image_url }}" alt="{{ $book->title }}">
            @else
                <span>📕</span>
            @endif
        </div>
        <div class="recent-item__info">
            <strong>{{ $book->title }}</strong>
            <span class="recent-item__meta">{{ $book->category->name ?? 'N/A' }}</span>
        </div>
        <span class="status {{ $statusClass }}">{{ $book->status }}</span>
    </a>
    @endforeach
</div>
@endif
@endsection