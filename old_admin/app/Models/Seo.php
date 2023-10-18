<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use HasFactory;

    protected $table = 'seo';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'meta_description',
        'meta_keywords',
    ];
}