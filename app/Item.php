<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;


class Item extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'category_id',
        'code',
        'name'
    ];

    protected $dates = ['deleted_at'];

    public function category(){
        return $this->belongsTo(Category::class)->withTrashed();
    }
}
