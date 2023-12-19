<?php

//use App\Models\Province;
use GuzzleHttp\Middleware;
//use App\Models\EstablishmentType;
//use App\Models\RegisterCoomplaint;
use Illuminate\Support\Facades\App;
//use App\Models\LabourOfficeDivision;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Adminpanel\Masterdata\CityController;
use App\Http\Controllers\Adminpanel\Masterdata\AboutUsController;
use App\Http\Controllers\Adminpanel\Masterdata\DistrictController;
use App\Http\Controllers\Adminpanel\Masterdata\ProvinceController;
use App\Http\Controllers\Adminpanel\Masterdata\SmsTemplateController;
use App\Http\Controllers\Adminpanel\Masterdata\MailTemplateController;
use App\Http\Controllers\Adminpanel\Masterdata\ComplaintListController;
use App\Http\Controllers\Adminpanel\Complaint\ActionPendingListController;
use App\Http\Controllers\Adminpanel\Complaint\ClosedListController;
use App\Http\Controllers\Adminpanel\Complaint\InvestigationOngoingListController;
//use App\Http\Controllers\Adminpanel\Masterdata\ComplainCategoryController;
use App\Http\Controllers\Adminpanel\Masterdata\EstablishmentTypeController;
use App\Http\Controllers\Adminpanel\RegisterComplaintController;
use App\Http\Controllers\Adminpanel\Masterdata\LabourOfficeDivisionController;
use App\Http\Controllers\Userpanel\NewComplaintController;
use App\Http\Controllers\Adminpanel\LogActivityController;
use App\Http\Controllers\Adminpanel\Complaint\SearchComplaintController;
use App\Http\Controllers\Adminpanel\Complaint\PendingApprovalListController;
use App\Http\Controllers\Adminpanel\Complaint\TemporaryClosedListController;
use App\Http\Controllers\Adminpanel\Complaint\PendingCertificateListController;
use App\Http\Controllers\Adminpanel\Complaint\PendingChargesheetListController;
use App\Http\Controllers\Adminpanel\Complaint\PendingRecoveryListController;
use App\Http\Controllers\Adminpanel\Complaint\ReportComplaintController;
use App\Http\Controllers\Adminpanel\DashboardController;
use App\Http\Controllers\Adminpanel\Masterdata\BusinessNatureController;
use App\Http\Controllers\Adminpanel\Masterdata\ComplainStatusController;
use App\Http\Controllers\Adminpanel\Masterdata\ComplaintRemarkController;
use App\Http\Controllers\Adminpanel\DatabaseBackupController;
use App\Http\Controllers\Adminpanel\EmailLogController;
use App\Http\Controllers\Adminpanel\Masterdata\EventTitleController;
use App\Http\Controllers\Adminpanel\SendSmsController;
use App\Http\Controllers\Adminpanel\UserManualController;
use App\Http\Controllers\Adminpanel\SmsLogController;
use App\Http\Controllers\Adminpanel\MultipleSmsRecordController;
use App\Http\Controllers\Adminpanel\TestEmailController;
use App\Models\LabourOfficeDivision;
use App\Http\Controllers\Adminpanel\EventController;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\WorkshopController;

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



Route::get('/admin', function () {
    return view('auth.login');
    //return view('welcome');
});

Route::get('/', function () {
    return view('userpanel.index');
    //return view('welcome');
});

Route::get('/login', function () {
    //dd('Hiiii');
   // rename('index.html', 'index15-03-2022.html');
    return view('userpanel.index');
    //return view('welcome');
});

Route::get('workshop/register', [WorkshopController::class, 'workshop_register'])->name('workshop.register');
Route::post('workshop/register', [WorkshopController::class, 'register'])->name('workshop.register');
Route::get('workshop/forgot-password', [LoginController::class, 'forgot_password'])->name('workshop.forgot-password');
Route::post('workshop/forgot-password', [LoginController::class, 'submitForgetPasswordForm'])->name('workshop.forgot-password');

Route::get('workshop/reset-password/{token}', [LoginController::class, 'showResetPasswordForm'])->name('workshop.reset-password');
Route::post('workshop/password-reset', [LoginController::class, 'submitResetPasswordForm'])->name('workshop.password-reset');


Route::get('/main-dashboard', [DashboardController::class, 'mainDashboard'])->name('main-dashboard');

Route::get('new-register', [NewComplaintController::class, 'index'])->name('new-register');
Route::post('new-complaint', [NewComplaintController::class, 'store'])->name('new-complaint');
Route::get('complaint-tracking', [NewComplaintController::class, 'complainttracking'])->name('complaint-tracking');
Route::get('/search-complaint-reference', [NewComplaintController::class, 'searchcomplaint'])->name('search-complaint-reference');
Route::get('/verification', [NewComplaintController::class, 'verification'])->name('verification');
Route::get('/complaint-status', [NewComplaintController::class, 'complaintstatus'])->name('complaint-status');
Route::get('resent-otp/{id}', [NewComplaintController::class, 'resentotp'])->name('resent-otp');
Route::get('getCityFront', [NewComplaintController::class, 'getCityFront'])->name('getCityFront');

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);

// Route::middleware(['auth', 'front-access:workshop'])->group(function () {
//     Route::get('workshop/dashboard', [LoginController::class, 'dashboard'])->name('workshop.dashboard');
// });
Route::middleware(['auth:front-access'])->group(function () {
    Route::get('workshop/dashboard', [LoginController::class, 'dashboard'])->name('workshop.dashboard');
    Route::get('workshop/profile', [LoginController::class, 'profile'])->name('workshop.profile');
    Route::post('workshop/profile', [LoginController::class, 'update'])->name('workshop.profile');
    Route::post('workshop/password_update', [LoginController::class, 'update_password'])->name('workshop.password_update');
    Route::get('workshop/michanics', [WorkshopController::class, 'index'])->name('workshop.michanics');
    Route::get('workshop/add-michanics', [WorkshopController::class, 'add'])->name('workshop.add-michanics');
    Route::get('workshop/michanics-list', [WorkshopController::class, 'datalist'])->name('workshop.michanics-list');
    Route::post('workshop/add-michanics', [WorkshopController::class, 'store'])->name('workshop.add-michanics');
    Route::get('workshop/edit-michanics/{id}', [WorkshopController::class, 'edit'])->name('workshop.edit-michanics');
    Route::get('workshop/michanics/change-status/{id}', [WorkshopController::class, 'activation'])->name('workshop.michanics.change-status');

    Route::post('workshop/enroll_session', [WorkshopController::class, 'enroll_session'])->name('workshop.enroll_session');
    Route::get('workshop/session_calendar', [WorkshopController::class, 'show_calander'])->name('workshop.session_calendar');

    Route::get('workshop/past_session', [WorkshopController::class, 'past_trainings'])->name('workshop.past_session');
    Route::get('workshop/upcomming_sessions', [WorkshopController::class, 'future_trainings'])->name('workshop.upcomming_sessions');

    Route::get('workshop/scan-for-points', [WorkshopController::class, 'scan_for_points'])->name('workshop.scan-for-points');
    Route::get('workshop/take-picture', [WorkshopController::class, 'take_picture'])->name('workshop.take-picture');
    Route::post('workshop/redeem-points', [WorkshopController::class, 'redeem_points'])->name('workshop.redeem-points');

    Route::get('workshop/redeem-list', [WorkshopController::class, 'redeemlist'])->name('workshop.redeem-list');
    Route::get('workshop/report-incedent', [WorkshopController::class, 'report_incedent'])->name('workshop.report-incedent');
    Route::post('workshop/report-incedent', [WorkshopController::class, 'store_incedent'])->name('workshop.report-incedent');
    Route::get('workshop/incedents-list', [WorkshopController::class, 'incedent_list'])->name('workshop.incedents-list');
    Route::get('workshop/list_incedent', [WorkshopController::class, 'incedent_datalist'])->name('workshop.list_incedent');
    Route::get('workshop/edit_incedent/{id}', [WorkshopController::class, 'edit_incedent'])->name('workshop.edit_incedent');

    Route::get('workshop/exam_calendar', [WorkshopController::class, 'exam_sessions'])->name('workshop.exam_calendar');
    Route::post('workshop/enroll-exam', [WorkshopController::class, 'enroll_exam'])->name('workshop.enroll-exam');
    Route::get('workshop/exam', [WorkshopController::class, 'start_exam'])->name('workshop.exam');

    Route::get('workshop/start-exam/{id}', [WorkshopController::class, 'load_exam_paper'])->name('workshop.start-exam');
    Route::post('workshop/exampaper-submit', [WorkshopController::class, 'submit_exampaper'])->name('workshop.exampaper-submit');
    Route::get('workshop/exam-complete', [WorkshopController::class, 'complete_exam'])->name('workshop.exam-complete');

    Route::get('workshop/logout', [LoginController::class, 'destroy'])->name('workshop.logout');
   
});

Route::group(['middleware' => 'auth'], function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // });

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
   
   

    Route::view('profile', 'profile')->name('profile');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('about-us', [AboutUsController::class, 'index'])->name('about-us');

    Route::get('province', [ProvinceController::class, 'index'])->name('province');
    Route::post('new-province', [ProvinceController::class, 'store'])->name('new-province');
    Route::get('province-list', [ProvinceController::class, 'list'])->name('province-list');
    Route::get('/edit-province/{id}', [ProvinceController::class, 'edit'])->name('edit-province');
    Route::put('save-province', [ProvinceController::class, 'update'])->name('save-province');
    Route::get('changestatus-province/{id}', [ProvinceController::class, 'activation'])->name('changestatus-province');
    Route::get('blockprovince/{id}', [ProvinceController::class, 'block'])->name('blockprovince');

    Route::get('district', [DistrictController::class, 'index'])->name('district');
    Route::post('new-district', [DistrictController::class, 'store'])->name('new-district');
    Route::get('district-list', [DistrictController::class, 'list'])->name('district-list');
    Route::get('/edit-district/{id}', [DistrictController::class, 'edit'])->name('edit-district');
    Route::put('save-district', [DistrictController::class, 'update'])->name('save-district');
    Route::get('changestatus/{id}', [DistrictController::class, 'activation'])->name('changestatus');
    Route::get('block/{id}', [DistrictController::class, 'block'])->name('block');

    Route::get('city', [CityController::class, 'index'])->name('city');
    Route::get('getDistrict', [CityController::class, 'getDistrict'])->name('getDistrict');
    Route::post('new-city', [CityController::class, 'store'])->name('new-city');
    Route::get('city-list', [CityController::class, 'list'])->name('city-list');
    Route::get('/edit-city/{id}', [CityController::class, 'edit'])->name('edit-city');
    Route::put('save-city', [CityController::class, 'update'])->name('save-city');
    Route::get('changestatus-city/{id}', [CityController::class, 'activation'])->name('changestatus-city');
    Route::get('blockcity/{id}', [CityController::class, 'block'])->name('blockcity');
    Route::get('checkDuplicate/{province_id}/{district_id}/{cityName}', [CityController::class, 'checkDuplicate'])->name('checkDuplicate');

    Route::get('establishment-type', [EstablishmentTypeController::class, 'index'])->name('establishment-type');
    Route::post('new-establishment-type', [EstablishmentTypeController::class, 'store'])->name('new-establishment-type');
    Route::get('establishment-type-list', [EstablishmentTypeController::class, 'list'])->name('establishment-type-list');
    Route::get('/edit-establishment-type/{id}', [EstablishmentTypeController::class, 'edit'])->name('edit-establishment-type');
    Route::put('save-establishment-type', [EstablishmentTypeController::class, 'update'])->name('save-establishment-type');
    Route::get('changestatus-establishment-type/{id}', [EstablishmentTypeController::class, 'activation'])->name('changestatus-establishment-type');
    Route::get('blockestablishment/{id}', [EstablishmentTypeController::class, 'block'])->name('blockestablishment');

    Route::get('labour-office-division', [LabourOfficeDivisionController::class, 'index'])->name('labour-office-division');
    Route::get('getDistrict', [LabourOfficeDivisionController::class, 'getDistrict'])->name('getDistrict');
    Route::get('getCity', [LabourOfficeDivisionController::class, 'getCity'])->name('getCity');
    Route::get('getCityforOffice', [LabourOfficeDivisionController::class, 'getCityforOffice'])->name('getCityforOffice');
    Route::post('new-labour-office-division', [LabourOfficeDivisionController::class, 'store'])->name('new-labour-office-division');
    Route::get('labour-office-division-list', [LabourOfficeDivisionController::class, 'list'])->name('labour-office-division-list');
    Route::get('/edit-labour-office-division/{id}', [LabourOfficeDivisionController::class, 'edit'])->name('edit-labour-office-division');
    Route::put('save-labour-office-division', [LabourOfficeDivisionController::class, 'update'])->name('save-labour-office-division');
    Route::get('changestatus-labour-office-division/{id}', [LabourOfficeDivisionController::class, 'activation'])->name('changestatus-labour-office-division');
    Route::get('blocklabourofficedivision/{id}', [LabourOfficeDivisionController::class, 'block'])->name('blocklabourofficedivision');
    Route::get('/add-city/{id}', [LabourOfficeDivisionController::class, 'addCity'])->name('add-city');
    Route::get('/getOfficeCodes/{id}', [LabourOfficeDivisionController::class, 'getOfficeCodes'])->name('getOfficeCodes');
    Route::post('new-add-city', [LabourOfficeDivisionController::class, 'saveCities'])->name('new-add-city');
    Route::get('getZone', [LabourOfficeDivisionController::class, 'getZone'])->name('getZone');
    Route::get('getLabourDistrict', [LabourOfficeDivisionController::class, 'getLabourDistrict'])->name('getLabourDistrict');
    // Route::get('complaint-list',[ComplaintListController::class,'index'])->name('complaint-list');
    // Route::get('getTemplate',[ComplaintListController::class, 'getTemplate'])->name('getTemplate');
    Route::get('cityDetail', [LabourOfficeDivisionController::class, 'cityDetail'])->name('cityDetail');
    Route::get('add-city/sync-cities/{id}', [LabourOfficeDivisionController::class, 'syncCities'])->name('sync-cities');

    Route::get('mail-template', [MailTemplateController::class, 'index'])->name('mail-template');
    Route::get('getTemplate/{id}', [MailTemplateController::class, 'getTemplate'])->name('getTemplate');
    Route::put('save-mail-template', [MailTemplateController::class, 'update'])->name('save-mail-template');
    Route::get('mail-template-list', [MailTemplateController::class, 'list'])->name('mail-template-list');
    Route::get('changestatus-mail-template/{id}', [MailTemplateController::class, 'activation'])->name('changestatus-mail-template');
    Route::get('blockmailtemplate/{id}', [MailTemplateController::class, 'block'])->name('blockmailtemplate');
    Route::get('/edit-mail-template/{id}', [MailTemplateController::class, 'edit'])->name('edit-mail-template');

    Route::get('sms-template', [SmsTemplateController::class, 'index'])->name('sms-template');
    Route::get('sms-template-list', [SmsTemplateController::class, 'list'])->name('sms-template-list');
    Route::get('/edit-sms-template/{id}', [SmsTemplateController::class, 'edit'])->name('edit-sms-template');
    Route::put('save-sms-template', [SmsTemplateController::class, 'update'])->name('save-sms-template');
    Route::get('changestatus-sms-template/{id}', [SmsTemplateController::class, 'activation'])->name('changestatus-sms-template');
    Route::get('blocksmstemplate/{id}', [SmsTemplateController::class, 'block'])->name('blocksmstemplate');

    Route::get('complain-status', [ComplainStatusController::class, 'index'])->name('complain-status');
    Route::post('new-complain-status', [ComplainStatusController::class, 'store'])->name('new-complain-status');
    Route::get('complain-status-list', [ComplainStatusController::class, 'list'])->name('complain-status-list');
    Route::get('/edit-complain-status/{id}', [ComplainStatusController::class, 'edit'])->name('edit-complain-status');
    Route::put('save-complain-status', [ComplainStatusController::class, 'update'])->name('save-complain-status');
    Route::get('changestatus-complain-status/{id}', [ComplainStatusController::class, 'activation'])->name('changestatus-complain-status');
    Route::get('blockcomplainstatus/{id}', [ComplainStatusController::class, 'block'])->name('blockcomplainstatus');

    Route::get('business-nature', [BusinessNatureController::class, 'index'])->name('business-nature');
    Route::post('new-business-nature', [BusinessNatureController::class, 'store'])->name('new-business-nature');
    Route::get('business-nature-list', [BusinessNatureController::class, 'list'])->name('business-nature-list');
    Route::get('/edit-business-nature/{id}', [BusinessNatureController::class, 'edit'])->name('edit-business-nature');
    Route::put('save-business-nature', [BusinessNatureController::class, 'update'])->name('save-business-nature');
    Route::get('changestatus-business-nature/{id}', [BusinessNatureController::class, 'activation'])->name('changestatus-business-nature');
    Route::get('blockbusinessnature/{id}', [BusinessNatureController::class, 'block'])->name('blockbusinessnature');

    Route::get('register-complaint', [RegisterComplaintController::class, 'index'])->name('register-complaint');
    // Route::get('dashboard', [RegisterComplaintController::class, 'index'])->name('dashboard');
    Route::post('new-register-complaint', [RegisterComplaintController::class, 'store'])->name('new-register-complaint');
    Route::get('action-pending-list', [ActionPendingListController::class, 'list'])->name('action-pending-list');

    Route::get('investigation-ongoing-list', [InvestigationOngoingListController::class, 'list'])->name('investigation-ongoing-list');
    Route::get('/calendar/{id}', [InvestigationOngoingListController::class, 'calendar'])->name('calendar');
    Route::get('/view-ongoing-complaint/{id}', [InvestigationOngoingListController::class, 'view'])->name('view-ongoing-complaint');
    Route::post('new-event', [InvestigationOngoingListController::class, 'createEvent'])->name('new-event');
    Route::get('/mail/{id}', [InvestigationOngoingListController::class, 'mail'])->name('mail');
    // Route::get('getMailTemplates/{id}/{lang}/{complaintID}', [InvestigationOngoingListController::class, 'getMailTemplates'])->name('getMailTemplates');
    Route::post('send-mail', [InvestigationOngoingListController::class, 'saveMail'])->name('send-mail');
    Route::post('print-mail', [InvestigationOngoingListController::class, 'print'])->name('print-mail');
    Route::get('print', [InvestigationOngoingListController::class, 'printview'])->name('print');
    Route::post('update-complain-detail', [InvestigationOngoingListController::class, 'updateComplainDetail'])->name('update-complain-detail');
    Route::get('getcomplainantDetails/{prefLang}/{id}', [InvestigationOngoingListController::class, 'getcomplainantDetails'])->name('getcomplainantDetails');
    Route::get('getletterTemplates/{letterTemplateID}/{prefLang}/{id}', [InvestigationOngoingListController::class, 'getletterTemplates'])->name('getletterTemplates');
    Route::post('send-letter', [InvestigationOngoingListController::class, 'saveLetter'])->name('send-letter');
    Route::post('print-letter', [InvestigationOngoingListController::class, 'printLetter'])->name('print-letter');
    Route::get('getLetterTempTitle/{prefLang}/{category}/{id}', [InvestigationOngoingListController::class, 'getLetterTempTitle'])->name('getLetterTempTitle');
    Route::get('getSentMail/{id}', [InvestigationOngoingListController::class, 'getSentMail'])->name('getSentMail');
    Route::get('getRecipient/{id}', [InvestigationOngoingListController::class, 'getRecipient'])->name('getRecipient');
    Route::get('getSentEMail/{id}', [InvestigationOngoingListController::class, 'getSentEMail'])->name('getSentEMail');
    Route::post('checkEventsDuplicate', [InvestigationOngoingListController::class, 'checkEventsDuplicate'])->name('checkEventsDuplicate');
    Route::get('/calculation/{id}', [InvestigationOngoingListController::class, 'calculation'])->name('calculation');
    Route::post('calculate-gratuity', [InvestigationOngoingListController::class, 'gratuitycalculation'])->name('calculate-gratuity');
    Route::post('calculate-minimum-wage', [InvestigationOngoingListController::class, 'minWagescalculation'])->name('calculate-minimum-wage');
    Route::get('calculation/delete/{id}', [InvestigationOngoingListController::class, 'deleteminwage'])->name('calculation');
    Route::post('send-nd', [InvestigationOngoingListController::class, 'saveNd'])->name('send-nd');

    Route::get('log-activity-list', [LogActivityController::class, 'list'])->name('log-activity-list');
    Route::get('blocklog/{id}', [LogActivityController::class, 'block'])->name('blocklog');
    // Route::get('search-log', [LogActivityController::class, 'searchLog'])->name('search-log');

    Route::get('/upload-document/{id}', [ActionPendingListController::class, 'upload'])->name('upload-document');
    Route::post('save-upload-document', [ActionPendingListController::class, 'uploadfiles'])->name('save-upload-document');
    Route::get('/view/{id}', [ActionPendingListController::class, 'view'])->name('view');
    Route::get('/edit-register-complaint/{id}', [ActionPendingListController::class, 'edit'])->name('edit-register-complaint');
    Route::get('edit-register-complaint/delete/{id}', [ActionPendingListController::class, 'deletedocument'])->name('edit-register-complaints');
    Route::get('edit-register-complaint/delete-officer/{id}', [ActionPendingListController::class, 'deleteofficer'])->name('edit-register-complaints');
    Route::post('save-register-complaint', [ActionPendingListController::class, 'update'])->name('save-register-complaint');
    Route::get('/complaint-status-history/{id}', [ActionPendingListController::class, 'complaintStatus'])->name('complaint-status-history');
    Route::get('/complain-print/{id}', [ActionPendingListController::class, 'printcomplaint'])->name('complain-print');

    Route::get('search-complaint', [SearchComplaintController::class, 'index'])->name('search-complaint');
    Route::get('/view-search', [SearchComplaintController::class, 'searchdetails'])->name('view-search');

    Route::get('/complaint-action/{id}', [ActionPendingListController::class, 'complaintAction'])->name('complaint-action');
    Route::post('forward-actionpending', [ActionPendingListController::class, 'forward'])->name('forward-actionpending');
    Route::post('forward-request', [ActionPendingListController::class, 'forward'])->name('forward-request');
    Route::post('tempclose-action', [ActionPendingListController::class, 'forward'])->name('tempclose-action');
    Route::post('close-action', [ActionPendingListController::class, 'forward'])->name('close-action');
    // Route::post('tempclose-action', [ActionPendingListController::class, 'tempclose'])->name('tempclose-action');
    // Route::post('close-action', [ActionPendingListController::class, 'close'])->name('close-action');
    Route::post('status-update-action', [ActionPendingListController::class, 'statusUpdate'])->name('status-update-action');
    Route::post('assignlo-action', [ActionPendingListController::class, 'assignlo'])->name('assignlo-action');
    Route::get('sent-approval-list',[ActionPendingListController::class,'sentApprovalList'])->name('sent-approval-list');
    Route::get('wca-complaint-list',[ActionPendingListController::class,'wcaComplaintList'])->name('wca-complaint-list');
    Route::get('wca-complaint-officewise-list/{id}',[ActionPendingListController::class,'wcaComplaintOfficeWiseList'])->name('wca-complaint-officewise-list');

    Route::get('pending-approval-list',[PendingApprovalListController::class,'list'])->name('pending-approval-list');
    Route::get('/pending-approval-view/{id}', [PendingApprovalListController::class, 'view'])->name('pending-approval-view');
    Route::get('/pending-approval-status-history/{id}', [PendingApprovalListController::class, 'complaintStatus'])->name('pending-approval-status-history');
    Route::get('/pending-approval-action/{id}', [PendingApprovalListController::class, 'complaintAction'])->name('pending-approval-action');
    Route::post('forward-pendingapproval', [PendingApprovalListController::class, 'forward'])->name('forward-pendingapproval');
    Route::get('approved-list',[PendingApprovalListController::class,'approvedList'])->name('approved-list');
    //Route::get('/approval-action-change/{id}', [PendingApprovalListController::class, 'changeAction'])->name('approval-action-change');
    Route::get('rejected-list',[PendingApprovalListController::class,'rejectedList'])->name('rejected-list');

    Route::get('temporary-closed-list', [TemporaryClosedListController::class, 'list'])->name('temporary-closed-list');
    Route::get('/temporary-closed-action/{id}', [TemporaryClosedListController::class, 'complaintAction'])->name('temporary-closed-action');
    Route::post('reopen', [TemporaryClosedListController::class, 'reopen'])->name('reopen');
    Route::post('close-action-temp-list', [TemporaryClosedListController::class, 'close'])->name('close-action-temp-list');
    Route::post('status-update-action-temp-list', [TemporaryClosedListController::class, 'statusUpdate'])->name('status-update-action-temp-list');
    Route::get('/view-temporary-closed-complaint/{id}', [TemporaryClosedListController::class, 'view'])->name('view-temporary-closed-complaint');

    Route::get('closed-list', [ClosedListController::class, 'list'])->name('closed-list');
    Route::get('/view-closed-complaint/{id}', [ClosedListController::class, 'view'])->name('view-closed-complaint');

    Route::get('pending-certificate-list',[PendingCertificateListController::class,'list'])->name('pending-certificate-list');
    Route::get('/pending-certificate-view/{id}', [PendingCertificateListController::class, 'view'])->name('pending-certificate-view');
    Route::get('/pending-certificate-status-history/{id}', [PendingCertificateListController::class, 'complaintStatus'])->name('pending-certificate-status-history');
    Route::get('/pending-certificate-action/{id}', [PendingCertificateListController::class, 'complaintAction'])->name('pending-certificate-action');
    Route::post('forward-pendingcertificate', [PendingCertificateListController::class, 'forward'])->name('forward-pendingcertificate');
    Route::get('certificate-approved-list',[PendingCertificateListController::class,'approvedList'])->name('certificate-approved-list');
    //Route::get('/certificate-action-change/{id}', [PendingCertificateListController::class, 'changeAction'])->name('certificate-action-change');
    Route::get('certificate-rejected-list',[PendingCertificateListController::class,'rejectedList'])->name('certificate-rejected-list');

    Route::get('pending-chargesheet-list',[PendingChargesheetListController::class,'list'])->name('pending-chargesheet-list');
    Route::get('/pending-chargesheet-view/{id}', [PendingChargesheetListController::class, 'view'])->name('pending-chargesheet-view');
    Route::get('/pending-chargesheet-status-history/{id}', [PendingChargesheetListController::class, 'complaintStatus'])->name('pending-chargesheet-status-history');
    Route::get('/pending-chargesheet-action/{id}', [PendingChargesheetListController::class, 'complaintAction'])->name('pending-chargesheet-action');
    Route::post('forward-pendingchargesheet', [PendingChargesheetListController::class, 'forward'])->name('forward-pendingchargesheet');
    Route::get('chargesheet-created-list',[PendingChargesheetListController::class,'approvedList'])->name('chargesheet-created-list');
    //Route::get('/chargesheet-action-change/{id}', [PendingChargesheetListController::class, 'changeAction'])->name('chargesheet-action-change');
    Route::get('chargesheet-rejected-list',[PendingChargesheetListController::class,'rejectedList'])->name('chargesheet-rejected-list');

    Route::get('/report-complaint', [ReportComplaintController::class, 'reportcomplaint'])->name('report-complaint');
    Route::get('/report-complaint-year', [ReportComplaintController::class, 'reportcomplaintyear'])->name('report-complaint-year');

    Route::get('/report-search', [ReportComplaintController::class, 'reportsearch'])->name('report-search');

    Route::get('legal-certificate-pending-list',[PendingCertificateListController::class,'pendinglist'])->name('legal-certificate-pending-list');
    Route::get('plaint-chargesheet-pending-list',[PendingChargesheetListController::class,'pendinglist'])->name('plaint-chargesheet-pending-list');
    Route::get('recovery-pending-list',[PendingRecoveryListController::class,'pendinglist'])->name('recovery-pending-list');

    Route::post('create-complaintcopy', [ActionPendingListController::class, 'createcomplaintcopy'])->name('create-complaintcopy');

    Route::get('pending-recovery-list',[PendingRecoveryListController::class,'list'])->name('pending-recovery-list');
    Route::get('/pending-recovery-status-history/{id}', [PendingRecoveryListController::class, 'complaintStatus'])->name('pending-recovery-status-history');
    Route::get('/pending-recovery-action/{id}', [PendingRecoveryListController::class, 'complaintAction'])->name('pending-recovery-action');
    Route::post('forward-recovery', [PendingRecoveryListController::class, 'forward'])->name('forward-recovery');
    Route::post('forward-action-recovery', [PendingRecoveryListController::class, 'forwardActionRecovery'])->name('forward-action-recovery');

    Route::get('/report-complaint-eachact', [ReportComplaintController::class, 'reportEachAct'])->name('report-complaint-eachact');

    Route::get('checkNic', [RegisterComplaintController::class, 'checkNic'])->name('checkNic');

    Route::get('encrypt', [RegisterComplaintController::class, 'encryptNic'])->name('encrypt');

    Route::get('complaint-remark', [ComplaintRemarkController::class, 'index'])->name('complaint-remark');
    Route::post('new-complaint-remark', [ComplaintRemarkController::class, 'store'])->name('new-complaint-remark');
    Route::get('complaint-remark-list', [ComplaintRemarkController::class, 'list'])->name('complaint-remark-list');
    Route::get('/edit-complaint-remark/{id}', [ComplaintRemarkController::class, 'edit'])->name('edit-complaint-remark');
    Route::put('save-complaint-remark', [ComplaintRemarkController::class, 'update'])->name('save-complaint-remark');
    Route::get('changestatus-complaint-remark/{id}', [ComplaintRemarkController::class, 'activation'])->name('changestatus-complaint-remark');
    Route::get('blockcomplaintremark/{id}', [ComplaintRemarkController::class, 'block'])->name('blockcomplaintremark');

    Route::get('send-sms', [SendSmsController::class, 'index'])->name('send-sms');
    Route::post('send-sms-to', [SendSmsController::class, 'store'])->name('send-sms-to');

    Route::post('status-update-action-certificate-list', [PendingCertificateListController::class, 'statusUpdate'])->name('status-update-action-certificate-list');
    Route::post('status-update-action-chargesheet-list', [PendingChargesheetListController::class, 'statusUpdate'])->name('status-update-action-chargesheet-list');

    Route::get('appeal-pending-list', [ActionPendingListController::class, 'pendingAppealList'])->name('appeal-pending-list');

    Route::get('/report-complaint-eachoffice', [ReportComplaintController::class, 'reportEachOffice'])->name('report-complaint-eachoffice');

    Route::get('user-manual', [UserManualController::class, 'index'])->name('user-manual');
    Route::get('office-code-list', [UserManualController::class, 'officeCodeList'])->name('office-code-list');
    Route::get('category-code-list', [UserManualController::class, 'categoryCodeList'])->name('category-code-list');
    Route::get('account-request-form', [UserManualController::class, 'accountRequestFormList'])->name('account-request-form');
    Route::get('cms-circular', [UserManualController::class, 'cmscircular'])->name('cms-circular');

    Route::get('sms-log-list', [SmsLogController::class, 'list'])->name('sms-log-list');
    Route::get('sms-blocklog/{id}', [SmsLogController::class, 'block'])->name('sms-blocklog');

    Route::get('email-log-list', [EmailLogController::class, 'list'])->name('email-log-list');
    Route::get('email-blocklog/{id}', [EmailLogController::class, 'block'])->name('email-blocklog');

    Route::get('/report-complaint-officerwise', [ReportComplaintController::class, 'reportOfficerWise'])->name('report-complaint-officerwise');

    Route::get('/report-complaint-officewise', [ReportComplaintController::class, 'reportOfficeWise'])->name('report-complaint-officewise');
    Route::get('getLoUnassignComplaints', [ReportComplaintController::class, 'getLoUnassignComplaints'])->name('getLoUnassignComplaints');

    Route::get('getComplaintStatus', [SearchComplaintController::class, 'getComplaintStatus'])->name('getComplaintStatus');
    Route::get('/report-complaint-by-period', [ReportComplaintController::class, 'reportcomplaintbyperiod'])->name('report-complaint-by-period');

    Route::get('send-multiple-sms', [MultipleSmsRecordController::class, 'index'])->name('send-multiple-sms');
    Route::get('send-multiple-email', [MultipleSmsRecordController::class, 'email'])->name('send-multiple-email');

    Route::get('send-test-mail', [TestEmailController::class, 'index'])->name('send-test-mail');

    Route::get('database-backup', [DatabaseBackupController::class, 'index'])->name('database-backup');
    Route::post('our_backup_database', [DatabaseBackupController::class, 'our_backup_database'])->name('our_backup_database');

    Route::get('/report-complaint-transfer', [ReportComplaintController::class, 'reportTransferComplaint'])->name('report-complaint-transfer');
    Route::get('/report-categorywise', [ReportComplaintController::class, 'reportCategoryWise'])->name('report-categorywise');
    Route::get('/report-performance', [ReportComplaintController::class, 'reportPerformance'])->name('report-performance');

    Route::get('assign-complaint-list', [ActionPendingListController::class, 'assignComplaintList'])->name('assign-complaint-list');

    Route::get('event-title', [EventTitleController::class, 'index'])->name('event-title');
    Route::post('new-event-title', [EventTitleController::class, 'store'])->name('new-event-title');
    Route::get('event-title-list', [EventTitleController::class, 'list'])->name('event-title-list');
    Route::get('/edit-event-title/{id}', [EventTitleController::class, 'edit'])->name('edit-event-title');
    Route::put('save-event-title', [EventTitleController::class, 'update'])->name('save-event-title');
    Route::get('changestatus-event-title/{id}', [EventTitleController::class, 'activation'])->name('changestatus-event-title');
    Route::get('blockeventtitle/{id}', [EventTitleController::class, 'block'])->name('blockeventtitle');

    Route::get('event-list', [EventController::class, 'list'])->name('event-list');
    Route::get('/edit-event/{id}', [EventController::class, 'edit'])->name('edit-event');
    Route::put('save-event', [EventController::class, 'update'])->name('save-event');

    Route::get('/report-time-analysis', [ReportComplaintController::class, 'reporttimeanalysis'])->name('report-time-analysis');

});

require __DIR__ . '/auth.php';
