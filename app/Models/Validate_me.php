<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validate_me extends Model
{
    use HasFactory;
    protected $table = 'validate_me';
    protected $primarykey = 'id';
    public $timestamps = FALSE;
    protected $fillable = ['id','name', 'year'];
}
