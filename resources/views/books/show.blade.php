<h1>{{ $book->title }}</h1>

<p><strong>Tác giả:</strong> {{ $book->author }}</p>
<p><strong>Thể loại:</strong> {{ $book->category->name ?? 'N/A' }}</p>
<p><strong>Mô tả:</strong> {{ $book->description }}</p>
<p><strong>Năm xuất bản:</strong> {{ $book->published_year }}</p>
<p><strong>Trạng thái:</strong> {{ $book->status }}</p>

<a href="{{ route('books.edit', $book) }}">Sửa</a>
<a href="{{ route('books.index') }}">Quay lại danh sách</a>