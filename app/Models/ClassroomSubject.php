<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassroomSubject extends Model
{
    use HasFactory;

    protected $table = 'classroom_subject';
    protected $guarded = [];

    public function subjects(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}
