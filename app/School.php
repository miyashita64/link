<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Classroom;
use App\Student;
use App\Teacher;

class School extends Model
{
    // 全ての授業を取得
    public function getClassrooms(){
        return Classroom::where("school_id", $this->id)->get();
    }

    //activeなクラスルームだけを取得
    public function getActiveClassrooms(){
        return Classroom::where("school_id", $this->id)->where("active", true)->get();
    }

    // 指定した授業を取得
    public function getClassroom($classroom_id){
        $classrooms = $this->getClassrooms();
        return $classrooms->find($classroom_id);
    }

    // 学生を取得
    public function getStudents(){
        return Student::where("school_id", $this->id)->get() ?: [];
    }

    // 教員を取得
    public function getTeachers(){
        return Teacher::where("school_id", $this->id)->get() ?: [];
    }

    // 学生が存在する年度を取得
    public function getYearList(){
        $year_list = [];
        $years = [];
        $students = $this->getStudents();
        foreach($students as $student){
            $years[] = $student["entered_at"];
        }
        $years = array_unique($years);
        foreach($years as $year){
            if(isset($year)){
                // 当該学年を割り出す
                $to_year = Date("Y");
                if(Date("m")<=3) $to_year--;
                $data = explode("-", explode(" ", $year)[0]);
                $dy = $to_year - $data[0] + 1;
                $grade = (1 <= $dy && $dy <= 6)? "(".$dy."学年)" : "";
                // データを追加
                $year_list[] = [
                    "content" => explode("-", $year)[0]."年度".$grade,
                    "value" => explode(" ", $year)[0]
                ];
            }
        }
        return $year_list;
    }
}
