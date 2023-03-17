<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\School;
use App\Student;
use App\Classmate;

class Classroom extends Model
{
    // クラスメートを取得
    public function getClassmates(){
        return Classmate::where("classroom_id", $this->id)->get();
    }

    // 指定した学生IDを持つクラスメートを取得する
    public function getClassmate($student_id){
        return Classmate::where("classroom_id", $this->id)
                        ->where("student_id", $student_id)
                        ->first();
    }

    // 指定した席に配置されているクラスメートを取得する
    public function getClassmateBySeat($row, $column){
        return Classmate::where("row", $row)
                        ->where("column", $column)
                        ->first();
    }

    // クラスメートである学生を取得
    public function getStudents(){
        $classmates = $this->getClassmates();
        $students = [];
        foreach($classmates as $classmate){
            $student = $classmate->getStudent();
            if(isset($student)){
                $students[] = $student;
            }
        }
        return $students;
    }

    // 指定したidを持つ学生がクラスメートであるかを返す
    public function isClassmate($target_student_id){
        $student_ids = array_column($this->getStudents(), "student_id");
        return in_array($student_ids, $target_student_id);
    }

    // 座席情報を取得
    public function getSeats(){
        $seats = [
            "none-seat" => []
        ];
        foreach($this->getClassmates() as $classmate){
            $student = $classmate->getStudent();
            if(isset($student)){
                if(isset($classmate->row) && isset($classmate->column)
                && 0 <= $classmate->row && $classmate->row < $this->row_size
                && 0 <= $classmate->column && $classmate->column < $this->column_size){
                    $seats[$classmate->row][$classmate->column] = $student;
                }else{
                    $seats["none-seat"][] = $student;
                }
            }
        }
        return $seats;
    }
}
