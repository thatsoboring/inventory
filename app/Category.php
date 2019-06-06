<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'code',
        'name'
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $appends = array('diffForHumansDeletedAt');

    public function getDiffForHumansDeletedAtAttribute()
    {
        return Carbon::parse($this->attributes['deleted_at'])->diffForHumans();
    }
}
