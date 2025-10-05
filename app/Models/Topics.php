<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topics extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "subject_id",
    ];

    public function subject()
    {
        return $this->belongsTo(Subjects::class);
    }
}
