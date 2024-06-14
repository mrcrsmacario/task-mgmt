<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tasks';
    protected $primaryKey = 't_id';
    protected $fillable = [
        't_user_id',
        't_title',
        't_content',
        't_status',
        't_file',
        't_is_published'
    ];
}
