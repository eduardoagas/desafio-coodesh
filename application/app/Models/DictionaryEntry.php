<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DictionaryEntry extends Model
{
    use HasFactory;

    protected $fillable = ['word'];
}
