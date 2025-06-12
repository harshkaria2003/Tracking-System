<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'task_description',
        'project_id',
        'start_time',
        'end_time',
        'total_time_seconds',
    ];


   public function employee()
{
    return $this->belongsTo(Employee::class, 'employee_id');
}

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
