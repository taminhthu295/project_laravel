@extends('layouts.app')

@section('title', 'Danh sách thể loại')

@section('content')

<div class="actions-bar">

    <h1>Thể loại</h1>

    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 5v14"/>
            <path d="M5 12h14"/>
        </svg>
        Thêm thể loại
    </a>

</div>


<div class="table-card">

    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>Thể loại</th>
                    <th>Số lượng sách</th>
                    <th>Thao tác</th>
                </tr>
            </thead>

            <tbody>

                @forelse($categories as $category)

                    <tr>

                        {{-- TÊN THỂ LOẠI --}}
                        <td data-label="Thể loại">
                            <span class="book-title">
                                {{ $category->name }}
                            </span>
                        </td>


                        {{-- SỐ LƯỢNG SÁCH --}}
                        <td data-label="Số lượng sách">
                            <a
                                href="{{ route('books.index', ['category_id' => $category->id]) }}"
                                class="category-count-link"
                                title="Xem danh sách sách thuộc thể loại {{ $category->name }}"
                            >
                                {{ $category->books_count }}
                            </a>
                        </td>


                        {{-- THAO TÁC --}}
                        <td data-label="Thao tác">

                            <div class="row-actions">

                                {{-- SỬA --}}
                                <a
                                    href="{{ route('categories.edit', $category) }}"
                                    class="icon-button"
                                    data-tooltip="Sửa"
                                    aria-label="Sửa"
                                >
                                    <svg
                                        viewBox="0 0 24 24"
                                        aria-hidden="true"
                                    >
                                        <path d="M4 20h4L19 9l-4-4L4 16v4Z"/>
                                        <path d="m13.5 6.5 4 4"/>
                                    </svg>
                                </a>


                                {{-- XÓA --}}
                                <form
                                    action="{{ route('categories.destroy', $category) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="icon-button icon-button--danger"
                                        data-tooltip="Xóa"
                                        aria-label="Xóa"
                                        onclick="return confirm('Xóa thể loại này?')"
                                    >
                                        <svg
                                            viewBox="0 0 24 24"
                                            aria-hidden="true"
                                        >
                                            <path d="M4 7h16"/>
                                            <path d="M9 7V4h6v3"/>
                                            <path d="M7 7l1 13h8l1-13"/>
                                            <path d="M10 11v5"/>
                                            <path d="M14 11v5"/>
                                        </svg>
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="3"
                            class="empty-state"
                        >
                            Chưa có thể loại nào.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection