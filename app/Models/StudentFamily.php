<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFamily extends Model
{
    protected $guarded = ['id'];

    public function profile()
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }
}
