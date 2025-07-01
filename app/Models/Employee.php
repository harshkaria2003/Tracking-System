<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Employee extends Model
{
    use HasFactory;

    
    use LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'project_id',
        'country_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

   
public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}

public function timeLogs()
{
    return $this->hasMany(\App\Models\TimeLog::class);
}

   
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'project_id', 'start_date', 'end_date'])
            ->useLogName('employee')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    
}
