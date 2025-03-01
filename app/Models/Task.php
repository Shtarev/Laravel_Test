<?php

namespace App\Models;

use App\Events\DeadlineCheckEvent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;
    protected $dispatchesEvents = [
        'updated' => DeadlineCheckEvent::class,
    ];
    protected $fillable = [
        'title',
        'description',
        'text',
        'deadline',
        'project_id',
        'user_id',
        'dlmessage',
        'status'
    ];

    public function project() {
        return $this->belongsTo('App\Models\Project');
    }
    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
