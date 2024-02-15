<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function admin()
    {
        return $this->belongsTo(\App\Model\Admin::class, 'admin_id');
    }

    public function scopeOpened($query)
    {
        return $query->whereNull('close_time');
    }

    public function scopeClosed($query)
    {
        return $query->whereNotNull('close_time');
    }
}
