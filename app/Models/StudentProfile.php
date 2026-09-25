<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function education()
    {
        return $this->hasOne(StudentEducation::class);
    }

    public function families()
    {
        return $this->hasMany(StudentFamily::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'periode_id');
    }
}
