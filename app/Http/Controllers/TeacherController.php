<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Auth;
use DateTime;
use App\User;
use App\Classroom;
use App\School;
use App\Student;
use App\Teacher;
use App\Classmate;
use App\SchoolItem;

class TeacherController extends Controller
{
    /**
     * 授業一覧画面
     */
    public function openClassrooms(Request $request){
        $admin = Auth::user();
        $school = School::where("admin_id", $admin->id)->first();
        $classrooms = (isset($school))? $school->getActiveClassrooms() : [];

        $datas = [
            "title" => "クラス一覧",
            "classrooms" => $classrooms
        ];
        return view("teacher.home", ["datas" => $datas]);
    }

    /**
     * 授業画面
     */
    public function openSeatTable(Request $request){
        $validate_rule = [
            'classroom_id' => 'required'
        ];
        $this->validate($request, $validate_rule);

        $admin = Auth::user();
        $school = School::where("admin_id", $admin->id)->first();
        $teachers = isset($school)? Teacher::where("school_id", $school->id)->get() : [];
        $classroom = isset($school)? $school->getClassroom($request->classroom_id) : null;
        $seats = isset($classroom)? $classroom->getSeats() : [];

        $teacher = isset($classroom)? Teacher::find($classroom->teacher_id) : null;
        $items = (isset($teacher))? $teacher->getTeacherHistories() : [];
        $datas = [
            "title" => "座席表テスト",
            "uesr" => Auth::user(),
            "teachers" => $teachers,
            "classroom" => $classroom,
            "seats" => $seats,
            "items" => $items,
        ];
        return view("teacher.seat_table", ["datas" => $datas]);
    }

    /**
     * 学生情報入力
     */
    public function registStudentInfo(Request $request){
        $admin = Auth::user();
        $school = School::where("admin_id", $admin->id)->first();
        if(!empty($request->student_ids)){
            $now = Date("Y-m-d H:i:s");
            $time = $request->time ?:  Date("H:i:00");
            foreach($request->student_ids as $student_id){
                $item = new SchoolItem;
                $item->student_id = $student_id;
                $item->time = $time;
                $item->activity = $request->activity;
                $item->comment = $request->comment;
                $item->subject = $request->subject;
                $item->teacher_id = $request->teacher_id;
                $item->school_id = $school->id;
                $item->classroom_id = $request->classroom_id;
                $item->active = true;
                $item->created_at = $now;
                $item->updated_at = $now;
                $item->save();
            }
        }
        exit();
    }

    /**
     * 学生・教員選択画面
     */
    public function openDocumentList(Request $request){
        $datas = [
            "title" => "データ管理",
        ];
        return view("teacher.document_list", ["datas" => $datas]);
    }

    /**
     * 学生リスト画面
     */
    public function openStudentList(Request $request){
        $admin = Auth::user();
        $school = School::where("admin_id", $admin->id)->first();
        if(isset($request->grade)){
            $students = Student::where("school_id", $school->id)->where("grade", $request->grade)->get();
        }else{
            $students = Student::where("school_id", $school->id)->get();
        }
        $datas = [
            "title" => "学生管理",
            "students" => $students,
            "grade" => $request->grade,
        ];
        return view("teacher.student_list", ["datas" => $datas]);
    }

    /**
     * 学生情報更新
     */
    public function updateStudentInfo(Request $request){
        $validate_rule = [
            'student_id' => 'required',
            'name' => 'required',
            'grade' => 'required',
        ];
        $this->validate($request, $validate_rule);

        $admin = Auth::user();
        $school = School::where("admin_id", $admin->id)->first();

        $now = Date("Y-m-d H:i:s");
        $student = Student::find($request->student_id);
        if(!isset($student)){
            $student = new Student();
            $student->created_at = $now;
        }
        $student->school_id = $school->id;
        $student->name = $request->name;
        $student->grade = $request->grade;
        $student->entered_at = $request->entered_at ?: null;
        $student->graduated_at = $request->graduated_at ?: null;
        $student->active = true;
        $student->updated_at = $now;
        $student->save();

        exit();
    }

    /**
     * 学生進級
     */
    public function promoteStudent(Request $request){
        $students = Student::get();
        $now = Date("Y-m-d H:i:s");
        foreach($students as $student) {
            $student->grade = $student->grade+1;
            $student->updated_at = $now;
            $student->save();
        }

        return redirect()->back();
    }

    /**
     * 教員リスト画面
     */
    public function openTeacherList(Request $request){
        $admin = Auth::user();
        $school = School::where("admin_id", $admin->id)->first();
        $teachers = isset($school)? Teacher::where("school_id", $school->id)->get() : [];
        $datas = [
            "title" => "教員管理",
            "teachers" => $teachers,
        ];
        return view("teacher.teacher_list", ["datas" => $datas]);
    }

    /**
     * 教員情報更新
     */
    public function updateTeacherInfo(Request $request){
        $validate_rule = [
            'teacher_id' => 'required',
            'name' => 'required',
        ];
        $this->validate($request, $validate_rule);

        $admin = Auth::user();
        $school = School::where("admin_id", $admin->id)->first();

        $now = Date("Y-m-d H:i:s");
        $teacher = Teacher::find($request->teacher_id);
        if(!isset($teacher)){
            $teacher = new Teacher();
            $teacher->created_at = $now;
        }
        $teacher->school_id = $school->id;
        $teacher->name = $request->name;
        $teacher->active = true;
        $teacher->updated_at = $now;
        $teacher->save();

        exit();
    }

    /**
     * クラスルームリスト画面
     */
    public function openClassroomList(Request $request){
        $admin = Auth::user();
        $school = School::where("admin_id", $admin->id)->first();
        $students = (isset($school))? $school->getStudents() : [];
        $teachers = (isset($school))? $school->getTeachers() : [];
        $classrooms = (isset($school))? $school->getActiveClassrooms() : [];
        foreach($classrooms as $classroom){
            $classroom["classmates"] = $classroom->getStudents();
        }

        $datas = [
            "title" => "クラス管理",
            "classrooms" => $classrooms,
            "students" => $students,
            "teachers" => $teachers,
        ];
        return view("teacher.classroom_list", ["datas" => $datas]);
    }

    /**
     * クラスルーム登録・更新
     */
    public function registClassroomList(Request $request){
        $validate_rule = [
            'classroom_id' => 'required'
        ];
        $this->validate($request, $validate_rule);

        $admin = Auth::user();
        $school = School::where("admin_id", $admin->id)->first();
        $now = Date("Y-m-d H:i:s");

        //クラス新規登録・更新
        $classroom = ($request->classroom_id == 0)? new Classroom : Classroom::find($request->classroom_id);
        $classroom->school_id = $school->id;
        $classroom->teacher_id = $request->teacher_id;
        $classroom->name = $request->name;
        $classroom->active = true;
        $classroom->created_at = ($request->classroom_id == 0)? $now : $classroom->created_at;
        $classroom->updated_at = $now;
        $classroom->save();

        //クラスメイト登録・更新
        if(!empty($request->student_ids)){
            foreach ($request->student_ids as $student_id){
                $exist = Classmate::where("classroom_id", $classroom->id)->where("student_id", $student_id)->exists();
                if(!$exist){
                    $classmate = new Classmate;
                    $classmate->classroom_id = $classroom->id;
                    $classmate->student_id = $student_id;
                    $classmate->row = null;
                    $classmate->column = null;
                    $classmate->active = true;
                    $classmate->created_at = $now;
                    $classmate->updated_at = $now;
                    $classmate->save();
                }
            }
            Classmate::where("classroom_id", $classroom->id)->whereNotIn("student_id", $request->student_ids)->delete();
        }

        //レスポンス
        exit();
    }

    /**
     * クラスルーム削除
     */
    public function deleteClassroomList(Request $request){
        //クラスルームのactiveをfalse
        $classroom = Classroom::find($request->classroom_id);
        $classroom->active = false;
        $classroom->save();

        //レスポンス
        exit();
    }

    /**
     * クラスルーム席替え画面
     */
    public function openClassroomSeatTable(Request $request){
        $validate_rule = [
            'classroom_id' => 'required'
        ];
        $this->validate($request, $validate_rule);

        $classroom = Classroom::find($request->classroom_id);
        $classmates = isset($classroom)? $classroom->getClassmates() : [];
        $seats = isset($classroom)? $classroom->getSeats() : [[]];

        $datas = [
            "title" => "クラス席替え",
            "classroom" => $classroom,
            "classmates" => $classmates,
            "seats" => $seats,
        ];

        return view("teacher.classroom_seat_change", ["datas" => $datas]);
    }

    /**
     * 座席表更新
     */
    public function updateClassroomSeatTable(Request $request){
        $validate_rule = [
            'classroom_id' => 'required',
        ];
        $this->validate($request, $validate_rule);

        $classroom = Classroom::find($request->classroom_id);
        $now = Date("Y-m-d H:i:s");

        //dd(Classmate::where("classroom_id", $classroom->id)->whereBetween("row", [0, $classroom->row_size-1])->get());

        // 座席表サイズの更新
        if(isset($request->row_size) && isset($request->column_size)){
            $row_size = $request->row_size;
            $column_size = $request->column_size;
            if(0 <= $row_size && 0 <= $column_size){
                // クラスルームの座席表サイズを更新
                $classroom->row_size = $row_size;
                $classroom->column_size = $column_size;
                $classroom->updated_at = $now;
                $classroom->save();
                // 変更によって席がなくなったクラスメートを更新
                Classmate::where("classroom_id", $classroom->id)
                         ->whereNotBetween("row", [0, $row_size-1])
                         ->orWhereNotBetween("column", [0, $column_size-1])
                         ->update(["row" => null, "column" => null, "updated_at" => $now]);
            }
        }

        // 学生の配置
        $row = $request->row;
        $column = $request->column;
        if(0 <= $row && $row < $classroom->row_size && 0 <= $column && $column < $classroom->column_size){
            $old_classmate = $classroom->getClassmateBySeat($row, $column);
            if(isset($old_classmate)){
                $old_classmate->row = null;
                $old_classmate->column = null;
                $old_classmate->updated_at = $now;
                $old_classmate->save();
            }
            if(isset($request->student_id)){
                $classmate = $classroom->getClassmate($request->student_id);
                if(isset($classmate)){
                    $classmate->row = $row;
                    $classmate->column = $column;
                    $classmate->updated_at = $now;
                    $classmate->save();
                }
            }
        }

        return redirect()->back();
    }

    /**
     * 統計情報を表示する
     */
    public function openLineGraph(Request $request){
        $admin = Auth::user();
        $school = School::where("admin_id", $admin->id)->first();
        $year_list = $school->getYearList();
        $datas = [
            "title" => "データ集計",
            "year_list" => $year_list,
            "graph_datas" => [[]],
        ];
        return view("teacher.line_graph", ["datas" => $datas]);
    }

    /**
     * 統計情報の絞り込み
     */
    public function searchSchoolItem(Request $request){
        $admin = Auth::user();
        $school = School::where("admin_id", $admin->id)->first();
        $now = Date("Y-m-d H:i:s");

        //entered_atの範囲
        $entered_at = (strtotime($request->entered_at) !== strtotime("0000-00-00"))? $request->entered_at: Date("Y-m-d");
        $year = Date("Y", strtotime($entered_at));
        $start_year = Date("Y-m-d", mktime(0, 0, 0, 4, 1, $year));
        $end_entered_at = (strtotime($entered_at) >= strtotime($start_year))? Date("Y-m-d", mktime(0, 0, 0, 3, 31, $year+1)) : Date("Y-m-d", mktime(0, 0, 0, 3, 31, $year));
        $start_entered_at = (strtotime($request->entered_at) == strtotime("0000-00-00"))? Date("Y-04-01", strtotime($end_entered_at."-6 year")) : Date("Y-04-01", strtotime($end_entered_at."-1 year"));

        //activity配列化
        if(empty($request->activity)){
            $activity = SchoolItem::distinct()->select("activity")->get();
            $activity = json_decode(json_encode($activity), true);
            $activities = array_column($activity, "activity");
        }else{
            $activities = array();
            $activities[] = $request->activity;
        }

        //日・月・年別で集計
        $graph_datas = array();
        foreach ($activities as $key => $activity) {
            $graph_datas[$key]["label"] = $activity;
            if(strcmp($request->unit, "date") == 0){
                for($i = $request->start_date; $i <= $request->end_date; $i = Date("Y-m-d", strtotime($i . "+1 day"))){
                    $graph_datas[$key]["data"][$i] = SchoolItem::join("students", "school_items.student_id", "=", "students.id")
                    ->whereBetween("students.entered_at", [$start_entered_at, $end_entered_at])
                    ->where("school_items.school_id", $school->id)
                    ->whereDate("school_items.created_at", $i)
                    ->where("activity", $activity)->count("activity");
                }
            }else if(strcmp($request->unit, "month") == 0){
                $start_month = Date("Y-m-01", strtotime($request->start_date));
                $end_month = Date("Y-m-01", strtotime($request->end_date));
                for($i = $start_month; $i <= $end_month; $i = Date("Y-m-d", strtotime($i . "+1 month"))){
                    $month = Date("Y-m", strtotime($i));
                    $month_num = Date("m", strtotime($i));
                    $graph_datas[$key]["data"][$month] = SchoolItem::join("students", "school_items.student_id", "=", "students.id")
                    ->whereBetween("students.entered_at", [$start_entered_at, $end_entered_at])
                    ->where("school_items.school_id", $school->id)
                    ->whereMonth("school_items.created_at", $month_num)
                    ->where("activity", $activity)->count("activity");
                }
            }else if(strcmp($request->unit, "year") == 0){
                //年度計算
                $year = Date("Y", strtotime($request->start_date));
                $start = Date("Y-m-d", mktime(0, 0, 0, 4, 1, $year));
                $start_year = (strtotime($request->start_date) >= strtotime($start))? $start : Date("Y-m-d", mktime(0, 0, 0, 4, 1, $year-1));
                $year = Date("Y", strtotime($request->end_date));
                $end = Date("Y-m-d", mktime(0, 0, 0, 4, 1, $year));
                $end_year = (strtotime($request->end_date) >= strtotime($end))? $end : Date("Y-m-d", mktime(0, 0, 0, 4, 1, $year-1));

                for($i = $start_year; $i <= $end_year; $i = Date("Y-m-d", strtotime($i . "+1 year"))){
                    $year = Date("Y", strtotime($i));
                    $graph_datas[$key]["data"][$year] = SchoolItem::join("students", "school_items.student_id", "=", "students.id")
                    ->whereBetween("students.entered_at", [$start_entered_at, $end_entered_at])
                    ->where("school_items.school_id", $school->id)
                    ->whereBetween("school_items.created_at", [$i, Date("Y-03-31", strtotime($i . "+1 year"))])
                    ->where("activity", $activity)->count("activity");
                }
            }
        }

        // レスポンス
        header("Content-type: application/json; charset=UTF-8");
        echo json_encode($graph_datas);
        exit();
    }
}
