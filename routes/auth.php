<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Adminpanel\UserController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

//RAJITHA///////////////////////
use App\Http\Controllers\Adminpanel\Masterdata\VehicletypeContoller;
use App\Http\Controllers\Adminpanel\Masterdata\VehiclemakeContoller;
use App\Http\Controllers\Adminpanel\Masterdata\VehiclemodelContoller;
use App\Http\Controllers\Adminpanel\Masterdata\VehiclesubmodelContoller;
use App\Http\Controllers\Adminpanel\Masterdata\ProducttypeContoller;
use App\Http\Controllers\Adminpanel\Masterdata\ProductfamilyContoller;
use App\Http\Controllers\Adminpanel\Masterdata\AxleContoller;
use App\Http\Controllers\Adminpanel\Masterdata\BrakesystemContoller;
use App\Http\Controllers\Adminpanel\Masterdata\VentilationtypeContoller;
use App\Http\Controllers\Adminpanel\Masterdata\CompetitorbrandsContoller;
use App\Http\Controllers\Adminpanel\Masterdata\Original_equipment_vehiclesContoller;
use App\Http\Controllers\Adminpanel\Masterdata\SeriesContoller;
use App\Http\Controllers\Adminpanel\Masterdata\EnginetypeContoller;
use App\Http\Controllers\Adminpanel\Masterdata\DrivetypeContoller;
use App\Http\Controllers\Adminpanel\Masterdata\BodytypeContoller;
use App\Http\Controllers\Adminpanel\Masterdata\TransmissionContoller;

use App\Http\Controllers\Adminpanel\WorkshopsContoller;
use App\Http\Controllers\Adminpanel\NewslettersContoller;
use App\Http\Controllers\Adminpanel\TrainingContoller;
use App\Http\Controllers\Adminpanel\ExamContoller;
use App\Http\Controllers\Adminpanel\WarrentyController;

use App\Http\Controllers\LoginController;
//frontend
Route::get('/workshop/login', [LoginController::class, 'create'])
    ->middleware('guest')
    ->name('workshop.login');

Route::post('/workshop/login', [LoginController::class, 'authenticate'])
    ->middleware('guest')->name('workshop.login');

//backend 
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
    ->middleware('auth')
    ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
    ->middleware('auth');

Route::get('admin/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('admin.logout');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::put('update-role', [RoleController::class, 'update'])->name('update-role');
    Route::resource('users', UserController::class);
    Route::get('users-list',[UserController::class,'list'])->name('users-list');
    Route::put('save-user', [UserController::class, 'update'])->name('save-user');
    Route::get('changestatus-user/{id}', [UserController::class, 'activation'])->name('changestatus-user');
    Route::get('blockuser/{id}', [UserController::class, 'block'])->name('blockuser');
    Route::post('checkEmailAvailability', [UserController::class, 'checkEmailAvailability'])->name('checkEmailAvailability');
    Route::resource('products', ProductController::class);

    Route::get('complain-category', [ComplainCategoryController::class, 'index'])->name('complain-category');
    Route::post('new-category', [ComplainCategoryController::class, 'store'])->name('new-category');
    Route::get('complain-category-list', [ComplainCategoryController::class, 'list'])->name('complain-category-list');
    Route::get('/edit-category/{id}', [ComplainCategoryController::class, 'edit'])->name('edit-category');
    Route::put('save-complain-category', [ComplainCategoryController::class, 'update'])->name('save-complain-category');
    Route::get('/status-category/{id}', [ComplainCategoryController::class, 'activation'])->name('status-category');
    
    ///////////////////RAJITHA MASTER DATA ////////////////////////////
    Route::get('new-vehicletype', [VehicletypeContoller::class, 'index'])->name('new-vehicletype');
    Route::post('new-vehicletype', [VehicletypeContoller::class, 'store'])->name('new-vehicletype');
    Route::get('vehicletype-list', [VehicletypeContoller::class, 'datalist'])->name('vehicletype-list');
    Route::get('/edit-vehicletype/{id}', [VehicletypeContoller::class, 'edit'])->name('edit-vehicletype');
    Route::get('/status-vehicletype/{id}', [VehicletypeContoller::class, 'activation'])->name('status-vehicletype');
    
    Route::get('new-vehiclemake', [VehiclemakeContoller::class, 'index'])->name('new-vehiclemake');
    Route::post('new-vehiclemake', [VehiclemakeContoller::class, 'store'])->name('new-vehiclemake');
    Route::get('vehiclemake-list', [VehiclemakeContoller::class, 'datalist'])->name('vehiclemake-list');
    Route::get('/edit-vehiclemake/{id}', [VehiclemakeContoller::class, 'edit'])->name('edit-vehiclemake');
    Route::get('/status-vehiclemake/{id}', [VehiclemakeContoller::class, 'activation'])->name('status-vehiclemake');
    
    Route::get('new-vehiclemodel', [VehiclemodelContoller::class, 'index'])->name('new-vehiclemodel');
    Route::post('new-vehiclemodel', [VehiclemodelContoller::class, 'store'])->name('new-vehiclemodel');
    Route::get('vehiclemodel-list', [VehiclemodelContoller::class, 'datalist'])->name('vehiclemodel-list');
    Route::get('/edit-vehiclemodel/{id}', [VehiclemodelContoller::class, 'edit'])->name('edit-vehiclemodel');
    Route::get('/status-vehiclemodel/{id}', [VehiclemodelContoller::class, 'activation'])->name('status-vehiclemodel');
    
    Route::get('new-vehiclesubmodel', [VehiclesubmodelContoller::class, 'index'])->name('new-vehiclesubmodel');
    Route::post('new-vehiclesubmodel', [VehiclesubmodelContoller::class, 'store'])->name('new-vehiclesubmodel');
    Route::get('vehiclesubmodel-list', [VehiclesubmodelContoller::class, 'datalist'])->name('vehiclesubmodel-list');
    Route::get('/edit-vehiclesubmodel/{id}', [VehiclesubmodelContoller::class, 'edit'])->name('edit-vehiclesubmodel');
    Route::get('/status-vehiclesubmodel/{id}', [VehiclesubmodelContoller::class, 'activation'])->name('status-vehiclesubmodel');
    Route::get('get-make-model-list', [VehiclesubmodelContoller::class, 'get_vehicle_make_braches']) ->name('get-make-model-list');
    
    
    Route::get('new-producttype', [ProducttypeContoller::class, 'index'])->name('new-producttype');
    Route::post('new-producttype', [ProducttypeContoller::class, 'store'])->name('new-producttype');
    Route::get('producttype-list', [ProducttypeContoller::class, 'datalist'])->name('producttype-list');
    Route::get('/edit-producttype/{id}', [ProducttypeContoller::class, 'edit'])->name('edit-producttype');
    Route::get('/status-producttype/{id}', [ProducttypeContoller::class, 'activation'])->name('status-producttype');
    
    Route::get('new-productfamily', [ProductfamilyContoller::class, 'index'])->name('new-productfamily');
    Route::post('new-productfamily', [ProductfamilyContoller::class, 'store'])->name('new-productfamily');
    Route::get('productfamily-list', [ProductfamilyContoller::class, 'datalist'])->name('productfamily-list');
    Route::get('/edit-productfamily/{id}', [ProductfamilyContoller::class, 'edit'])->name('edit-productfamily');
    Route::get('/status-productfamily/{id}', [ProductfamilyContoller::class, 'activation'])->name('status-productfamily');
    
    Route::get('new-axle', [AxleContoller::class, 'index'])->name('new-axle');
    Route::post('new-axle', [AxleContoller::class, 'store'])->name('new-axle');
    Route::get('axle-list', [AxleContoller::class, 'datalist'])->name('axle-list');
    Route::get('/edit-axle/{id}', [AxleContoller::class, 'edit'])->name('edit-axle');
    Route::get('/status-axle/{id}', [AxleContoller::class, 'activation'])->name('status-axle');
    
    Route::get('new-brakesystem', [BrakesystemContoller::class, 'index'])->name('new-brakesystem');
    Route::post('new-brakesystem', [BrakesystemContoller::class, 'store'])->name('new-brakesystem');
    Route::get('brakesystem-list', [BrakesystemContoller::class, 'datalist'])->name('brakesystem-list');
    Route::get('/edit-brakesystem/{id}', [BrakesystemContoller::class, 'edit'])->name('edit-brakesystem');
    Route::get('/status-brakesystem/{id}', [BrakesystemContoller::class, 'activation'])->name('status-brakesystem');
    
    Route::get('new-ventilation-type', [VentilationtypeContoller::class, 'index'])->name('new-ventilation-type');
    Route::post('new-ventilation-type', [VentilationtypeContoller::class, 'store'])->name('new-ventilation-type');
    Route::get('ventilation-type-list', [VentilationtypeContoller::class, 'datalist'])->name('ventilation-type-list');
    Route::get('/edit-ventilation-type/{id}', [VentilationtypeContoller::class, 'edit'])->name('edit-ventilation-type');
    Route::get('/status-ventilation-type/{id}', [VentilationtypeContoller::class, 'activation'])->name('status-ventilation-type');
    
    Route::get('new-competitor-brands', [CompetitorbrandsContoller::class, 'index'])->name('new-competitor-brands');
    Route::post('new-competitor-brands', [CompetitorbrandsContoller::class, 'store'])->name('new-competitor-brands');
    Route::get('competitor-brandse-list', [CompetitorbrandsContoller::class, 'datalist'])->name('competitor-brands-list');
    Route::get('/edit-competitor-brands/{id}', [CompetitorbrandsContoller::class, 'edit'])->name('edit-competitor-brands');
    Route::get('/status-competitor-brands/{id}', [CompetitorbrandsContoller::class, 'activation'])->name('status-competitor-brands');
    
    Route::get('new-original-equipment-for-vehicles', [Original_equipment_vehiclesContoller::class, 'index'])->name('new-original-equipment-for-vehicles');
    Route::post('new-original-equipment-for-vehicles', [Original_equipment_vehiclesContoller::class, 'store'])->name('new-original-equipment-for-vehicles');
    Route::get('original-equipment-for-vehicles-list', [Original_equipment_vehiclesContoller::class, 'datalist'])->name('original-equipment-for-vehicles-list');
    Route::get('/edit-original-equipment-for-vehicles/{id}', [Original_equipment_vehiclesContoller::class, 'edit'])->name('edit-original-equipment-for-vehicles');
    Route::get('/status-original-equipment-for-vehicles/{id}', [Original_equipment_vehiclesContoller::class, 'activation'])->name('status-original-equipment-for-vehicles');
    
    Route::get('new-series', [SeriesContoller::class, 'index'])->name('new-series');
    Route::post('new-series', [SeriesContoller::class, 'store'])->name('new-series');
    Route::get('series-list', [SeriesContoller::class, 'datalist'])->name('series-list');
    Route::get('/edit-series/{id}', [SeriesContoller::class, 'edit'])->name('edit-series');
    Route::get('/status-series/{id}', [SeriesContoller::class, 'activation'])->name('status-series');
    
    Route::get('new-enginetype', [EnginetypeContoller::class, 'index'])->name('new-enginetype');
    Route::post('new-enginetype', [EnginetypeContoller::class, 'store'])->name('new-enginetype');
    Route::get('enginetype-list', [EnginetypeContoller::class, 'datalist'])->name('enginetype-list');
    Route::get('/edit-enginetype/{id}', [EnginetypeContoller::class, 'edit'])->name('edit-enginetype');
    Route::get('/status-enginetype/{id}', [EnginetypeContoller::class, 'activation'])->name('status-enginetype');
    
    Route::get('new-drivetype', [DrivetypeContoller::class, 'index'])->name('new-drivetype');
    Route::post('new-drivetype', [DrivetypeContoller::class, 'store'])->name('new-drivetype');
    Route::get('drivetype-list', [DrivetypeContoller::class, 'datalist'])->name('drivetype-list');
    Route::get('/edit-drivetype/{id}', [DrivetypeContoller::class, 'edit'])->name('edit-drivetype');
    Route::get('/status-drivetype/{id}', [DrivetypeContoller::class, 'activation'])->name('status-drivetype');
    
    Route::get('new-bodytype', [BodytypeContoller::class, 'index'])->name('new-bodytype');
    Route::post('new-bodytype', [BodytypeContoller::class, 'store'])->name('new-bodytype');
    Route::get('bodytype-list', [BodytypeContoller::class, 'datalist'])->name('bodytype-list');
    Route::get('/edit-bodytype/{id}', [BodytypeContoller::class, 'edit'])->name('edit-bodytype');
    Route::get('/status-bodytype/{id}', [BodytypeContoller::class, 'activation'])->name('status-bodytype');
    
    Route::get('new-transmission', [TransmissionContoller::class, 'index'])->name('new-transmission');
    Route::post('new-transmission', [TransmissionContoller::class, 'store'])->name('new-transmission');
    Route::get('transmission-list', [TransmissionContoller::class, 'datalist'])->name('transmission-list');
    Route::get('/edit-transmission/{id}', [TransmissionContoller::class, 'edit'])->name('edit-transmission');
    Route::get('/status-transmission/{id}', [TransmissionContoller::class, 'activation'])->name('status-transmission');
    
    //////////////////End MASTER DATA //////////////////////////////////////////
    
    /////////////////WORKSHOP///////////////////////////////////////
    
     Route::get('new-workshop', [WorkshopsContoller::class, 'index'])->name('new-workshop');
    Route::post('new-workshop', [WorkshopsContoller::class, 'store'])->name('new-workshop');
    Route::get('workshop-list', [WorkshopsContoller::class, 'datalist'])->name('workshop-list');
    Route::get('/edit-workshop/{id}', [WorkshopsContoller::class, 'edit'])->name('edit-workshop');
    Route::get('/status-workshop/{id}', [WorkshopsContoller::class, 'activation'])->name('status-workshop');
    Route::get('get-state-cities', [WorkshopsContoller::class, 'get_state_cities']) ->name('get-state-cities');

      /////////////////TRAINING///////////////////////////////////////
    
      Route::get('new-training', [TrainingContoller::class, 'index'])->name('new-training');
      Route::post('new-training', [TrainingContoller::class, 'store'])->name('new-training');
      Route::get('trainings-list', [TrainingContoller::class, 'datalist'])->name('trainings-list');
      Route::get('/edit-training/{id}', [TrainingContoller::class, 'edit'])->name('edit-training');
      Route::get('/status-training/{id}', [TrainingContoller::class, 'activation'])->name('status-training');
      Route::get('/edit-training/delete/{id}', [TrainingContoller::class, 'deletemeterial'])->name('delete-training');
     
    //// EXAM //////////////  
      Route::get('new-exam', [ExamContoller::class, 'index'])->name('new-exam');
      Route::post('new-exam', [ExamContoller::class, 'store'])->name('new-exam');
      Route::get('exam-list', [ExamContoller::class, 'datalist'])->name('exam-list');
      Route::get('/edit-exam/{id}', [ExamContoller::class, 'edit'])->name('edit-exam');
      Route::get('/status-exam/{id}', [ExamContoller::class, 'activation'])->name('status-exam');


    //// EXAM QUESTIONS //////////////  
        Route::get('new-examquestion-quiz', [ExamContoller::class, 'questions_landing'])->name('new-examquestion-quiz');
        Route::post('new-examquestion-quiz', [ExamContoller::class, 'store_quiz'])->name('new-examquestion-quiz');
        Route::get('exam-questions-list', [ExamContoller::class, 'questions_datalist'])->name('exam-questions-list');
        Route::get('/edit-exam-quiz/{id}', [ExamContoller::class, 'edit_question'])->name('edit-exam-quiz');
        Route::get('/status-quiz-exam/{id}', [ExamContoller::class, 'quiz_activation'])->name('status-quiz-exam');

        Route::get('new-structured-question', [ExamContoller::class, 'structured_landing'])->name('new-structured-question');
        Route::post('new-structured-question', [ExamContoller::class, 'store_structured'])->name('new-structured-question');
        Route::get('structured-questions-list', [ExamContoller::class, 'structured_datalist'])->name('structured-questions-list');
        Route::get('/edit-structured-quiz/{id}', [ExamContoller::class, 'edit_structured_question'])->name('edit-structured-quiz');
        Route::get('/status-structured-exam/{id}', [ExamContoller::class, 'structured_activation'])->name('status-structured-exam');

    //Warrenty Incendents

    Route::get('new-warrenty-incedent', [WarrentyController::class, 'index'])->name('new-warrenty-incedent');
    Route::post('new-warrenty-incedent', [WarrentyController::class, 'store'])->name('new-warrenty-incedent');
    Route::get('warrenty-incedent-list', [WarrentyController::class, 'datalist'])->name('warrenty-incedent-list');
    Route::get('/edit-warrenty-incedent/{id}', [WarrentyController::class, 'edit'])->name('edit-warrenty-incedent');
    Route::post('/exportto_excel', [WarrentyController::class, 'export'])->name('exportto_excel');
    Route::get('/export_excel', [WarrentyController::class, 'export'])->name('export_excel');

    /////////////////Newsletters///////////////////////////////////////
    
     Route::get('new-newsletters', [NewslettersContoller::class, 'index'])->name('new-newsletters');
    Route::post('new-newsletters', [NewslettersContoller::class, 'store'])->name('new-newsletters');
    Route::get('newsletters-list', [NewslettersContoller::class, 'datalist'])->name('newsletters-list');
    Route::get('/edit-newsletters/{id}', [NewslettersContoller::class, 'edit'])->name('edit-newsletters');
    Route::get('/status-newsletters/{id}', [NewslettersContoller::class, 'activation'])->name('status-newsletters');
    Route::get('remove-image/{id}', [NewslettersContoller::class, 'get_state_cities']) ->name('remove-image');
});
