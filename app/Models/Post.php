<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, Uuid;

    protected $guarded = ['id'];

    protected $with = ['category', 'author'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
