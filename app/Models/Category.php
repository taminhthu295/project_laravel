<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Danh sách cột được phép gán dữ liệu hàng loạt
    protected $fillable = ['name'];

    // Thiết lập mối quan hệ 1 - N: Một thể loại có nhiều sách
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
