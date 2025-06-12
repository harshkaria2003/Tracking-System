<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTracking extends Model
{
    protected $table = 'user_tracking'; 

    protected $fillable = [
        'user_id',
        'event_type',
        'event_data',
        'url',
        'ip_address',
        'user_agent',
        'click_count',
        'scroll_count',
        'keypress_count',
    ];

    protected $casts = [
        'event_data' => 'array',
    ];

   
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
