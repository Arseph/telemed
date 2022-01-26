<?php

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
Route::Auth();
Route::get('/', 'Auth\LoginController@index');
Route::post('login', 'Auth\LoginController@login');
Route::get('/logout', function(){
    $user = \Illuminate\Support\Facades\Session::get('auth');
    \Illuminate\Support\Facades\Session::flush();
    if(isset($user)){
        \App\User::where('id',$user->id)
            ->update([
                'login_status' => 'logout'
            ]);
        $logout = date('Y-m-d H:i:s');
        $logoutId = \App\Login::where('user_id',$user->id)
            ->orderBy('id','desc')
            ->first()
            ->id;

        \App\Login::where('id',$logoutId)
            ->update([
                'status' => 'login_off',
                'logout' => $logout
            ]);
    }
    return redirect('/');
});
// SuperSuperadmin Module
Route::get('superadmin','Superadmin\HomeController@index');
Route::get('/users', 'Superadmin\ManageController@indexUser');
Route::post('/user-deactivate/{id}', 'Superadmin\ManageController@deactivateUser');
Route::post('/user-store', 'Superadmin\ManageController@storeUser');
Route::get('/facilities', 'Superadmin\ManageController@indexFacility');
Route::get('/facilities/{id}/{type}', 'Superadmin\ManageController@getMunandBrgy');
Route::post('/facility-store', 'Superadmin\ManageController@storeFacility');
Route::post('/facility-delete/{id}', 'Superadmin\ManageController@deleteFacility');
Route::get('/provinces', 'Superadmin\ManageController@indexProvince');
Route::post('/province-store', 'Superadmin\ManageController@storeProvince');
Route::post('/province-delete/{id}', 'Superadmin\ManageController@deleteProvince');
Route::match(['GET','POST'],'/municipality/{province_id}/{province_name}','Superadmin\ManageController@viewMunicipality');
Route::post('/municipality-store', 'Superadmin\ManageController@storeMunicipality');
Route::post('/municipality-delete/{id}', 'Superadmin\ManageController@deleteMunicipality');
Route::match(['GET','POST'],'/barangay/{prov_id}/{prov_name}/{mun_id}/{mun_name}','Superadmin\ManageController@viewBarangay');
Route::post('/barangay-store', 'Superadmin\ManageController@storeBarangay');
Route::post('/barangay-delete/{id}', 'Superadmin\ManageController@deleteBarangay');
Route::match(['GET','POST'],'/diagnosis', 'Superadmin\DiagnosisController@indexDiagnosis');
Route::get('/diagnosis/{id}/maincat', 'Superadmin\DiagnosisController@getSubCategory');
Route::post('/diagnosis-store', 'Superadmin\DiagnosisController@storeDiagnosis');
Route::post('/diagnosis-delete/{id}', 'Superadmin\DiagnosisController@deleteDiagnosis');
Route::match(['GET','POST'],'/diagnosis-main-category', 'Superadmin\DiagnosisController@indexDiagMainCat');
Route::post('/main-cat-store', 'Superadmin\DiagnosisController@storeMainCat');
Route::post('/main-cat-delete/{id}', 'Superadmin\DiagnosisController@deleteMainCat');
Route::match(['GET','POST'],'/diagnosis-sub-category', 'Superadmin\DiagnosisController@indexDiagSubCat');
Route::post('/sub-cat-store', 'Superadmin\DiagnosisController@storeSubCat');
Route::post('/sub-cat-delete/{id}', 'Superadmin\DiagnosisController@deleteSubCat');
Route::get('/doctor-option/{id}', 'Superadmin\ManageController@getDoctors');
Route::get('/audit-trail', 'Superadmin\ManageController@indexAudit');
Route::get('/tele-category', 'Superadmin\ManageController@indexTeleCat');
Route::post('/telecat-store', 'Superadmin\ManageController@storeTelecat');
Route::post('/telecat-delete/{id}', 'Superadmin\ManageController@deleteTelecat');


//Admin Module
Route::get('admin','Admin\HomeController@index');
Route::get('/admin-facility','Admin\ManageController@AdminFacility');
Route::post('/update-facility','Admin\ManageController@updateFacility');
Route::match(['GET','POST'],'/admin-patient','Admin\ManageController@patientList');
Route::match(['GET','POST'],'/admin-sched-pending','Admin\ManageController@schedTeleStore');
Route::get('/admin-patient-meeting-info','Admin\ManageController@meetingInfo');
Route::match(['GET','POST'],'/admin-teleconsult','Admin\TeleController@index');
Route::get('/admin-join-meeting','Admin\TeleController@joinMeeting');
Route::get('/admin-meeting-info','Admin\TeleController@meetingInfo');
Route::get('/join-meeting/{id}','Admin\TeleController@indexCall');
Route::get('/get-doctors-facility','Admin\TeleController@getDoctorsFacility');
Route::get('/admin/clinical/{id}','Admin\ManageController@clinical');
Route::post('/admin/clinical-store','Admin\ManageController@clinicalStore');
Route::get('/admin/covid/{id}','Admin\ManageController@covid');
Route::post('/admin/covid-store','Admin\ManageController@covidStore');
Route::get('/admin/diagnosis/{id}','Admin\ManageController@diagnosis');
Route::get('/admin/plan/{id}','Admin\ManageController@plan');

// Doctor Module
Route::get('doctor','Doctor\HomeController@index');
Route::match(['GET','POST'],'doctor/patient/list','Doctor\PatientController@patientList');
Route::match(['GET','POST'],'doctor/patient/update','Doctor\PatientController@patientUpdate');
Route::get('location/barangay/{muncity_id}','Doctor\PatientController@getBaranggays');
Route::match(['GET','POST'],'/patient-store', 'Doctor\PatientController@storePatient');
Route::post('/patient-delete/{id}', 'Doctor\PatientController@deletePatient');
Route::match(['GET','POST'],'doctor/teleconsult','Doctor\TeleConsultController@index');
Route::match(['GET','POST'],'/add-meeting', 'Doctor\TeleConsultController@storeMeeting');
Route::get('/validate-datetime','Doctor\TeleConsultController@validateDateTime');
Route::get('/meeting-info','Doctor\TeleConsultController@meetingInfo');
Route::get('/start-meeting/{id}','Doctor\TeleConsultController@indexCall');
Route::post('/webex-token', 'Doctor\TeleConsultController@storeToken');
Route::post('/patient-accept/{id}', 'Doctor\PatientController@acceptPatient');
Route::post('/patient-consult-info/{id}', 'Doctor\PatientController@patientConsultInfo');
Route::get('/get-pending-meeting/{id}', 'Doctor\TeleConsultController@getPendingMeeting');
Route::post('/accept-decline-meeting/{id}', 'Doctor\TeleConsultController@acceptDeclineMeeting');
Route::match(['GET','POST'],'/doctor-sched-pending','Doctor\TeleConsultController@schedTeleStore');

// Patient Module 
Route::get('patient','Patient\HomeController@index');

