@extends('layouts.app')

@section('title', 'Danh sách sách')

@section('content')

<div class="actions-bar">
    <h1>Sách</h1>

    <a href="{{ route('books.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 5v14"/>
            <path d="M5 12h14"/>
        </svg>
        Thêm sách
    </a>
</div>


{{-- =========================
    FILTER
========================= --}}
<form method="GET"
      action="{{ route('books.index') }}"
      class="filter-bar">

    <input
        type="text"
        name="search"
        placeholder="Tìm theo tên sách"
        value="{{ request('search') }}"
    >

    <select name="category_id">
        <option value="">Tất cả thể loại</option>

        @foreach($categories as $category)
            <option
                value="{{ $category->id }}"
                {{ request('category_id') == $category->id ? 'selected' : '' }}
            >
                {{ $category->name }}
            </option>
        @endforeach
    </select>


    <select name="status">
        <option value="">Tất cả trạng thái</option>

        <option
            value="Want to Read"
            {{ request('status') == 'Want to Read' ? 'selected' : '' }}
        >
            Want to Read
        </option>

        <option
            value="Reading"
            {{ request('status') == 'Reading' ? 'selected' : '' }}
        >
            Reading
        </option>

        <option
            value="Read"
            {{ request('status') == 'Read' ? 'selected' : '' }}
        >
            Read
        </option>
    </select>


    <button type="submit" class="btn btn-filter">
        Lọc
    </button>

    <a
        href="{{ route('books.index') }}"
        class="btn btn-clear-filter"
    >
        Xóa lọc
    </a>

</form>


{{-- =========================
    BOOK TABLE
========================= --}}
<div class="table-card">

    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên sách</th>
                    <th>Tác giả</th>
                    <th>Thể loại</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>


            <tbody>

                @forelse($books as $book)

                    @php
                        $statusClass = match($book->status) {
                            'Reading' => 'status--reading',
                            'Read' => 'status--read',
                            default => 'status--want',
                        };
                    @endphp


                    <tr>

                        {{-- =========================
                            STT
                        ========================= --}}
                        <td data-label="STT">
                            {{ $books->firstItem() ? ($books->firstItem() + $loop->index) : $loop->iteration }}
                        </td>


                        {{-- =========================
                            TITLE
                        ========================= --}}
                        <td data-label="Tên">

                            <a
                                href="{{ route('books.show', $book) }}"
                                class="book-title"
                            >
                                {{ $book->title }}
                            </a>

                        </td>


                        {{-- =========================
                            AUTHOR
                        ========================= --}}
                        <td data-label="Tác giả">
                            {{ $book->author }}
                        </td>


                        {{-- =========================
                            CATEGORY
                        ========================= --}}
                        <td data-label="Thể loại">
                            {{ $book->category->name ?? 'Chưa phân loại' }}
                        </td>


                        {{-- =========================
                            STATUS
                        ========================= --}}
                        <td data-label="Trạng thái">

                            <span class="status {{ $statusClass }}">
                                {{ $book->status }}
                            </span>

                        </td>


                        {{-- =========================
                            ACTIONS
                        ========================= --}}
                        <td data-label="Thao tác">

                            <div class="row-actions">

                                {{-- SỬA --}}
                                <a
                                    href="{{ route('books.edit', $book) }}"
                                    class="icon-button"
                                    data-tooltip="Sửa"
                                    aria-label="Sửa"
                                >
                                    <svg
                                        viewBox="0 0 24 24"
                                        aria-hidden="true"
                                    >
                                        <path
                                            d="M4 20h4L19 9l-4-4L4 16v4Z"
                                        />

                                        <path
                                            d="m13.5 6.5 4 4"
                                        />
                                    </svg>
                                </a>


                                {{-- XÓA --}}
                                <form
                                    id="delete-book-{{ $book->id }}"
                                    action="{{ route('books.destroy', $book) }}"
                                    method="POST"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="button"
                                        class="icon-button icon-button--danger"
                                        data-tooltip="Xóa"
                                        aria-label="Xóa"
                                        onclick="openConfirmModal('Bạn có chắc muốn xóa cuốn sách &quot;{{ addslashes($book->title) }}&quot;?', 'delete-book-{{ $book->id }}', 'Xóa sách')"
                                    >

                                        <svg
                                            viewBox="0 0 24 24"
                                            aria-hidden="true"
                                        >
                                            <path d="M4 7h16"/>

                                            <path
                                                d="M9 7V4h6v3"
                                            />

                                            <path
                                                d="M7 7l1 13h8l1-13"
                                            />

                                            <path
                                                d="M10 11v5"
                                            />

                                            <path
                                                d="M14 11v5"
                                            />
                                        </svg>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="empty-state"
                        >
                            Không tìm thấy sách phù hợp.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

{{ $books->links('pagination.custom') }}

@endsection