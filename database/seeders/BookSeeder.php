<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tạo các thể loại
        $catTieuThuyet = Category::firstOrCreate(['name' => 'Tiểu thuyết']);
        $catCongNghe = Category::firstOrCreate(['name' => 'Công nghệ thông tin']);
        $catKinhTe = Category::firstOrCreate(['name' => 'Kinh tế - Kinh doanh']);
        $catTamLy = Category::firstOrCreate(['name' => 'Tâm lý & Kỹ năng']);
        $catKhoaHoc = Category::firstOrCreate(['name' => 'Khoa học - Triết học']);
        $catLichSu = Category::firstOrCreate(['name' => 'Lịch sử']);

        // 2. Danh sách sách mẫu đa dạng
        $books = [
            [
                'title' => 'Nhà Giả Kim',
                'author' => 'Paulo Coelho',
                'category_id' => $catTieuThuyet->id,
                'published_year' => 1988,
                'status' => 'Read',
                'description' => 'Cuốn tiểu thuyết triết lý nổi tiếng theo chân cậu bé chăn cừu Santiago trong hành trình theo đuổi ước mơ và Vận mệnh của mình.',
            ],
            [
                'title' => 'Đắc Nhân Tâm',
                'author' => 'Dale Carnegie',
                'category_id' => $catTamLy->id,
                'published_year' => 1936,
                'status' => 'Read',
                'description' => 'Tác phẩm kinh điển về nghệ thuật ứng xử, giao tiếp và xây dựng các mối quan hệ tốt đẹp trong cuộc sống và công việc.',
            ],
            [
                'title' => 'Clean Code (Mã Sạch)',
                'author' => 'Robert C. Martin',
                'category_id' => $catCongNghe->id,
                'published_year' => 2008,
                'status' => 'Reading',
                'description' => 'Cẩm nang hướng dẫn phát triển phần mềm chuyên nghiệp, giúp lập trình viên viết mã dễ đọc, dễ bảo trì và mở rộng.',
            ],
            [
                'title' => 'Design Patterns: Elements of Reusable Object-Oriented Software',
                'author' => 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides',
                'category_id' => $catCongNghe->id,
                'published_year' => 1994,
                'status' => 'Want to Read',
                'description' => 'Tác phẩm kinh điển của nhóm Gang of Four (GoF) giới thiệu 23 mẫu thiết kế phần mềm hướng đối tượng.',
            ],
            [
                'title' => 'Những Người Khốn Khổ (Les Misérables)',
                'author' => 'Victor Hugo',
                'category_id' => $catTieuThuyet->id,
                'published_year' => 1862,
                'status' => 'Want to Read',
                'description' => 'Kiệt tác văn học Pháp thế kỷ 19 kể về cuộc đời nhân vật Jean Valjean cùng những thăng trầm xã hội nước Pháp.',
            ],
            [
                'title' => 'Tội Ác Và Trừng Phạt',
                'author' => 'Fyodor Dostoevsky',
                'category_id' => $catTieuThuyet->id,
                'published_year' => 1866,
                'status' => 'Read',
                'description' => 'Tiểu thuyết kinh điển của văn học Nga khai thác sâu sắc cuộc đấu tranh tâm lý và lương tâm của Raskolnikov.',
            ],
            [
                'title' => 'Sapiens: Lược Sử Loài Người',
                'author' => 'Yuval Noah Harari',
                'category_id' => $catLichSu->id,
                'published_year' => 2014,
                'status' => 'Read',
                'description' => 'Cái nhìn toàn diện về lịch sử tiến hóa và thống trị hành tinh của loài Homo sapiens từ thời đồ đá đến hiện đại.',
            ],
            [
                'title' => 'Homo Deus: Lược Sử Tương Lai',
                'author' => 'Yuval Noah Harari',
                'category_id' => $catLichSu->id,
                'published_year' => 2015,
                'status' => 'Reading',
                'description' => 'Dự báo những bước tiến tiếp theo của nhân loại với trí tuệ nhân tạo, công nghệ sinh học và khát vọng bất tử.',
            ],
            [
                'title' => 'Khởi Nghiệp Tinh Gọn (The Lean Startup)',
                'author' => 'Eric Ries',
                'category_id' => $catKinhTe->id,
                'published_year' => 2011,
                'status' => 'Read',
                'description' => 'Phương pháp xây dựng doanh nghiệp và phát triển sản phẩm nhanh chóng dựa trên mô hình Build - Measure - Learn.',
            ],
            [
                'title' => 'Cha Giàu Cha Nghèo',
                'author' => 'Robert Kiyosaki',
                'category_id' => $catKinhTe->id,
                'published_year' => 1997,
                'status' => 'Read',
                'description' => 'Cuốn sách vỡ lòng về tư duy tài chính cá nhân, đầu tư và sự khác biệt giữa tài sản và tiêu sản.',
            ],
            [
                'title' => 'Tư Duy Nhanh Và Chậm (Thinking, Fast and Slow)',
                'author' => 'Daniel Kahneman',
                'category_id' => $catTamLy->id,
                'published_year' => 2011,
                'status' => 'Reading',
                'description' => 'Khám phá hai hệ thống chi phối cách con người suy nghĩ và đưa ra quyết định: trực giác nhanh nhạy và lý trí chậm rãi.',
            ],
            [
                'title' => 'Lược Sử Thời Gian (A Brief History of Time)',
                'author' => 'Stephen Hawking',
                'category_id' => $catKhoaHoc->id,
                'published_year' => 1988,
                'status' => 'Read',
                'description' => 'Tác phẩm vật lý thiên văn phổ thông giải thích về Big Bang, hố đen, lý thuyết dây và bản chất của vũ trụ.',
            ],
            [
                'title' => 'Vũ Trụ Trong Vỏ Hạt Dẻ',
                'author' => 'Stephen Hawking',
                'category_id' => $catKhoaHoc->id,
                'published_year' => 2001,
                'status' => 'Want to Read',
                'description' => 'Phần tiếp theo của Lược Sử Thời Gian với nhiều minh họa sinh động về thuyết tương đối và cơ học lượng tử.',
            ],
            [
                'title' => 'Refactoring: Improving the Design of Existing Code',
                'author' => 'Martin Fowler',
                'category_id' => $catCongNghe->id,
                'published_year' => 1999,
                'status' => 'Reading',
                'description' => 'Nghệ thuật tái cấu trúc mã nguồn để cải thiện thiết kế bên trong mà không làm thay đổi hành vi bên ngoài.',
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'category_id' => $catTieuThuyet->id,
                'published_year' => 1949,
                'status' => 'Read',
                'description' => 'Tiểu thuyết phản địa đàng (dystopian) kinh điển cảnh báo về sự giám sát toàn trị và kiểm soát thông tin.',
            ],
            [
                'title' => 'Chuyện Ở Nông Trại (Animal Farm)',
                'author' => 'George Orwell',
                'category_id' => $catTieuThuyet->id,
                'published_year' => 1945,
                'status' => 'Read',
                'description' => 'Truyện ngụ ngôn chính trị sâu sắc về cuộc nổi dậy của các loài động vật trong nông trại.',
            ],
            [
                'title' => 'Hoàng Tử Bé (Le Petit Prince)',
                'author' => 'Antoine de Saint-Exupéry',
                'category_id' => $catTieuThuyet->id,
                'published_year' => 1943,
                'status' => 'Read',
                'description' => 'Câu chuyện cảm động mang tính biểu tượng về tình yêu, tình bạn và cách người lớn nhìn nhận thế giới.',
            ],
            [
                'title' => 'Tuổi Trẻ Đáng Giá Bao Nhiêu',
                'author' => 'Rosie Nguyễn',
                'category_id' => $catTamLy->id,
                'published_year' => 2016,
                'status' => 'Read',
                'description' => 'Cuốn sách truyền cảm hứng cho người trẻ về việc đọc sách, học hỏi, đi trải nghiệm và tìm kiếm đam mê.',
            ],
            [
                'title' => 'Dế Mèn Phiêu Lưu Ký',
                'author' => 'Tô Hoài',
                'category_id' => $catTieuThuyet->id,
                'published_year' => 1941,
                'status' => 'Read',
                'description' => 'Tác phẩm văn học thiếu nhi Việt Nam kinh điển ghi lại những chuyến phiêu lưu kỳ thú và bài học trưởng thành của Dế Mèn.',
            ],
            [
                'title' => 'Số Đỏ',
                'author' => 'Vũ Trọng Phụng',
                'category_id' => $catTieuThuyet->id,
                'published_year' => 1936,
                'status' => 'Read',
                'description' => 'Tiểu thuyết trào phúng đỉnh cao châm biếm xã hội thành thị Việt Nam thời kỳ Âu hóa qua nhân vật Xuân Tóc Đỏ.',
            ],
            [
                'title' => 'Mắt Biếc',
                'author' => 'Nguyễn Nhật Ánh',
                'category_id' => $catTieuThuyet->id,
                'published_year' => 1990,
                'status' => 'Read',
                'description' => 'Câu chuyện tình đơn phương da diết và đầy hoài niệm của Ngạn dành cho cô bạn thanh mai trúc mã Hà Lan.',
            ],
            [
                'title' => 'Cho Tôi Xin Một Vé Đi Tuổi Thơ',
                'author' => 'Nguyễn Nhật Ánh',
                'category_id' => $catTieuThuyet->id,
                'published_year' => 2008,
                'status' => 'Read',
                'description' => 'Tấm vé đưa người đọc quay trở lại với những ký ức tuổi thơ trong trẻo, hồn nhiên và đầy ắp tiếng cười.',
            ],
            [
                'title' => 'Sổ Tay Ghi Chép Cá Nhân (Chưa Phân Loại)',
                'author' => 'Ẩn Danh',
                'category_id' => null, // Test trường hợp sách không có thể loại
                'published_year' => 2024,
                'status' => 'Want to Read',
                'description' => 'Bản thảo tài liệu tự do, chưa được gán thể loại cụ thể để kiểm tra tính năng Chưa phân loại trên hệ thống.',
            ],
            [
                'title' => 'The Pragmatic Programmer',
                'author' => 'Andrew Hunt, David Thomas',
                'category_id' => $catCongNghe->id,
                'published_year' => 1999,
                'status' => 'Want to Read',
                'description' => 'Những bài học thực tế, lời khuyên quý giá và thói quen cốt lõi để trở thành một lập trình viên thực thụ.',
            ],
            [
                'title' => 'Bắt Trẻ Đồng Xanh (The Catcher in the Rye)',
                'author' => 'J.D. Salinger',
                'category_id' => $catTieuThuyet->id,
                'published_year' => 1951,
                'status' => 'Read',
                'description' => 'Tiểu thuyết khắc họa tâm lý nổi loạn, cô độc và những trăn trở về sự giả tạo trong xã hội của thiếu niên Holden Caulfield.',
            ],
        ];

        foreach ($books as $bookData) {
            Book::firstOrCreate(
                ['title' => $bookData['title']],
                $bookData
            );
        }
    }
}
