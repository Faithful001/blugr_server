<?php

namespace App\Models;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogModel extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'body', 'user_id'];

    public function user(){
        return $this->belongsTo(UserModel::class);
    }
}
