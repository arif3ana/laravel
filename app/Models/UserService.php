<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserService extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_module_id',
        'service_category_id',
        'stage',
        'due_date',
        'status'
    ];

    protected $with = ['invoice'];
}
