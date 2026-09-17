# SYSTEM FLOW - HỆ THỐNG QUẢN LÝ THƯ VIỆN SÁCH (BOOK MANAGEMENT SYSTEM)

Tài liệu mô tả chi tiết kiến trúc, luồng hoạt động (System Flow), các chức năng và cơ sở dữ liệu của ứng dụng Laravel Quản lý Sách và Thể loại.

---

## 1. Tổng quan hệ thống (System Overview)

Hệ thống cung cấp giải pháp quản lý sách cá nhân/thư viện với 3 module cốt lõi:
- **Trang chủ / Bảng điều khiển (`Home Dashboard`)**: Thống kê số lượng sách theo từng trạng thái đọc (`Muốn đọc`, `Đang đọc`, `Đã đọc`), hỗ trợ click trực tiếp vào thẻ số để chuyển hướng sang danh sách sách đã lọc tự động; hiển thị 5 cuốn sách mới thêm gần đây; cung cấp các lối tắt thêm sách và thêm thể loại nhanh.
- **Quản lý Thể loại sách (`Category Management`)**: Phân loại sách theo danh mục, hiển thị số lượng sách theo thể loại và điều hướng nhanh sang danh sách đã lọc.
- **Quản lý Sách (`Book Management`)**: Quản lý thông tin sách, tác giả, năm xuất bản, trạng thái đọc (`Want to Read`, `Reading`, `Read`), tải lên và xem trước ảnh bìa sách, tìm kiếm và lọc đa điều kiện.

---

## 2. Kiến trúc luồng MVC (Architecture Flow)

Ứng dụng tuân theo mô hình chuẩn **MVC (Model - View - Controller)** của Laravel:

```mermaid
graph TD
    User([Người dùng / Trình duyệt]) -->|Gửi HTTP Request| Route[Routes: web.php]
    Route -->|Điều hướng| Controller[Controller: HomeController / BookController / CategoryController]
    Controller -->|Truy vấn / Thao tác dữ liệu| Model[Eloquent Model: Book / Category]
    Model <-->|Đọc / Ghi| DB[(Cơ sở dữ liệu: MySQL/SQLite)]
    Controller -->|Lưu trữ / Xóa file| Storage[(Storage: storage/app/public/books)]
    Controller -->|Truyền dữ liệu| View[Blade Views: resources/views]
    View -->|Render HTML + CSS| User
```

### 2.1. Minh họa luồng xử lý qua ví dụ "Thêm sách mới" (Add Book Flow)

> **Yêu cầu chuẩn:** `User → Form → Route → Controller → Model → Database → Response → View`

```mermaid
flowchart LR
    User([1. User]) -->|Nhập dữ liệu & Submit| Form[2. Form View]
    Form -->|Gửi POST request| Route[3. Route: web.php]
    Route -->|Điều hướng action| Controller[4. BookController]
    Controller -->|Khởi tạo & ORM| Model[5. Book Model]
    Model -->|Thực thi INSERT SQL| DB[(6. MySQL DB)]
    DB -->|Xác nhận ghi thành công| Controller
    Controller -->|Chuyển hướng kèm flash msg| Response[7. HTTP Response 302]
    Response -->|Render dữ liệu mới| View[8. View: books.index]
    View -->|Hiển thị kết quả trực quan| User
```

#### Bảng giải thích chi tiết vai trò của từng thành phần:

| STT | Thành phần | Tên file / Vị trí cụ thể | Vai trò & Trách nhiệm trong hệ thống |
| :---: | :--- | :--- | :--- |
| **1** | **User (Người dùng)** | Trình duyệt Client | Người dùng tương tác: nhập tiêu đề, tác giả, chọn thể loại, chọn file ảnh bìa và bấm nút *"Lưu sách"*. |
| **2** | **Form (View)** | [`resources/views/books/create.blade.php`](file:///c:/laragon/www/project_laravel/resources/views/books/create.blade.php) | Hiển thị giao diện nhập liệu, hỗ trợ xem trước ảnh (instant preview qua JS), đóng gói toàn bộ input vào `multipart/form-data`. |
| **3** | **Route** | [`routes/web.php`](file:///c:/laragon/www/project_laravel/routes/web.php) (`POST /books`) | Lắng nghe HTTP Request, kiểm tra URI & Method khớp với quy tắc định tuyến, chuyển tiếp request sang Controller xử lý. |
| **4** | **Controller** | [`BookController@store`](file:///c:/laragon/www/project_laravel/app/Http/Controllers/BookController.php) | Tiếp nhận request, thực thi validate dữ liệu, xử lý lưu file ảnh vào `storage/app/public/books`, gọi Model để tạo bản ghi. |
| **5** | **Model** | [`App\Models\Book`](file:///c:/laragon/www/project_laravel/app/Models/Book.php) | Đại diện cho thực thể dữ liệu, kiểm soát Mass Assignment (`$fillable`), định nghĩa mối quan hệ với `Category`. |
| **6** | **Database** | MySQL (bảng `books`) | Thực thi câu lệnh `INSERT INTO books (...) VALUES (...)` và lưu dữ liệu an toàn, bền vững trên ổ đĩa vật lý. |
| **7** | **Response** | `redirect()->route('books.index')` | Tạo phản hồi HTTP Redirect (302) đính kèm Flash Message thông báo thành công (`with('success', '...')`). |
| **8** | **View** | [`resources/views/books/index.blade.php`](file:///c:/laragon/www/project_laravel/resources/views/books/index.blade.php) | Nhận dữ liệu danh sách mới từ Controller, render giao diện HTML/CSS chứa cuốn sách vừa thêm kèm Toast popup thông báo thành công. |

---

## 3. Sơ đồ luồng hoạt động tổng thể (Overall System Flow)

```mermaid
flowchart TD
    Start([Truy cập hệ thống: /]) --> Home[Trang chủ Home Dashboard]
    
    %% Module Trang chủ (Home)
    Home -->|Click '+ Thêm sách'| BookCreate[Form Thêm Sách]
    Home -->|Click '+ Thêm thể loại'| CatCreate[Form Thêm Thể Loại]
    Home -->|Click thẻ 'Tổng số sách'| BookList[Trang Danh Sách Sách]
    Home -->|Click thẻ 'Muốn đọc'| FilterWant[Danh sách /books?status=Want+to+Read]
    Home -->|Click thẻ 'Đang đọc'| FilterReading[Danh sách /books?status=Reading]
    Home -->|Click thẻ 'Đã đọc'| FilterRead[Danh sách /books?status=Read]
    Home -->|Click item 'Mới thêm gần đây'| BookShow[Xem Chi Tiết Sách]
    
    FilterWant --> BookList
    FilterReading --> BookList
    FilterRead --> BookList

    %% Navigation Bar
    Nav{Thanh điều hướng} -->|Click 'Sách'| BookList
    Nav -->|Click 'Thể loại'| CatList[Trang Danh Sách Thể Loại]
    Nav -->|Click 'Thư viện sách'| Home

    %% Module Sách
    BookList --> FilterAction[Lọc theo Tên, Thể loại, Trạng thái]
    FilterAction --> BookList
    BookList -->|Click 'Thêm sách'| BookCreate
    BookCreate -->|Submit + Upload Ảnh| StoreBook[BookController@store]
    StoreBook --> BookList
    
    BookList -->|Click 'Tên sách'| BookShow
    BookShow -->|Có ảnh| Layout2Col[Layout 2 cột: Thông tin bên trái, Ảnh bên phải]
    BookShow -->|Không có ảnh| Layout1Col[Layout 1 cột: Thông tin trải rộng]
    
    BookList -->|Click icon 'Sửa'| BookEdit[Form Sửa Sách]
    BookEdit -->|Cập nhật + Thay ảnh mới| UpdateBook[BookController@update]
    UpdateBook --> BookList
    
    BookList -->|Click icon 'Xóa'| ConfirmModal[Modal Xác nhận Xóa tùy chỉnh]
    ConfirmModal -->|Xác nhận| DeleteBook[Xóa Sách + Xóa file ảnh]
    DeleteBook --> BookList

    %% Module Thể loại
    CatList -->|Click 'Thêm thể loại'| CatCreate
    CatCreate -->|Submit| StoreCat[CategoryController@store]
    StoreCat --> CatList
    
    CatList -->|Click vào 'Số lượng sách'| FilterByCat[Điều hướng sang /books?category_id=...]
    FilterByCat --> BookList
    
    CatList -->|Click icon 'Sửa'| CatEdit[Form Sửa Thể Loại]
    CatEdit -->|Submit| UpdateCat[CategoryController@update]
    UpdateCat --> CatList
    
    CatList -->|Click icon 'Xóa'| ConfirmModalCat[Modal Xác nhận Xóa tùy chỉnh]
    ConfirmModalCat -->|Xác nhận| DeleteCat[Xóa Thể Loại]
    DeleteCat --> CatList
```

---

## 4. Chi tiết các luồng chức năng (Functional Flows)

### 4.1. Luồng Trang chủ (Home Dashboard Flow)

#### A. Tổng quan màn hình Home (`GET /`)
1. Người dùng truy cập trang chủ `/` (Route name: `home`).
2. `HomeController@index` tổng hợp dữ liệu thống kê từ cơ sở dữ liệu:
   - `totalBooks`: Tổng số cuốn sách hiện có (`Book::count()`).
   - `wantCount`: Số sách ở trạng thái *Want to Read* (`Book::where('status', 'Want to Read')->count()`).
   - `readingCount`: Số sách ở trạng thái *Reading* (`Book::where('status', 'Reading')->count()`).
   - `readCount`: Số sách ở trạng thái *Read* (`Book::where('status', 'Read')->count()`).
   - `recentBooks`: 5 cuốn sách được thêm mới gần đây nhất kèm thông tin thể loại (`Book::with('category')->latest()->take(5)->get()`).
3. Render giao diện [`resources/views/home.blade.php`](file:///c:/laragon/www/project_laravel/resources/views/home.blade.php).

#### B. Các tính năng tương tác tại màn hình Home
- **Hero Actions**: 2 nút thao tác nhanh đồng bộ màu (`btn btn-primary`):
  - **`+ Thêm sách`**: Chuyển thẳng đến form thêm sách mới (`books.create`).
  - **`+ Thêm thể loại`**: Chuyển thẳng đến form thêm thể loại mới (`categories.create`).
- **Thẻ thống kê tương tác (Clickable Stats Cards)**:
  - Khi hover: Thẻ nổi lên mượt mà (`translateY(-3px)` + `box-shadow`) và chuyển màu số sang vàng kim.
  - Khi click vào **Tổng số sách**: Mở danh sách toàn bộ sách (`/books`).
  - Khi click vào **Muốn đọc**: Mở danh sách sách với bộ lọc tự động `status=Want to Read` (`/books?status=Want+to+Read`).
  - Khi click vào **Đang đọc**: Mở danh sách sách với bộ lọc tự động `status=Reading` (`/books?status=Reading`).
  - Khi click vào **Đã đọc**: Mở danh sách sách với bộ lọc tự động `status=Read` (`/books?status=Read`).
- **Mục "Mới thêm gần đây"**:
  - Liệt kê tối đa 5 cuốn sách mới nhất gồm ảnh thumbnail (hoặc icon sách mặc định), tên sách, thể loại và nhãn trạng thái đọc.
  - Nhấp vào bất kỳ cuốn sách nào sẽ mở ngay trang chi tiết cuốn sách đó (`books.show`).
  - Nếu chưa có cuốn sách nào, hiển thị ô trống kèm lời gợi ý thêm cuốn sách đầu tiên.

---

### 4.2. Luồng Quản lý Sách (Book Management)

#### A. Xem và Lọc danh sách sách (`GET /books`)
1. Người dùng truy cập `/books`.
2. `BookController@index` nhận request với các tham số tìm kiếm tùy chọn:
   - `search`: Tìm kiếm theo tiêu đề sách (`LIKE %keyword%`).
   - `category_id`: Lọc theo thể loại được chọn.
   - `status`: Lọc theo trạng thái đọc (`Want to Read`, `Reading`, `Read`).
3. Dữ liệu được sắp xếp theo mới nhất (`latest()`).
4. Giao diện hiển thị bảng gồm các cột:
   - **STT**: Đánh số thứ tự hiển thị trực quan (`$loop->iteration`).
   - **Tên sách**: Link dẫn tới trang chi tiết (`books.show`).
   - **Tác giả**, **Thể loại**, **Trạng thái**.
   - **Thao tác**: Icon **Sửa** (`books.edit`) và **Xóa** (`books.destroy`).

#### B. Thêm sách mới (`GET /books/create` & `POST /books`)
```mermaid
sequenceDiagram
    actor User as Người dùng
    participant V as View (books.create)
    participant C as BookController
    participant S as Storage Disk
    participant DB as Database

    User->>V: Mở form thêm sách
    V->>User: Hiển thị form (Tên, Tác giả, Thể loại, Năm XB, Trạng thái, Mô tả, Chọn ảnh)
    User->>V: Chọn file ảnh (JavaScript preview tức thì)
    User->>V: Nhấn nút "Lưu"
    V->>C: POST /books (dữ liệu + file ảnh)
    C->>C: Validate dữ liệu (Tên, Thể loại bắt buộc, Ảnh max 2MB...)
    alt Validate thất bại
        C-->>V: Trả về lỗi validate (@error)
    else Validate thành công
        opt Có file ảnh
            C->>S: Lưu ảnh vào thư mục public/books
            S-->>C: Trả về đường dẫn ảnh
        end
        C->>DB: Book::create($data)
        C-->>User: Redirect về /books + Thông báo thành công
    end
```

#### C. Xem chi tiết sách (`GET /books/{id}`)
- Nếu sách **có ảnh**: Hiển thị layout 2 cột linh hoạt (`detail-content`): bên trái là thông tin sách (Tác giả, Thể loại, Năm XB, Trạng thái, Mô tả được in đậm nhãn), bên phải là ảnh bìa sách (`detail-image`).
- Nếu sách **không có ảnh**: Kích hoạt lớp `.no-image`, hiển thị 1 cột trải dài tự nhiên.

#### D. Chỉnh sửa sách (`GET /books/{id}/edit` & `PUT /books/{id}`)
- Form tự động điền các thông tin hiện tại của cuốn sách.
- Hiển thị ảnh bìa hiện tại (nếu có).
- Cho phép người dùng chọn ảnh bìa mới:
  - Xem trước (preview) ngay trên giao diện.
  - Khi lưu, Controller tự động xóa file ảnh cũ khỏi `storage` và lưu file ảnh mới vào hệ thống.

#### E. Xóa sách (`DELETE /books/{id}`)
- Người dùng nhấn nút "Xóa" -> Popup trình duyệt xác nhận `confirm()`.
- Controller kiểm tra và xóa file ảnh liên kết trong `storage` (nếu có) trước khi xóa bản ghi trong cơ sở dữ liệu.

---

### 4.2. Luồng Quản lý Thể loại (Category Management)

```mermaid
sequenceDiagram
    actor User as Người dùng
    participant V as View (categories.index)
    participant C as CategoryController
    participant DB as Database

    User->>V: Truy cập /categories
    V->>C: GET /categories
    C->>DB: Category::withCount('books')->orderBy('name')->get()
    DB-->>C: Danh sách thể loại + Số lượng sách mỗi loại
    C-->>V: Render bảng danh sách thể loại
    User->>V: Bấm vào số lượng sách (ví dụ: '5')
    V->>User: Chuyển hướng tới /books?category_id={id} (Hiển thị 5 cuốn sách tương ứng)
```

---

### 4.3. Luồng Xử lý Media / Ảnh bìa sách (Media Storage Flow)

```mermaid
flowchart LR
    Input[File tải lên từ Client] --> Validation{Kiểm tra file: image, max 2MB}
    Validation -->|Hợp lệ| StoreStorage[Lưu vào storage/app/public/books]
    StoreStorage --> Symlink[Liên kết symbolic: public/storage/books]
    Symlink --> URLAsset[Truy xuất qua asset: storage/books/filename]
    Validation -->|Không hợp lệ| ReturnError[Trả về thông báo lỗi @error('image')]
```

---

## 5. Sơ đồ cơ sở dữ liệu (Database Schema & ERD)

```mermaid
erDiagram
    CATEGORIES ||--o{ BOOKS : "hasMany / belongsTo"
    
    CATEGORIES {
        bigint id PK "Tự tăng"
        string name "Tên thể loại"
        timestamp created_at
        timestamp updated_at
    }
    
    BOOKS {
        bigint id PK "Tự tăng"
        string title "Tên sách"
        string author "Tác giả"
        bigint category_id FK "Liên kết categories.id"
        text description "Mô tả cuốn sách (nullable)"
        integer published_year "Năm xuất bản (nullable)"
        string status "Want to Read | Reading | Read"
        string image "Đường dẫn file ảnh bìa (nullable)"
        timestamp created_at
        timestamp updated_at
    }
```

---

## 6. Danh mục Định tuyến (Routing Table)

| Phương thức HTTP | URI Path | Route Name | Action Controller | Mô tả |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/` | `home` | `HomeController@index` | Trang chủ Dashboard thống kê & mới thêm |
| `GET` | `/books` | `books.index` | `BookController@index` | Danh sách sách, tìm kiếm & lọc |
| `GET` | `/books/create` | `books.create` | `BookController@create` | Giao diện thêm sách mới |
| `POST` | `/books` | `books.store` | `BookController@store` | Lưu sách mới & upload ảnh |
| `GET` | `/books/{book}` | `books.show` | `BookController@show` | Xem thông tin chi tiết cuốn sách |
| `GET` | `/books/{book}/edit` | `books.edit` | `BookController@edit` | Giao diện chỉnh sửa sách |
| `PUT/PATCH` | `/books/{book}` | `books.update` | `BookController@update` | Cập nhật sách & thay đổi ảnh |
| `DELETE` | `/books/{book}` | `books.destroy` | `BookController@destroy` | Xóa sách & dọn dẹp ảnh storage |
| `GET` | `/categories` | `categories.index` | `CategoryController@index` | Danh sách thể loại & số lượng sách |
| `GET` | `/categories/create` | `categories.create` | `CategoryController@create` | Giao diện thêm thể loại mới |
| `POST` | `/categories` | `categories.store` | `CategoryController@store` | Lưu thể loại mới |
| `GET` | `/categories/{category}/edit` | `categories.edit` | `CategoryController@edit` | Giao diện sửa thể loại |
| `PUT/PATCH` | `/categories/{category}` | `categories.update` | `CategoryController@update` | Cập nhật tên thể loại |
| `DELETE` | `/categories/{category}` | `categories.destroy` | `CategoryController@destroy` | Xóa thể loại |

---

## 7. Tìm hiểu quy trình Deploy (Deploy Flow)

> Phần này mô tả lý thuyết luồng xử lý khi ứng dụng được triển khai lên môi trường production thực tế (chưa deploy thật, chỉ tìm hiểu và mô tả).

### 7.1. Sơ đồ luồng tổng quát

```mermaid
flowchart LR
    User([Người dùng]) -->|1. Nhập domain vào trình duyệt| Domain[example.com]
    Domain -->|2. Phân giải tên miền| DNS[(DNS Server)]
    DNS -->|3. Trả về IP của server| Domain
    Domain -->|4. Gửi HTTP/HTTPS request tới IP| Nginx[Nginx - Web Server]
    Nginx -->|5. Request PHP file .php| PHPFPM[PHP-FPM]
    PHPFPM -->|6. Thực thi code| Laravel[Laravel Application]
    Laravel -->|7. Query dữ liệu| MySQL[(MySQL Database)]
    MySQL -->|8. Trả kết quả| Laravel
    Laravel -->|9. Trả HTML/JSON| PHPFPM
    PHPFPM -->|10. Trả response| Nginx
    Nginx -->|11. Trả về trình duyệt| User
```

### 7.2. Giải thích từng bước

| Bước | Thành phần | Vai trò |
|---|---|---|
| 1 | **Domain** | Người dùng gõ tên miền (ví dụ `mybooks.com`) thay vì nhớ địa chỉ IP |
| 2-3 | **DNS Server** | Phân giải (resolve) tên miền thành địa chỉ IP thật của server đang host ứng dụng, trả IP đó về trình duyệt |
| 4 | **Nginx (Web Server)** | Nhận request HTTP/HTTPS đầu tiên tại địa chỉ IP đó. Nginx đóng vai trò "gác cổng" — xử lý các file tĩnh (CSS, JS, ảnh) trực tiếp, còn file `.php` thì chuyển tiếp (proxy) sang PHP-FPM |
| 5 | **Chuyển tiếp** | Nginx dùng giao thức FastCGI để gửi request sang PHP-FPM xử lý logic động |
| 6 | **PHP-FPM** | (FastCGI Process Manager) — quản lý các tiến trình PHP, nhận request từ Nginx và thực thi code Laravel tương ứng (routing, controller...) |
| 6 | **Laravel Application** | Nhận request đã qua PHP-FPM, chạy đúng luồng MVC nội bộ: Route → Controller → Model |
| 7-8 | **MySQL Database** | Laravel (qua Eloquent) truy vấn hoặc ghi dữ liệu vào MySQL, trả kết quả về lại Laravel |
| 9-10 | **Trả response** | Laravel render View (HTML) hoặc trả JSON, gửi ngược lại qua PHP-FPM → Nginx |
| 11 | **Hiển thị** | Nginx trả response cuối cùng về trình duyệt người dùng, hoàn tất 1 vòng request-response |

### 7.3. Vai trò cụ thể của từng thành phần trong hệ thống

- **DNS**: chỉ tham gia đúng 1 lần khi bắt đầu kết nối (phân giải tên miền), không tham gia vào các bước xử lý dữ liệu sau đó.
- **Nginx**: là lớp ngoài cùng tiếp nhận toàn bộ traffic, có thể xử lý luôn các asset tĩnh (`public/css/app.css`, ảnh bìa sách trong `public/storage`) mà không cần "làm phiền" tới PHP-FPM/Laravel, giúp giảm tải và tăng tốc độ phản hồi.
- **PHP-FPM**: là cầu nối giữa Nginx (web server) và code PHP (Laravel) — Nginx bản thân không tự chạy được code PHP, cần PHP-FPM làm trung gian thực thi.
- **Laravel**: xử lý toàn bộ logic nghiệp vụ — nhận request đã được định tuyến (`routes/web.php`), gọi đúng Controller (`BookController`, `CategoryController`), tương tác Model (Eloquent) để truy vấn MySQL.
- **MySQL**: lưu trữ toàn bộ dữ liệu bền vững của hệ thống (bảng `categories`, `books`).

### 7.4. Khác biệt so với môi trường local (Laragon) hiện tại

| | Local (Laragon) | Production (thực tế) |
|---|---|---|
| Web server | Apache/Nginx tích hợp sẵn trong Laragon | Nginx cấu hình riêng trên server |
| Domain | `localhost` / `project_laravel.test` | Tên miền thật đã trỏ DNS |
| PHP | Chạy qua `php artisan serve` (built-in server) hoặc Apache module | PHP-FPM chạy như 1 service riêng, tối ưu cho tải cao |
| Database | MySQL cài trong Laragon, chỉ máy local truy cập | MySQL server riêng (có thể cùng server hoặc server khác), có backup/bảo mật |
| HTTPS | Không bắt buộc | Bắt buộc, dùng chứng chỉ SSL (Let's Encrypt...) |