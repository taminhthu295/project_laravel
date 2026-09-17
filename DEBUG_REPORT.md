# Báo Cáo & Tài Liệu Debug Hệ Thống (Debugging & Fixes Documentation)

Tài liệu này tổng hợp toàn bộ quá trình rà soát, phát hiện lỗi, phân tích nguyên nhân gốc rễ (Root Cause), và các giải pháp đã được thực hiện trên mã nguồn dự án **Quản lý Sách (Book Management System)**.

---

## Mục Lục
1. [Tổng Quan Hiện Trạng & Bảng Lỗi](#1-tổng-quan-hiện-trạng--bảng-lỗi)
2. [Chi Tiết Các Bug Đã Sửa](#2-chi-tiết-các-bug-đã-sửa)
   - [Bug 1: Lỗi Ảnh Bìa Không Hiển Thị (Thiếu Storage Link)](#bug-1-lỗi-ảnh-bìa-không-hiển-thị-thiếu-storage-link)
   - [Bug 2: Lỗi Crash SQL Khi Nhập Năm Xuất Bản (Xung Đột Kiểu YEAR)](#bug-2-lỗi-crash-sql-khi-nhập-năm-xuất-bản-xung-đột-kiểu-year)
   - [Bug 3: Rủi Ro Mất Dữ Liệu Sách & File Rác Khi Xóa Thể Loại (Cascade Delete)](#bug-3-rủi-ro-mất-dữ-liệu-sách--file-rác-khi-xóa-thể-loại-cascade-delete)
   - [Bug 4: Layout Bị Khuyết Thông Báo Lỗi (`session('error')`)](#bug-4-layout-bị-khuyết-thông-báo-lỗi-sessionerror)
3. [Chi Tiết Các Tính Năng Cải Tiến (Enhancements)](#3-chi-tiết-các-tính-năng-cải-tiến-enhancements)
   - [Cải tiến 1: Chống Trùng Lặp Tên Thể Loại (Unique Category Validation)](#cải-tiến-1-chống-trùng-lặp-tên-thể-loại-unique-category-validation)
   - [Cải tiến 2: Bổ Sung Tùy Chọn Gỡ Bỏ Ảnh Bìa Sách Cũ](#cải-tiến-2-bổ-sung-tùy-chọn-gỡ-bỏ-ảnh-bìa-sách-cũ)
   - [Cải tiến 3: Phân Trang (Pagination), Đánh Số STT Chuẩn & Khắc Phục N+1 Query](#cải-tiến-3-phân-trang-pagination-đánh-số-stt-chuẩn--khắc-phục-n1-query)
4. [Checklist Kiểm Thử Hệ Thống (Verification Checklist)](#4-checklist-kiểm-thử-hệ-thống-verification-checklist)

---

## 1. Tổng Quan Hiện Trạng & Bảng Lỗi

| Mã | Hạng mục | Mức độ | Trạng thái | Tóm tắt giải pháp |
| :--- | :--- | :---: | :---: | :--- |
| **BUG-01** | Lỗi hiển thị ảnh tải lên (404 Not Found) | **Nghiêm trọng (Critical)** |  Đã xử lý | Tạo liên kết symlink `public/storage` qua lệnh `artisan storage:link`. |
| **BUG-02** | Sập trang MySQL khi lưu năm xuất bản cổ | **Nghiêm trọng (Critical)** |  Đã xử lý | Migrate đổi cột `published_year` sang `integer`, update validation `min:1`. |
| **BUG-03** | Xóa danh mục làm mất sạch sách (Cascade) | **Cao (High)** |  Đã xử lý | Chuyển `category_id` sang `nullable()`, đổi ràng buộc sang `nullOnDelete()`. |
| **BUG-04** | Layout thiếu hiển thị `session('error')` | **Trung bình (Medium)** |  Đã xử lý | Bổ sung container bắt `session('error')` và CSS `.alert--error`. |
| **ENH-01** | Trùng lặp tên danh mục thể loại | **Trung bình (Medium)** |  Đã xử lý | Thêm validation rule `unique:categories,name` kèm custom message tiếng Việt. |
| **ENH-02** | Không có cách gỡ ảnh sách khi cập nhật | **Thấp (Enhancement)** |  Đã xử lý | Thêm checkbox "Xóa ảnh bìa hiện tại", dọn dẹp file vật lý khi update. |
| **ENH-03** | Thiếu phân trang & N+1 query | **Hiệu năng (Performance)** |  Đã xử lý | Đổi sang `paginate(10)`, tạo view phân trang riêng, eager load category. |

---

## 2. Chi Tiết Các Bug Đã Sửa

### BUG-1: Lỗi Ảnh Bìa Không Hiển Thị (Thiếu Storage Link)

* **Issue (Hiện tượng):**
  Khi người dùng tải ảnh bìa mới trong form thêm/sửa sách, file ảnh được lưu vào máy chủ nhưng trên trang chi tiết ([books/show.blade.php](resources/views/books/show.blade.php)) và form sửa ([books/edit.blade.php](resources/views/books/edit.blade.php)), ảnh bị vỡ (biểu tượng 404 Not Found).
* **Nguyên nhân:**
  * Trong [BookController.php](app/Http/Controllers/BookController.php), ảnh được lưu vào disk `public` (`storage/app/public/books/`).
  * Trong view, hàm `asset('storage/' . $book->image)` trỏ tới đường dẫn tĩnh `public/storage/...`.
  * Thư mục symlink `public/storage` chưa từng được khởi tạo trong môi trường chạy (Laragon/Windows).
* **Cách điều tra:**
  1. Mở DevTools trình duyệt (F12) ➔ Chuyển qua tab **Network**, thấy request tải ảnh trả về mã HTTP `404 Not Found`.
  2. Kiểm tra thư mục máy chủ: file vật lý có tồn tại tại `storage/app/public/books/`.
  3. Kiểm tra thư mục gốc `public/` thấy chưa có thư mục symlink `storage/` kết nối ra ngoài.
* **Cách xử lý:**
  Khởi chạy lệnh Artisan để tạo symlink kết nối từ `storage/app/public` ra thư mục web công khai `public/storage`:
  ```bash
  php artisan storage:link
  ```
* **Kết quả:** Symlink được tạo lập thành công. Tất cả ảnh upload truy cập và hiển thị bình thường trên mọi view.

### BUG-1A: Ảnh Sách Trên Trang Home Không Hiển Thị Do Dùng Đường Dẫn Sai

* **Hiện tượng:**
  Trên trang chủ [resources/views/home.blade.php](resources/views/home.blade.php), các item sách hiển thị ô trống hoặc hình ảnh vỡ dù dữ liệu `image` có tồn tại.
* **Nguyên nhân gốc rễ (Root Cause):**
  * Trang home đang render ảnh bằng `asset('storage/' . $book->image)` thay vì dùng accessor chuẩn `image_url` của model.
  * Với dữ liệu ảnh từ URL ngoài internet, cách nối chuỗi này không hợp lệ; nếu giá trị lưu trong DB đã là URL tuyệt đối thì `asset('storage/https://...')` sinh ra đường dẫn sai.
  * Đồng thời, ảnh upload local cũng phụ thuộc vào điều kiện đường dẫn `public/storage` đã đúng và vững chắc.
* **Giải pháp đã thực hiện:**
  1. Cập nhật view [resources/views/home.blade.php](resources/views/home.blade.php) để dùng `@if($book->image_url)` và `src="{{ $book->image_url }}"`.
  2. Đảm bảo model [app/Models/Book.php](app/Models/Book.php) đã có accessor `getImageUrlAttribute()` xử lý cả file nội bộ và URL ngoài.
  3. Xoá cache Blade/Config sau khi sửa bằng lệnh:
     ```bash
     php artisan view:clear
     php artisan config:clear
     ```
* **Kết quả:** Ảnh trên trang home hiển thị đúng cả ảnh upload nội bộ lẫn ảnh từ URL bên ngoài, không còn mất dạng hình ảnh trên card sách mới gần đây.


---

### BUG-2: Lỗi Crash SQL Khi Nhập Năm Xuất Bản (Xung Đột Kiểu YEAR)

* **Issue (Hiện tượng):**
  Khi thêm một tác phẩm văn học cổ điển có năm xuất bản trước thế kỷ 20 (ví dụ: *Les Misérables* - 1862, *Pride and Prejudice* - 1813, hoặc các tác phẩm cổ đại), hệ thống ném ra ngoại lệ SQL nghiêm trọng:
  ```text
  SQLSTATE[22003]: Numeric value out of range: 1264 Out of range value for column 'published_year' at row 1
  ```
* **Nguyên nhân:**
  * Migration ban đầu `create_books_table.php` định nghĩa: `$table->year('published_year')`.
  * Trong MySQL, kiểu dữ liệu `YEAR` **chỉ hỗ trợ giá trị từ 1901 đến 2155** (hoặc `0000`).
  * Validation trong `BookController` chỉ kiểm tra `'nullable|digits:4|integer'`, cho phép vượt qua với mọi số có 4 chữ số, dẫn đến việc MySQL từ chối ghi dữ liệu và sập trang.
* **Cách điều tra:**
  1. Thử nhập một năm xuất bản hợp lệ trong lịch sử (1862) vào form thêm sách và submit.
  2. Trang báo lỗi màu đỏ (Laravel Error Page); mở file log tại `storage/logs/laravel.log` phát hiện lỗi `SQLSTATE[22003]: Numeric value out of range: 1264`.
  3. Kiểm tra định nghĩa cột trong database qua lệnh `DESCRIBE books;` ➔ Cột `published_year` có kiểu `year(4)`. Tra cứu tài liệu MySQL xác nhận giới hạn năm của kiểu `YEAR`.
* **Cách xử lý:**
  1. Tạo và chạy migration [2026_09_04_090727_change_published_year_in_books_table.php](database/migrations/2026_09_04_090727_change_published_year_in_books_table.php):
     ```php
     Schema::table('books', function (Blueprint $table) {
         $table->integer('published_year')->nullable()->change();
     });
     ```
  2. Cập nhật validation tại `BookController::store()` và `BookController::update()`:
     ```php
     'published_year' => 'nullable|integer|min:1|max:' . (date('Y') + 1),
     ```
  3. Bổ sung ràng buộc `min="1"` và `max="{{ date('Y') + 1 }}"` cho thẻ `<input type="number">` tại [create.blade.php](resources/views/books/create.blade.php) và [edit.blade.php](resources/views/books/edit.blade.php).
* **Kết quả:** Lưu được mọi năm xuất bản từ thời cổ đại đến năm tương lai gần mà không xảy ra lỗi cơ sở dữ liệu.

---

### BUG-3: Rủi Ro Mất Dữ Liệu Sách & File Rác Khi Xóa Thể Loại (Cascade Delete)

* **Issue (Hiện tượng):**
  * Khóa ngoại ban đầu được thiết lập `->onDelete('cascade')`.
  * Khi người dùng xóa một thể loại trong [CategoryController.php](app/Http/Controllers/CategoryController.php), **toàn bộ sách thuộc thể loại đó bị xóa sạch vĩnh viễn khỏi database**.
  * Quá trình xóa này do MySQL cascade tự động thực hiện, không qua `BookController::destroy()`, khiến toàn bộ file ảnh bìa vật lý của những cuốn sách bị xóa **vẫn nằm lại trên ổ đĩa**, gây rác bộ nhớ server.
* **Nguyên nhân:**
  Ràng buộc khóa ngoại `category_id` trong migration cũ dùng `cascadeOnDelete()`, kết hợp với cột `category_id` không cho phép giá trị `NULL`.
* **Cách điều tra:**
  1. Tạo 1 thể loại "Công nghệ" và thêm 3 cuốn sách thuộc thể loại này (có đính kèm ảnh bìa).
  2. Thực hiện xóa thể loại "Công nghệ" trên màn hình quản lý thể loại.
  3. Kiểm tra bảng `books` trong MySQL: 3 cuốn sách đã biến mất hoàn toàn.
  4. Kiểm tra thư mục `storage/app/public/books/`: các file ảnh bìa của 3 cuốn sách vẫn tồn tại, không được dọn dẹp.
* **Cách xử lý:**
  Chuyển trường Thể loại (`category_id`) sang dạng **Tùy chọn (`nullable`)** với hành vi **`nullOnDelete()`**. Khi thể loại bị xóa, sách vẫn được giữ lại an toàn và tự động chuyển về thể loại *"Chưa phân loại"*:
  1. Tạo và chạy migration [2026_09_04_091419_make_category_id_nullable_in_books_table.php](database/migrations/2026_09_04_091419_make_category_id_nullable_in_books_table.php):
     ```php
     Schema::table('books', function (Blueprint $table) {
         $table->dropForeign(['category_id']);
         $table->unsignedBigInteger('category_id')->nullable()->change();
         $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
     });
     ```
  2. Cập nhật validation trong [BookController.php](app/Http/Controllers/BookController.php):
     ```php
     'category_id' => 'nullable|exists:categories,id',
     ```
  3. Cập nhật giao diện:
     * Dropdown chọn thể loại đổi option mặc định: `-- Chọn thể loại (Tùy chọn) --`.
     * Cột thể loại tại [books/index.blade.php](resources/views/books/index.blade.php) và [books/show.blade.php](resources/views/books/show.blade.php) hiển thị nhãn thân thiện: `Chưa phân loại` thay vì `N/A`.
* **Kết quả:** Người dùng có thể thêm sách ngay cả khi chưa tạo thể loại; khi xóa danh mục, dữ liệu sách hoàn toàn được bảo toàn và chuyển về "Chưa phân loại".

---

### BUG-4: Layout Bị Khuyết Thông Báo Lỗi (`session('error')`)

* **Issue (Hiện tượng):**
  Khi Controller điều hướng quay lại kèm thông báo thất bại: `redirect()->back()->with('error', 'Nội dung lỗi...')`, giao diện hoàn toàn không có phản hồi nào, người dùng không hiểu thao tác có thành công hay không.
* **Nguyên nhân:**
  Trong [layouts/app.blade.php](resources/views/layouts/app.blade.php), giao diện chỉ viết code kiểm tra `@if(session('success'))`, chưa có khối bắt `@if(session('error'))`.
* **Cách điều tra:**
  1. Thử kích hoạt một luồng trả về lỗi với `with('error', '...')` trong Controller.
  2. Quan sát màn hình sau khi redirect: trang tải lại bình thường nhưng thanh thông báo hoàn toàn trống.
  3. Kiểm tra mã nguồn Blade tại `resources/views/layouts/app.blade.php` ➔ Phát hiện thiếu khối directive bắt `session('error')`.
* **Cách xử lý:**
  1. Bổ sung container hiển thị `session('error')` trong [layouts/app.blade.php](resources/views/layouts/app.blade.php):
     ```blade
     @if(session('success'))
         <div class="alert alert--success">{{ session('success') }}</div>
     @endif

     @if(session('error'))
         <div class="alert alert--error">{{ session('error') }}</div>
     @endif
     ```
  2. Thêm class định kiểu trong [public/css/app.css](public/css/app.css):
     ```css
     .alert--success {
         border-left-color: var(--ink);
         color: var(--ink);
     }
     .alert--error,
     .alert--danger {
         border-left-color: var(--rust);
         color: var(--rust);
     }
     ```
* **Kết quả:** Mọi thông báo lỗi từ session đều hiển thị nổi bật với viền đỏ gạch (`var(--rust)`).

---

## 3. Chi Tiết Các Tính Năng Cải Tiến (Enhancements)

### Cải tiến 1: Chống Trùng Lặp Tên Thể Loại (Unique Category Validation)

* **Vấn đề:** Trước đây [CategoryController.php](app/Http/Controllers/CategoryController.php) chỉ kiểm tra `required|string|max:255`. Người dùng có thể vô tình tạo nhiều thể loại cùng tên gây nhiễu dữ liệu.
* **Xử lý:**
  * Hàm `store()`:
    ```php
    $request->validate([
        'name' => 'required|string|max:255|unique:categories,name',
    ], [
        'name.unique' => 'Tên thể loại này đã tồn tại.',
    ]);
    ```
  * Hàm `update()`:
    ```php
    $request->validate([
        'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
    ], [
        'name.unique' => 'Tên thể loại này đã tồn tại.',
    ]);
    ```

---

### Cải tiến 2: Bổ Sung Tùy Chọn Gỡ Bỏ Ảnh Bìa Sách Cũ

* **Vấn đề:** Ở form sửa sách ([books/edit.blade.php](resources/views/books/edit.blade.php)), người dùng chỉ có thể giữ ảnh cũ hoặc upload ảnh mới đè lên, không có tùy chọn gỡ bỏ hoàn toàn ảnh bìa sách.
* **Xử lý:**
  1. Trong [books/edit.blade.php](resources/views/books/edit.blade.php): Thêm checkbox "Xóa ảnh bìa hiện tại" khi `$book->image` tồn tại:
     ```blade
     <div class="image-remove-option" style="margin-top: 10px;">
         <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; cursor: pointer; color: var(--rust);">
             <input type="checkbox" name="remove_image" value="1" id="remove_image">
             <span>Xóa ảnh bìa hiện tại</span>
         </label>
     </div>
     ```
  2. Trong [BookController.php](app/Http/Controllers/BookController.php):
     ```php
     $data = $request->except(['image', 'remove_image']);

     if ($request->hasFile('image')) {
         if ($book->image) {
             Storage::disk('public')->delete($book->image);
         }
         $data['image'] = $request->file('image')->store('books', 'public');
     } elseif ($request->boolean('remove_image')) {
         if ($book->image) {
             Storage::disk('public')->delete($book->image);
         }
         $data['image'] = null;
     }

     $book->update($data);
     ```

---

### Cải tiến 3: Phân Trang (Pagination), Đánh Số STT Chuẩn & Khắc Phục N+1 Query

* **Vấn đề:**
  * `BookController@index` gọi `$books = $query->latest()->get();` lấy toàn bộ sách ra cùng lúc, gây quá tải nếu dữ liệu lớn.
  * Khi phân trang bằng template mặc định của Laravel, do project dùng vanilla CSS nên các icon SVG của Tailwind sẽ bị phóng to toàn màn hình.
  * Cột STT nếu dùng `$loop->iteration` sẽ bị reset về 1 mỗi khi chuyển sang trang 2, 3...
  * Tại `BookController@show`, gọi `$book->category->name` sinh ra truy vấn riêng (N+1 nhẹ).
* **Xử lý:**
  1. **Controller:** 
     * Phân trang 10 mục/trang kèm giữ query string: `$query->latest()->paginate(10)->withQueryString();`.
     * Eager loading tại `show()`: `$book->load('category');`.
  2. **View phân trang tùy biến:**
     Tạo [resources/views/pagination/custom.blade.php](resources/views/pagination/custom.blade.php) với đầy đủ thông tin:
     `Hiển thị X - Y trong tổng số Z cuốn sách` và các nút Trước, Số trang, Sau.
  3. **CSS:** Thêm định dạng đồng bộ typography (`Lora`, `Inter`, tông màu `--ink`, `--surface`, `--line`) tại [public/css/app.css](public/css/app.css).
  4. **STT lũy tiến:** Sửa cột STT tại [books/index.blade.php](resources/views/books/index.blade.php):
     ```blade
     {{ $books->firstItem() ? ($books->firstItem() + $loop->index) : $loop->iteration }}
     ```

---

### Cải tiến 4: Hỗ Trợ Chèn Ảnh Linh Hoạt (Upload File & Dán Link URL)

* **Vấn đề & Nhu cầu:** Người dùng muốn có thể copy-paste link ảnh trực tiếp từ internet (Goodreads, Tiki, Amazon...) thay vì phải tải ảnh về máy rồi upload.
* **Xử lý:**
  1. **Database:** Tạo migration [2026_09_04_101130_change_image_column_in_books_table.php](database/migrations/2026_09_04_101130_change_image_column_in_books_table.php) chuyển kiểu cột `image` sang `TEXT` để lưu trữ an toàn các URL dài mà không sợ lỗi vượt quá 255 ký tự.
  2. **Model:** Bổ sung Accessor `image_url` tại [app/Models/Book.php](app/Models/Book.php):
     * Nếu bắt đầu bằng `http://` hoặc `https://`: trả về trực tiếp URL.
     * Nếu là file upload nội bộ: trả về `asset('storage/' . $this->image)`.
  3. **Controller:** Cập nhật `BookController` tại `store()`, `update()`, và `destroy()`:
     * Chấp nhận cả trường `image` (file) và `image_url` (link URL hợp lệ).
     * Phân biệt file nội bộ và URL bên ngoài để chỉ xóa file vật lý trên đĩa khi đó là file nội bộ (tránh lỗi khi xóa sách có ảnh URL).
  4. **Giao diện & Xem trước tức thì:**
     * Cập nhật [create.blade.php](resources/views/books/create.blade.php) và [edit.blade.php](resources/views/books/edit.blade.php) với 2 tùy chọn rõ ràng phân cách bằng chữ "HOẶC".
     * JavaScript hỗ trợ xem trước (instant preview) cho cả 2 nguồn: file từ máy tính (qua `FileReader`) hoặc link dán vào (qua sự kiện `oninput`), tự động bắt lỗi nếu link ảnh bị hỏng (`onerror`).

---

### Cải tiến 5: Bổ Sung Nút Xóa Sách Trực Tiếp Trong Form Sửa Sách

* **Vấn đề & Nhu cầu:** Khi người dùng đang ở trang chỉnh sửa chi tiết sách ([books/edit.blade.php](resources/views/books/edit.blade.php)), nếu quyết định xóa cuốn sách đó thì phải quay lại trang danh sách, tìm đúng dòng sách rồi mới bấm xóa.
* **Xử lý:**
  * Bổ sung nút **"Xóa sách này"** (màu đỏ gạch `.btn-danger`, có icon thùng rác và hộp thoại xác nhận `confirm()`) ngay trong cụm nút bấm tại [books/edit.blade.php](resources/views/books/edit.blade.php).
  * Sử dụng thuộc tính chuẩn `form="delete-book-form"` của HTML5 liên kết tới một form ẩn riêng biệt có `@method('DELETE')` bên ngoài form chính, đảm bảo không vi phạm quy tắc lồng thẻ `<form>` trong chuẩn HTML.

---

## 4. Checklist Kiểm Thử Hệ Thống (Verification Checklist)

| STT | Kịch bản kiểm thử | Kết quả mong đợi | Trạng thái |
| :---: | :--- | :--- | :---: |
| 1 | Thêm sách mới có tải ảnh bìa | Ảnh hiển thị đúng ở trang danh sách, chi tiết và form sửa; không lỗi 404 |  Đạt |
| 2 | Thêm sách có năm xuất bản < 1901 (vd: 1850) | Form lưu thành công, DB ghi nhận chính xác, không phát sinh lỗi MySQL |  Đạt |
| 3 | Thêm sách nhưng không chọn Thể loại | Lưu thành công; hiển thị "Chưa phân loại" trên bảng và chi tiết |  Đạt |
| 4 | Xóa một Thể loại đang chứa nhiều sách | Thể loại bị xóa; toàn bộ sách vẫn nguyên vẹn và chuyển sang "Chưa phân loại" |  Đạt |
| 5 | Tạo thể loại trùng tên đã có | Form từ chối, hiển thị lỗi "Tên thể loại này đã tồn tại" |  Đạt |
| 6 | Sửa sách và tick chọn "Xóa ảnh bìa hiện tại" | Ảnh bìa bị xóa khỏi ổ đĩa server; sách chuyển về trạng thái không có ảnh |  Đạt |
| 7 | Chuyển qua các trang phân trang (Trang 1 -> 2) | Bộ lọc tìm kiếm được giữ nguyên; số STT nối tiếp chính xác (11, 12...) |  Đạt |
| 8 | Thêm / Sửa sách bằng dán link URL ảnh | Ảnh xem trước tức thì, lưu thành công, hiển thị chuẩn ở trang chi tiết |  Đạt |
| 9 | Xóa sách trực tiếp ngay trên Form sửa sách | Hộp thoại xác nhận hiện ra; xác nhận xóa thành công và điều hướng về trang chủ kèm flash message |  Đạt |
| 10 | Biên dịch kiểm tra toàn bộ Blade & PHP | `artisan view:cache` & `php -l` không phát hiện bất kỳ lỗi cú pháp nào |  Đạt |

