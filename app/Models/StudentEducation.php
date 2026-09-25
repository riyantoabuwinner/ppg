<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentEducation extends Model
{
    protected $table = 'student_educations';
    protected $guarded = ['id'];

    public function profile()
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }
}
