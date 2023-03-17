<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// トップページ　とりあえず各ホームページとする
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');

// モバイルアプリ用API
Route::get('/v1/auth', 'MobileAppAPIController@authUser');
Route::get('/v1/loggedin', 'MobileAppAPIController@loggedIn');

// 認証
Auth::routes();

// 凍結アカウントは排除
Route::group(['middleware' => ['auth','can:not-freezed']], function(){
    // 管理者ページ
    Route::group(['middleware' => ['auth','can:admin-higher']], function(){
        // 施設一覧ページ
        Route::get('/link-admin/home', 'LinkAdminController@home');
        // アカウント凍結・解凍
        Route::post('/link-admin/freeze_user', 'LinkAdminController@freezeUser');
        // チャットリスト
        Route::get('/link-admin/chat_list', 'LinkAdminController@openChatList');
        Route::get('/link-admin/chat', 'LinkAdminController@openChat');
        Route::post('/link-admin/chat', 'LinkAdminController@registMessage');
        // 新規施設登録
        Route::post('/link-admin/regist_facilitie', 'LinkAdminController@registFacilitie');
        // 新規学校登録
        Route::post('/link-admin/regist_school', 'LinkAdminController@registSchool');
        // 権限変更
        Route::post('/link-admin/update_user_permit', 'LinkAdminController@updateUserPermit');
        // パスワード変更
        Route::post('/link-admin/update_user_password', 'LinkAdminController@updateUserPassword');
    });

    // 保護者ページ
    Route::group(['middleware' => ['auth','can:parent-only']], function(){
        // 連絡帳閲覧ページ
        Route::get('/parent/home', 'ParentController@openHome');
        // 児童追加ページ
        Route::get('/parent/add_child', 'ParentController@openChildAdd');
        Route::post('/parent/add_child', 'ParentController@registChild');
        // プロフィール編集ページ
        Route::get('/parent/profile', 'ParentController@openProfile');
        Route::post('/parent/update_child', 'ParentController@updateChild');
        Route::post('/parent/update_parent', 'ParentController@updateParent');
        // チャット関連ページ
        Route::get('/parent/chat_list', 'ParentController@openChatList');
        Route::get('/parent/chat', 'ParentController@openChat');
        Route::post('/parent/chat', 'ParentController@registMessage');
        // カレンダーページ
        Route::get('/parent/calendar', 'ParentController@openCalendar');
    });

    // 職員用ページ
    Route::group(['middleware' => ['auth','can:worker-only']], function(){
        // 利用者選択ページ
        Route::get('/worker/home', 'WorkerController@openHome');
        // 一括入力ページ
        Route::get('/worker/batch_edit_diarie', 'WorkerController@openBatchEditDiarie');
        Route::post('/worker/batch_search', 'WorkerController@batchSearch');
        Route::post('/worker/regist_batch_activity', 'WorkerController@registBatchActivity');
        Route::post('/worker/delete_batch_activity', 'WorkerController@deleteBatchActivity');
        Route::post('/worker/upload_batch_image', 'WorkerController@uploadBatchActiveImage');
        Route::post('/worker/regist_batch_message', 'WorkerController@registBatchMessage');
        //グループ編集
        Route::post('/worker/update_group', 'WorkerController@createUpdateGroup');
        Route::post('/worker/delete_group', 'WorkerController@deleteGroup');
        // 利用者追加ページ
        Route::get('/worker/add_client', 'WorkerController@openClientAdd');
        Route::post('/worker/add_client', 'WorkerController@registClient');
        // プロフィール編集ページ
        Route::get('/worker/profile', 'WorkerController@openProfile');
        Route::post('/worker/update_worker', 'WorkerController@updateWorkerProfile');
        // 連絡帳編集ページ
        Route::get('/worker/edit_diarie', 'WorkerController@openDiarieEditor');
        Route::post('/worker/edit_diarie', 'WorkerController@updateDiarieItem');
        Route::post('/worker/delete_diarie_item', 'WorkerController@deleteDiarieItem');
        Route::post('/worker/upload_img', "WorkerController@uploadActiveImage");
        Route::post('/worker/edit_document', 'WorkerController@updateDiarieDocument');
        // 連絡帳PDF表示用ページ
        Route::get('/worker/pdf', 'WorkerController@openDiariePDF');
        // チャット関連ページ
        Route::get('/worker/chat_list', 'WorkerController@openChatList');
        Route::get('/worker/chat', 'WorkerController@openChat');
        Route::post('/worker/chat', 'WorkerController@registMessage');
        // 機能選択ページ
        Route::get('/worker/report_list', 'WorkerController@openReportList');
        // サービス管理ページ
        Route::get('/worker/service_management', 'WorkerController@openServiceManagement');
        Route::post('/worker/service_management', 'WorkerController@updateServiceDocument');
        // サービス提供記録選択・編集・ダウンロードページ
        Route::get('/worker/service_report', 'WorkerController@openServiceReportList');
        Route::get('/worker/service_report_view', 'WorkerController@openServiceReportView');
        Route::post('/worker/update_diarie_item_time', 'WorkerController@updateDiarieItemTime');
        Route::post('/worker/update_diarie_info', 'WorkerController@updateDiarieInfo');
        Route::post('/worker/output_excel_report', 'WorkerController@downloadServiceReportFile');
        // 活動実績記録票選択・編集・ダウンロードページ
        Route::get('/worker/transfer_report', 'WorkerController@openTransferReportList');
        Route::get('/worker/transfer_report_view', 'WorkerController@openTransferReportView');
        Route::post('/worker/output_transfer_excel_report', 'WorkerController@downloadTransferReportFile');
        // 送迎記録入力ページ
        Route::get('/worker/transfer', 'WorkerController@openTransfer');
        Route::post('/worker/transfer', 'WorkerController@uploadSignImage');
        Route::post('/worker/transfer_update', 'WorkerController@updateTransfer');
        // 職員一覧ページ
        Route::get('/worker/worker_list', 'WorkerController@openWorkerList');
        Route::post('/worker/update_worker_permit', 'WorkerController@updateWorkerPermit');
        // 削除済み職員一覧ページ
        Route::get('/worker/deleted_worker_list', 'WorkerController@openDeletedWorkerList');
        Route::post('/worker/deleted_worker', 'WorkerController@deleteWorker');
        Route::post('/worker/restore_worker', 'WorkerController@restoreWorker');
        // 職員登録ページ
        Route::get('/worker/add_worker', 'WorkerController@openWorkerAdd');
        Route::post('/worker/add_worker', 'WorkerController@registWorker');
        // 職員契約承認ページ
        Route::get('/worker/approval_worker', 'WorkerController@openApprovalWorker');
        Route::post('/worker/approval_worker', 'WorkerController@approvalWorker');
        // 児童と利用者の紐づけ認証ページ
        Route::get('/worker/approval_child', 'WorkerController@openApprovalChild');
        Route::post('/worker/approval_child', 'WorkerController@approvalChild');
    });

    // 教員用ページ
    Route::group(['middleware' => ['auth','can:teacher-only']], function(){
        // 授業選択ページ
        Route::get('/teacher/home', 'TeacherController@openClassrooms');
        // 授業記録ページ
        Route::get('/teacher/classroom', 'TeacherController@openSeatTable');
        Route::post('/teacher/regist_student_info', 'TeacherController@registStudentInfo');
        // 学生・教員ページ
        Route::get('/teacher/document_list', 'TeacherController@openDocumentList');
        // 学生リストページ
        Route::get('/teacher/student_list', 'TeacherController@openStudentList');
        Route::post('/teacher/update_student', 'TeacherController@updateStudentInfo');
        Route::get('/teacher/promote', 'TeacherController@promoteStudent');
        // 教員リストページ
        Route::get('/teacher/teacher_list', 'TeacherController@openTeacherList');
        Route::post('/teacher/update_teacher', 'TeacherController@updateTeacherInfo');
        // クラスルーム編集ページ
        Route::get('/teacher/classroom_list', 'TeacherController@openClassroomList');
        Route::post('/teacher/regist_classroom_list', 'TeacherController@registClassroomList');
        Route::post('/teacher/delete_classroom_list', 'TeacherController@deleteClassroomList');
        // 席替えページ
        Route::get('/teacher/classroom_seat_table_change', 'TeacherController@openClassroomSeatTable');
        Route::post('/teacher/classroom_seat_table_change', 'TeacherController@updateClassroomSeatTable');
        // 解析画面
        Route::get('/teacher/analysis', 'TeacherController@openLineGraph');
        Route::post('/teacher/search_school_item', 'TeacherController@searchSchoolItem');
    });

    // リクエスト出力用URL
    Route::get('/display', 'UserController@display');
    Route::post('/display', 'UserController@display');
});
