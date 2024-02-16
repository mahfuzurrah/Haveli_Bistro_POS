<?php

namespace App\Models;

use App\Model\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminActivity extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function scopeOpened($query)
    {
        return $query->whereNull('end_date');
    }

    public function scopeClosed($query)
    {
        return $query->whereNotNull('end_date');
    }

}
