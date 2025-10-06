<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class questions extends Model
{
    use HasFactory;

    protected $fillable = [
        "content",
        "subject_id",
        "topic_id",
    ];

    public function Topic(){
        return $this->belongsTo(Topics::class);
    }
    public function Subject(){
        return $this->belongsTo(Subjects::class);
    }
}
