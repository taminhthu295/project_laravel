<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    // Danh sách các cột được phép gán dữ liệu hàng loạt (Mass Assignment)
    protected $fillable = [
        'title',
        'author',
        'category_id',
        'description',
        'published_year',
        'status',
        'image',
    ];

    // Thiết lập mối quan hệ N - 1: Một cuốn sách thuộc về một thể loại
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accessor: Tự động trả về đường dẫn ảnh hợp lệ (link online hoặc link storage cục bộ)
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }
}