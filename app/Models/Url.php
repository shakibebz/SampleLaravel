<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;


    protected $table = 'urls';
    protected $connection = 'mysql';


    protected $fillable = [
        'original_url',
        'short_code',
    ];

    protected $casts = [
        'clicks' => 'integer',
    ];
}
