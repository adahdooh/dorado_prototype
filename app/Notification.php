<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title', 'content', 'time','state','patient_id','dietitian_id','type'
    ];
    protected $casts = [
        'created_at' => 'datetime:F j, Y H:i a',
        'updated_at' => 'datetime:F j, Y H:i a'
    ];
}
