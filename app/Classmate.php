<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\School;
use App\Student;

class Classmate extends Model
{
    public function getStudent(){
        return Student::find($this->student_id);
    }
}
