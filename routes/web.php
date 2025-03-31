<?php

use Illuminate\Support\Facades\Artisan;
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
Route::get('/clear-cache', function() {

	Artisan::call('config:clear');
	Artisan::call('cache:clear');
	Artisan::call('config:cache');
	Artisan::call('optimize:clear');

	echo "config cleared";

});



Route::get('/', function () {
    if(Auth::check()){return Redirect::to('/');}
    return view('auth.login');
});
Route::post('custom-login', [App\Http\Controllers\Auth\LoginController::class,'customLogin'])->name('custom-login');
Auth::routes();

Route::get('project/floorPlanList/{id}',[App\Http\Controllers\ProjectController2::class,'floorPlanList'])->name('project/floorPlanList');
Route::get('project/get-floor-plan-doors', [App\Http\Controllers\ProjectController2::class,'getFloorPlanDoors'])->name('project/get-floor-plan-doors');
Route::post('project/add-floor-plan-door', [App\Http\Controllers\ProjectController2::class,'addFloorPlanDoor'])->name('project/add-floor-plan-door');
Route::post('project/remove-floor-plan-door', [App\Http\Controllers\ProjectController2::class,'removeFloorPlanDoor'])->name('project/remove-floor-plan-door');
Route::post('project/floor-door-list',[App\Http\Controllers\ProjectController2::class,'floorDoorList'])->name('project/floor-door-list');

Route::post('admin/notification',[App\Http\Controllers\DashboardController::class,'notificationShow'])->name('notification');

Route::get('/', [App\Http\Controllers\DashboardController::class,'index'])->name('Dashboard');
Route::post('/download', [App\Http\Controllers\DashboardController::class,'download'])->name('download');
// Send To Client


///reset password for admin & company
Route::get('ChargePassword', [App\Http\Controllers\ResetPasswordController::class,'index'])->name('ChargePassword');
Route::get('ChargePassword/{id}', [App\Http\Controllers\ResetPasswordController::class,'index'])->name('ChargePassword/');
Route::post('ChangePassword', [App\Http\Controllers\ResetPasswordController::class,'change_password'])->name('ChangePassword');
///reset password for admin & company

///reset password for admin & company
Route::get('ForgotPassword', [App\Http\Controllers\ForgotPasswordController::class,'forgotpassword'])->name('ForgotPassword');
Route::get('OTP', [App\Http\Controllers\ForgotPasswordController::class,'otp'])->name('OTP');
Route::post('OTP', [App\Http\Controllers\ForgotPasswordController::class,'otpstore'])->name('OTP');
Route::get('ResetPassword/{token}', [App\Http\Controllers\ForgotPasswordController::class,'resetpassword'])->name('reset.password.get');
Route::post('ResetPasswordstore', [App\Http\Controllers\ForgotPasswordController::class,'resetstore'])->name('reset.password.post');
///reset password for admin & company

Route::get('quotationApproval/{qId}/{vId}', [App\Http\Controllers\SendToClientController::class,'quotationApproval'])->name('quotationApproval');
Route::post('quotaionAccept', [App\Http\Controllers\SendToClientController::class,'quotaionAccept'])->name('quotaionAccept');
Route::post('quotaionReject', [App\Http\Controllers\SendToClientController::class,'quotaionReject'])->name('quotaionReject');

Route::middleware('auth:web')->group(function(){

Route::get('Admin/Profile', [App\Http\Controllers\ResetPasswordController::class,'admin_profile'])->name('admin/profile');

 ///create multiple admins
Route::prefix('admins')->group(function () {
    Route::get('/add',[App\Http\Controllers\AdminController::class,'add'])->name('admins/add');
    Route::get('/list', [App\Http\Controllers\AdminController::class,'list'])->name('admins/list');
    Route::get('/details/{id}', [App\Http\Controllers\AdminController::class,'details'])->name('admins/details');
    Route::get('/profile', [App\Http\Controllers\AdminController::class,'profile'])->name('admins/profile');
    Route::get('/edit/{id}', [App\Http\Controllers\AdminController::class,'edit'])->name('admins/edit');
    Route::post('/store', [App\Http\Controllers\AdminController::class,'store'])->name('admins/store');
    Route::post('/delete', [App\Http\Controllers\AdminController::class,'delete'])->name('admins/delete');

});

  ///architect
 Route::prefix('Architect')->group(function () {
    Route::get('/add',[App\Http\Controllers\ArchitectController::class,'add'])->name('Architect/add');
    Route::get('/list', [App\Http\Controllers\ArchitectController::class,'list'])->name('Architect/list');
    Route::get('/details/{id}', [App\Http\Controllers\ArchitectController::class,'details'])->name('Architect/details');
    Route::get('/profile', [App\Http\Controllers\ArchitectController::class,'profile'])->name('Architect/profile');
    Route::get('/edit/{id}', [App\Http\Controllers\ArchitectController::class,'edit'])->name('Architect/edit');
    Route::post('/store', [App\Http\Controllers\ArchitectController::class,'store'])->name('Architect/store');
    Route::post('/deleteArchitect', [App\Http\Controllers\ArchitectController::class,'deleteArchitect'])->name('deleteArchitect');

    });


Route::prefix('customer')->group(function () {
    Route::get('/add', [App\Http\Controllers\CustomerController::class,'add'])->name('customer/add');
    Route::get('/list', [App\Http\Controllers\CustomerController::class,'list'])->name('customer/list');
    // Route::match(['get','post'],'/edit', [App\Http\Controllers\CustomerController::class,'edit'])->name('customer/edit');
    Route::get('/details/{id}', [App\Http\Controllers\CustomerController::class,'details'])->name('customer/details');
    Route::get('/edit/{id}', [App\Http\Controllers\CustomerController::class,'edit'])->name('customer/edit');
    Route::post('/store', [App\Http\Controllers\CustomerController::class,'store'])->name('customer/store');
    // Route::get('/assign-form', [App\Http\Controllers\CustomerController::class,'assign_form'])->name('assign-form');
});


Route::prefix('contractor')->group(function () {
    Route::get('/add', [App\Http\Controllers\ContractorController::class,'add'])->name('contractor/add');
    Route::get('/list', [App\Http\Controllers\ContractorController::class,'list'])->name('contractor/list');
    // Route::match(['get','post'],'/edit', [App\Http\Controllers\ContractorController::class,'edit'])->name('customer/edit');
    Route::get('/details/{id}', [App\Http\Controllers\ContractorController::class,'details'])->name('contractor/details');
    Route::get('/edit/{id}', [App\Http\Controllers\ContractorController::class,'edit'])->name('contractor/edit');
    Route::post('/store', [App\Http\Controllers\ContractorController::class,'store'])->name('contractor/store');
    Route::post('/deleteContractor', [App\Http\Controllers\ContractorController::class,'deleteContractor'])->name('deleteContractor');
    Route::get('/profile', [App\Http\Controllers\ContractorController::class,'profile'])->name('contractor/profile');
    // Route::get('/assign-form', [App\Http\Controllers\ContractorController::class,'assign_form'])->name('assign-form');
});


Route::prefix('company')->group(function () {
    Route::get('/add', [App\Http\Controllers\CompanyController::class,'add'])->name('company/add');
    Route::get('/list', [App\Http\Controllers\CompanyController::class,'list'])->name('company/list');
    Route::get('/assign-form', [App\Http\Controllers\CompanyController::class,'assign_form'])->name('assign-form');
    Route::get('/details/{id}', [App\Http\Controllers\CompanyController::class,'details'])->name('company/details');
    Route::get('/profile', [App\Http\Controllers\CompanyController::class,'profile'])->name('company/profile');
    // Route::match(['get','post'],'/details', [App\Http\Controllers\CompanyController::class,'details'])->name('company/details');
    Route::get('/edit/{id}', [App\Http\Controllers\CompanyController::class,'edit'])->name('company/edit');
    Route::get('/edit-profile/{id}', [App\Http\Controllers\CompanyController::class,'editProfile'])->name('company/edit-profile');
    Route::post('/useremail_check', [App\Http\Controllers\CompanyController::class,'useremail_check'])->name('company/useremail_check');
    Route::post('/store', [App\Http\Controllers\CompanyController::class,'store'])->name('company/store');
    Route::post('/companyStore', [App\Http\Controllers\CompanyController::class,'companyStore'])->name('company/companyStore');
    Route::post('/assign-form-user/store', [App\Http\Controllers\CompanyController::class,'assign_form_user_store'])->name('assign-form-user/store');
    Route::post('/deleteCompany', [App\Http\Controllers\CompanyController::class,'deleteCompany'])->name('deleteCompany');

});


Route::prefix('user')->group(function () {
    Route::get('/add', [App\Http\Controllers\UserController::class,'add'])->name('user/add');
    Route::get('/list', [App\Http\Controllers\UserController::class,'list'])->name('user/list');
    Route::get('/details/{id}', [App\Http\Controllers\UserController::class,'details'])->name('user/details');
    Route::get('/profile', [App\Http\Controllers\UserController::class,'profile'])->name('user/profile');
    Route::get('/edit/{id}', [App\Http\Controllers\UserController::class,'edit'])->name('user/edit');
    // Route::match(['get','post'],'/details', [App\Http\Controllers\UserController::class,'details'])->name('user/details');
    // Route::match(['get','post'],'/edit', [App\Http\Controllers\UserController::class,'edit'])->name('user/edit');
    Route::post('/store', [App\Http\Controllers\UserController::class,'store'])->name('user/store');
    Route::post('/delete', [App\Http\Controllers\UserController::class,'delete'])->name('user/delete');
});

Route::prefix('items')->group(function () {
    Route::get('/add', [App\Http\Controllers\ItemListController::class,'index'])->name('items/add');
    Route::get('/tab', [App\Http\Controllers\ItemListController::class,'tab'])->name('items/tab');
    Route::post('/fire-rating-filter',[App\Http\Controllers\ItemListController::class,'filterFireRating'])->name('items/fire-rating-filter');
    Route::post('/glass-glazing-filter',[App\Http\Controllers\ItemListController::class,'glassGlazingFilter'])->name('items/glass-glazing-filter');
    Route::post('/glazing-filter',[App\Http\Controllers\ItemListController::class,'GlazingFilter'])->name('items/glazing-filter');
    Route::post('filter-glazing-system',[App\Http\Controllers\ItemListController::class,'filter'])->name('items/filter-glazing-system');
    Route::post('/glass-type-filter',[App\Http\Controllers\ItemListController::class,'glassTypeFilter'])->name('items/glass-type-filter');
    Route::post('/overpanel-glass-type-filter',[App\Http\Controllers\ItemListController::class,'overpanelglassTypeFilter'])->name('items/overpanel-glass-type-filter');
    Route::post('/glazing-system-filter',[App\Http\Controllers\ItemListController::class,'fileterGlazingSystem'])->name('items/glazing-system-filter');
    Route::post('/liping-glazing-system-filter',[App\Http\Controllers\ItemListController::class,'LipingGlazingSystem'])->name('items/liping-glazing-system-filter');
    Route::post('/overpanel-glass-filter',[App\Http\Controllers\ItemListController::class,'filterOverpanelGlass'])->name('items/overpanel-glass-filter');

    Route::post('/architrave-system-filter',[App\Http\Controllers\ItemListController::class,'fileterArchitraveSystem'])->name('items/architrave-system-filter');
    Route::post('/glazing-thikness-filter',[App\Http\Controllers\ItemListController::class,'fileterGlazingThikness'])->name('items/glazing-thikness-filter');
    Route::post('/glazing-beads-filter',[App\Http\Controllers\ItemListController::class,'fileterGlazingBeads'])->name('items/glazing-beads-filter');
    Route::post('/scallopped-lipping-thickness',[App\Http\Controllers\ItemListController::class,'scalloppedLippingThickness'])->name('items/scallopped-lipping-thickness');
    Route::post('/flat-lipping-thickness',[App\Http\Controllers\ItemListController::class,'flatLippingThickness'])->name('items/flat-lipping-thickness');
    Route::post('/lipping-thickness',[App\Http\Controllers\ItemListController::class,'lippingThickness'])->name('items/lipping-thickness');
    Route::post('/rebated-lipping-thickness',[App\Http\Controllers\ItemListController::class,'rebatedLippingThickness'])->name('items/rebated-lipping-thickness');
    Route::post('/glass-type-nfr',[App\Http\Controllers\ItemListController::class,'glassTypeNFR'])->name('items/glass-type-nfr');
    Route::post('/frame-material-filter',[App\Http\Controllers\ItemListController::class,'filterFrameMaterial'])->name('items/frame-material-filter');
    Route::post('/door-thickness-filter',[App\Http\Controllers\ItemListController::class,'filterDoorThickness'])->name('items/door-thickness-filter');
    Route::post('/door-leaf-face-value-filter',[App\Http\Controllers\ItemListController::class,'filterDoorleafFacingValue'])->name('items/door-leaf-face-value-filter');
    Route::post('/ral-color-filter',[App\Http\Controllers\ItemListController::class,'filterRalColor'])->name('items/ral-color-filter');
    Route::post('/face-groove-image',[App\Http\Controllers\ItemListController::class,'faceGrooveImage'])->name('items/face-groove-image');
    Route::post('door-dimension/door-leaf-face-value-filter',[App\Http\Controllers\DoorDimensionController::class,'filterDoorleafFacingValue'])->name('door-dimension/door-leaf-face-value-filter');
    Route::post('/store',[App\Http\Controllers\ItemListController::class,'itemStore'])->name('items/store');
    Route::post('/store1',[App\Http\Controllers\ItemListController::class,'itemStore1'])->name('items/store1');
    Route::post('/ScreenStore',[App\Http\Controllers\ItemListController::class,'ScreenStore'])->name('items/ScreenStore');
    Route::post('/store2',[App\Http\Controllers\ItemListController::class,'itemStore2'])->name('items/store2');
    Route::post('/get-handing-options', [App\Http\Controllers\ItemListController::class,'getHandingOptions'])->name('items/get-handing-options');
    Route::post('/get-glass-options', [App\Http\Controllers\ItemListController::class,'getGlassOptions'])->name('items/get-glass-options');
    Route::post('/screen-glass-glazing', [App\Http\Controllers\ItemListController::class,'screenGlassGlazing'])->name('items/screen-glass-glazing');
    Route::post('/screen-glazing-thickness', [App\Http\Controllers\ItemListController::class,'screenGlazingThickness'])->name('items/screen-glazing-thickness');



    Route::post('/Filterintumescentseals', [App\Http\Controllers\ItemListController::class,'Filterintumescentseals'])->name('Filterintumescentseals');
    Route::post('/glazingFilterScreen', [App\Http\Controllers\ItemListController::class,'glazingFilterScreen'])->name('items/glazingFilterScreen');

    Route::post('/opGlassTypeFilterUrl', [App\Http\Controllers\ItemListController::class,'opGlassTypeFilterUrl'])->name('opGlassTypeFilterUrl');


    Route::get('/add-non-configurable-items', [App\Http\Controllers\ItemFormController::class,'addNonConfigurableItems'])->name('items/add-non-configurable-items');
    Route::post('/save-non-configurable-items', [App\Http\Controllers\ItemFormController::class,'saveNonConfigurableItems'])->name('items/save-non-configurable-items');
    Route::post('/updNonconfigurable', [App\Http\Controllers\ItemFormController::class,'updNonconfigurable'])->name('updNonconfigurable');
});

Route::post('/item/remove', [App\Http\Controllers\ItemFormController::class,'item_remove'])->name('item/remove');
Route::post('/item/remove-screen', [App\Http\Controllers\ItemFormController::class,'screen_remove'])->name('item/remove-screen');

Route::prefix('options')->group(function () {
    Route::get('/add/{id}', [App\Http\Controllers\OptionController::class,'index'])->name('options/add');
    Route::get('/add1/{id}/{optionType}', [App\Http\Controllers\OptionController::class,'index1'])->name('options/add1');
    Route::post('/store', [App\Http\Controllers\OptionController::class,'store'])->name('options/store');
    Route::get('/list/{pageId}', [App\Http\Controllers\OptionController::class,'list'])->name('options/list');
    Route::post('/delete', [App\Http\Controllers\OptionController::class,'delete'])->name('options/delete');
    Route::post('/deleteGlassType', [App\Http\Controllers\OptionController::class,'deleteGlassType'])->name('options/deleteGlassType');
    Route::match(['get','post'],'/edit', [App\Http\Controllers\OptionController::class,'edit'])->name('options/edit');
    Route::post('get-option-value',[App\Http\Controllers\OptionController::class,'get_option_value'])->name('get-option-value');
//old option url
    Route::get('/select/{pageId}/{optionType}', [App\Http\Controllers\OptionController::class,'selectOption'])->name('options/select');
//new option url
    Route::get('/selected/{optionType}', [App\Http\Controllers\OptionController::class,'selectOptionNew'])->name('options/selected');
    Route::get('/selected1/{optionType}/{colorType}', [App\Http\Controllers\OptionController::class,'colorOptionNew'])->name('options/selected1');
    Route::get('/filter/{optionType}/{configurableItem}', [App\Http\Controllers\OptionController::class,'filterGlazingSystem'])->name('options/filter');

    Route::get('/filterFanLightBeading', [App\Http\Controllers\OptionController::class,'filterFanLightBeading'])->name('options/filterFanLightBeading');

    Route::get('/filterSideLightBeading', [App\Http\Controllers\OptionController::class,'filterSideLightBeading'])->name('options/filterSideLightBeading');


    Route::match(['get','post'],'/update-option', [App\Http\Controllers\OptionController::class,'updateSelectOption'])->name('options/update-option');
    Route::post('/update-check-option', [App\Http\Controllers\OptionController::class,'checkSelectOption'])->name('options/update-check-option');
    Route::post('/update-selected-option-cost', [App\Http\Controllers\OptionController::class,'updateSelectOptionCost'])->name('options/update-selected-option-cost');
    // tkt-484 for custome door update cost
    Route::post('/update-selected-option-cost-custome', [App\Http\Controllers\OptionController::class,'updateSelectOptionCostCustome'])->name('options//update-selected-option-cost-custome');
    Route::match(['get','post'],'/update-option-custome', [App\Http\Controllers\OptionController::class,'updateSelectOptionCustome'])->name('options/update-option-custome');
    //end
    Route::post('/update-glassType', [App\Http\Controllers\OptionController::class,'updateGlassType'])->name('option/update-glassType');
    Route::post('/option/import-OverpanelGlassGlazing', [App\Http\Controllers\OptionController::class,'updateOverpanelGlassGlazing'])->name('option/import-OverpanelGlassGlazing');
    Route::post('/option/import-intumescentSealArrangement', [App\Http\Controllers\OptionController::class,'updateintumescentSealArrangement'])->name('option/import-intumescentSealArrangement');
    Route::post('/option/import-sidescreen', [App\Http\Controllers\OptionController::class,'updatesidescreen'])->name('option/import-sidescreen');

    Route::post('/option/import-glasstype', [App\Http\Controllers\OptionController::class,'importglasstype'])->name('option/import-glasstype');
    Route::post('/option/import-glazing', [App\Http\Controllers\OptionController::class,'importglazingsystem'])->name('option/import-glazing');
    Route::post('/option/import-glassglazing', [App\Http\Controllers\OptionController::class,'importglassglazing'])->name('option/import-glassglazing');

    Route::match(['get','post'],'/get', [App\Http\Controllers\OptionController::class,'get'])->name('options/get');

    Route::post('filterConfiguretype',[App\Http\Controllers\OptionController::class,'filterConfiguretype'])->name('filterConfiguretype');
    Route::get('filter-swing-type',[App\Http\Controllers\OptionController::class,'filter_swing_type'])->name('filter-swing-type');
    Route::get('filter-door-dimensions',[App\Http\Controllers\OptionController::class,'filter_door_dimensions'])->name('filter-door-dimensions');
    Route::get('filter-door-dimensions-leaf',[App\Http\Controllers\OptionController::class,'filter_door_dimensions_leaf'])->name('filter-door-dimensions-leaf');
    Route::post('filter-latch-type',[App\Http\Controllers\OptionController::class,'filter_latch_type'])->name('filter-latch-type');

    Route::get('/get-glazing-beads', [App\Http\Controllers\OptionController::class, 'getGlazingBeads']);

    Route::post('/glassconfigvalue',[App\Http\Controllers\OptionController::class,'glassconfigvalue'])->name('items/glassconfigvalue');
    Route::post('filter-glass-type-overpanel',[App\Http\Controllers\OptionController::class,'filter_glass_type_overpanel'])->name('filter-glass-type-overpanel');
    Route::post('filter-leaf-type',[App\Http\Controllers\OptionController::class,'filter_leaf_type'])->name('filter-leaf-type');
});

Route::prefix('quotation')->group(function () {
    Route::get('/add', [App\Http\Controllers\DoorScheduleController::class,'add'])->name('quotation/add');
    Route::post('/store', [App\Http\Controllers\DoorScheduleController::class,'store'])->name('quotation/store');
    Route::get('/list', [App\Http\Controllers\DoorScheduleController::class,'quotationList'])->name('quotation/list');
    Route::get('/list/{id}', [App\Http\Controllers\DoorScheduleController::class,'quotationList'])->name('quotation/list/');

    Route::get('/request/{id}', [App\Http\Controllers\DoorScheduleController::class,'quotation_request'])->name('quotation/request');
    Route::get('/request/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'quotation_request'])->name('quotation/request');

    Route::get('/details', [App\Http\Controllers\DoorScheduleController::class,'quotation_details'])->name('quotation/details');
    Route::get('/doors/{id}', [App\Http\Controllers\DoorScheduleController::class,'doors'])->name('quotation/doors');
    Route::post('/addcustomer', [App\Http\Controllers\DoorScheduleController::class,'addcustomer'])->name('quotation/addcustomer');
    Route::get('/getcustomer', [App\Http\Controllers\DoorScheduleController::class,'getcustomer'])->name('quotation/getcustomer');
    Route::post('/add-shipping-address', [App\Http\Controllers\DoorScheduleController::class,'add_shipping_address'])->name('quotation/add-shipping-address');
    Route::get('/add-new-doors/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'getupdateddoors'])->name('quotation/add-new-doors');
    Route::get('/add-new-screens/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'getupdatedScreens'])->name('quotation/add-new-screens');
    Route::post('/store-new-door', [App\Http\Controllers\DoorScheduleController::class,'newdoorsstore'])->name('quotation/store-new-door');
    Route::post('/store-new-screen', [App\Http\Controllers\DoorScheduleController::class,'newScreenStore'])->name('quotation/store-new-screen');
    Route::get('/add-door/{id}', [App\Http\Controllers\DoorScheduleController::class,'adddoor'])->name('quotation/add-door');
    Route::get('/excel-upload/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'excelupload'])->name('quotation/excel-upload');
    Route::post('/store-door', [App\Http\Controllers\DoorScheduleController::class,'storedoor'])->name('quotation/store-door');
    Route::post('/store-excel', [App\Http\Controllers\DoorScheduleController::class,'storexcel'])->name('quotation/store-excel');
    Route::post('/edit-image', [App\Http\Controllers\DoorScheduleController::class,'editImage'])->name('quotation/edit-image');
    Route::post('/edit-image1', [App\Http\Controllers\DoorScheduleController::class,'editImage1'])->name('quotation/edit-image1');
    Route::post('/favoriteItem', [App\Http\Controllers\DoorScheduleController::class,'favoriteItem'])->name('quotation/favoriteItem');
    Route::post('/adjustPriceUrl', [App\Http\Controllers\DoorScheduleController::class,'adjustPriceUrl'])->name('quotation/adjustPriceUrl');
    Route::post('/favoriteItemAdd', [App\Http\Controllers\DoorScheduleController::class,'favoriteItemAdd'])->name('quotation/favoriteItemAdd');
    Route::post('/favoriteDeleteItem', [App\Http\Controllers\DoorScheduleController::class,'favoriteDeleteItem'])->name('quotation/favoriteDeleteItem');
    Route::post('/updateManualAcceptQuote', [App\Http\Controllers\DoorScheduleController::class,'updateManualAcceptQuote'])->name('quotation/updateManualAcceptQuote');
    Route::post('/adjustPriceDiscountUrl', [App\Http\Controllers\DoorScheduleController::class,'adjustPriceDiscount'])->name('quotation/adjustPriceDiscountUrl');
    Route::post('/ImportfileUpload', [App\Http\Controllers\DoorScheduleController::class,'ImportfileUpload'])->name('ImportfileUpload');

    Route::post('/parseImport', [App\Http\Controllers\DoorScheduleController::class,'parseImport'])->name('parseImport');
    Route::get('/import_fields', [App\Http\Controllers\DoorScheduleController::class,'import_fields'])->name('import_fields');
    Route::post('/import_process', [App\Http\Controllers\DoorScheduleController::class,'import_process'])->name('import_process');



    Route::get('/generate', [App\Http\Controllers\DoorScheduleController::class,'generateQuotation'])->name('quotation/generate');
    Route::get('/generate/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'generateQuotation'])->name('quotation/generate/');
    Route::post('/generate/BomCalculation', [App\Http\Controllers\DoorScheduleController::class,'BomCalculationPrint'])->name('quotation/BomCalculation');
    Route::get('/convert-to-quotation/{id}/{aid}', [App\Http\Controllers\DoorScheduleController::class,'convertToQuotation'])->name('convert-to-quotation');
    Route::post('/get-contact-details', [App\Http\Controllers\DoorScheduleController::class,'get_contact_details'])->name('get-contact-details');

    Route::post('/create-new-version', [App\Http\Controllers\DoorScheduleController::class,'createNewVersion'])->name('quotation/create-new-version');
    Route::post('/remove-item-from-version', [App\Http\Controllers\DoorScheduleController::class,'removeItemFromVersion'])->name('quotation/remove-item-from-version');

    Route::post('/versionstore', [App\Http\Controllers\DoorScheduleController::class,'versionStore'])->name('quotation/versionstore');
    Route::post('/get-version', [App\Http\Controllers\DoorScheduleController::class,'getVersionQuotation'])->name('quotation/get-version');
    Route::match(['get','post'],'/records', [App\Http\Controllers\DoorScheduleController::class,'records'])->name('quotation/records');
    Route::get('/printinvoice/{v}/{qid}', [App\Http\Controllers\PrintInvoiceController::class,'printinvoice'])->name('printinvoice');
    Route::get('/printinvoiceinexcel/{v}/{qid}', [App\Http\Controllers\PrintInvoiceController::class,'printinvoiceinexcel'])->name('printinvoiceinexcel');

    Route::post('/testprintinvoice', [App\Http\Controllers\PrintInvoiceController::class,'testprintinvoice'])->name('testprintinvoice');

    Route::post('/selectcustomer', [App\Http\Controllers\DoorScheduleController::class,'selectcustomer'])->name('quotation/selectcustomer');
    Route::get('/singleconfigurationitem/{id}', [App\Http\Controllers\DoorScheduleController::class,'singleconfigurationitem'])->name('quotation/singleconfigurationitem');
    Route::get('/singleconfigurationitem/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'singleconfigurationitem'])->name('quotation/singleconfigurationitem');


    Route::get('/add-configuration-cad-item/{id}', [App\Http\Controllers\DoorScheduleController::class,'addConfigurationCadItem'])->name('quotation/add-configuration-cad-item');
    Route::get('/add-configuration-cad-item/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'addConfigurationCadItem'])->name('quotation/add-configuration-cad-item');
    Route::get('/edit-configuration-cad-item/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'editConfigurationCadItem'])->name('quotation/edit-configuration-cad-item');

    Route::get('/add-side-screen-item/{id}', [App\Http\Controllers\DoorScheduleController::class,'addSideScreenItem'])->name('quotation/add-side-screen-item');
    Route::get('/add-side-screen-item/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'addSideScreenItem'])->name('quotation/add-side-screen-item');
    Route::get('/edit-side-screen-item/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'editSideScreenItem'])->name('quotation/edit-side-screen-item');

    // get firerating options
    Route::get('/get-fire-rating-options', [App\Http\Controllers\DoorScheduleController::class, 'getFireRatingOptions']);


    //end

    // Halspan Door
    Route::get('/add-halspan-item/{id}', [App\Http\Controllers\halspan\HalspanController::class,'addHalspanItem'])->name('addHalspanItem');
    Route::get('/add-halspan-item/{id}/{vid}', [App\Http\Controllers\halspan\HalspanController::class,'addHalspanItem'])->name('addHalspanItem');
    Route::get('/edit-halspan-configuration-cad-item/{id}/{vid}', [App\Http\Controllers\halspan\HalspanController::class,'editHalspanConfigurationCadItem'])->name('quotation/edit-halspan-configuration-cad-item');


    //Norma
    Route::get('/add-norma-door-core-item/{id}', [App\Http\Controllers\normadoorcore\NormaDoorCoreController::class,'add_norma_door_core'])->name('addNormaDoorCoreItem');
    Route::get('/add-norma-door-core-item/{id}/{vid}',[App\Http\Controllers\normadoorcore\NormaDoorCoreController::class,'add_norma_door_core'])->name('addNormaDoorCoreItem');

    //Vicaima Door
    Route::get('/add-vicaima-door-core-item/{id}', [App\Http\Controllers\VicaimaController::class,'add_vicaima_door_core'])->name('addVicaimaDoorCoreItem');
    Route::get('/add-vicaima-door-core-item/{id}/{vid}',[App\Http\Controllers\VicaimaController::class,'add_vicaima_door_core'])->name('addVicaimaDoorCoreItem');
    Route::get('/edit-vicaima-door-core-item/{id}/{vid}',[App\Http\Controllers\VicaimaController::class,'edit_vicaima_door_core'])->name('editVicaimaDoorCoreItem');

    //Seadec door
    Route::get('/add-seadec-cad-item/{id}', [App\Http\Controllers\SeadecController::class,'addseadecCadItem'])->name('quotation/add-seadec-cad-item');
    Route::get('/add-seadec-cad-item/{id}/{vid}', [App\Http\Controllers\SeadecController::class,'addseadecCadItem'])->name('quotation/add-seadec-cad-item');
    Route::get('/edit-seadec-cad-item/{id}/{vid}', [App\Http\Controllers\SeadecController::class,'editseadecCadItem'])->name('quotation/edit-seadec-cad-item');

      //deanta door
    Route::get('/add-deanta-cad-item/{id}', [App\Http\Controllers\DeantaController::class,'adddeantaCadItem'])->name('quotation/add-deanta-cad-item');
    Route::get('/add-deanta-cad-item/{id}/{vid}', [App\Http\Controllers\DeantaController::class,'adddeantaCadItem'])->name('quotation/add-deanta-cad-item');
    Route::get('/edit-deanta-cad-item/{id}/{vid}', [App\Http\Controllers\DeantaController::class,'editdeantaCadItem'])->name('quotation/edit-deanta-cad-item');
    Route::post('/import-denta', [App\Http\Controllers\DeantaController::class,'door_dimension_import'])->name('quotation/import-denta');

    // Flamebreak door
     Route::get('/add-flamebreak-item/{id}', [App\Http\Controllers\flamebreak\FlamebreakController::class,'addFlamebreakItem'])->name('addFlamebreakItem');
     Route::get('/add-flamebreak-item/{id}/{vid}', [App\Http\Controllers\flamebreak\FlamebreakController::class,'addFlamebreakItem'])->name('addFlamebreakItem');
    Route::get('/edit-flamebreak-cad-item/{id}/{vid}', [App\Http\Controllers\flamebreak\FlamebreakController::class,'editFlamebreakConfigurationCadItem'])->name('quotation/edit-flamebreak-cad-item');
    //end

    // Stredor door
     Route::get('/add-stredor-item/{id}', [App\Http\Controllers\stredor\StredorController::class,'addStredorItem'])->name('addStredorItem');
     Route::get('/add-stredor-item/{id}/{vid}', [App\Http\Controllers\stredor\StredorController::class,'addStredorItem'])->name('addStredorItem');
    Route::get('/edit-stredor-cad-item/{id}/{vid}', [App\Http\Controllers\stredor\StredorController::class,'editStredorConfigurationCadItem'])->name('quotation/edit-stredor-cad-item');
    //end


    Route::post('/door-leaf-facing-value',[App\Http\Controllers\VicaimaController::class,'doorLeafFacingValue'])->name('quotation/doorLeafFacingValue');
    Route::post('/lipping-type-value',[App\Http\Controllers\VicaimaController::class,'lippingtype'])->name('quotation/lippingtype');
    Route::post('/Intumescent-seal-arrangement-value',[App\Http\Controllers\VicaimaController::class,'IntumescentSealArrangementValue'])->name('quotation/IntumescentSealArrangementValue');
    Route::post('/Intumescent-seal-arrangement-option',[App\Http\Controllers\VicaimaController::class,'IntumescentSealArrangementOption'])->name('quotation/IntumescentSealArrangementOption');
    Route::post('/get-door-leaf-facing',[App\Http\Controllers\VicaimaController::class,'getDoorFacing'])->name('quotation/getDoorFacing');
    Route::get('/ral-color-inser',[App\Http\Controllers\VicaimaController::class,'ralcolorinsert'])->name('quotation/ral-color');



    // Export in excell
    Route::get('/excel-export', [App\Http\Controllers\DoorScheduleController::class,'export'])->name('export');
    Route::get('/excel-export2', [App\Http\Controllers\DoorScheduleController::class,'export2'])->name('export2');

    // Search customer
    Route::post('/searchCustomer' , [App\Http\Controllers\DoorScheduleController::class,'searchCustomer'])->name('searchCustomer');

    // generateBOM
    Route::get('/generateBOM2/{v}/{qid}/{tag}' , [App\Http\Controllers\BOMController::class,'generateBOM2'])->name('generateBOM2');
    Route::post('/generateBOM' , [App\Http\Controllers\BOMController::class,'generateBOM'])->name('generateBOM');
    Route::get('/generateBOM/{id}/{vid}/{version}' , [App\Http\Controllers\BOMController::class,'BomCalculation'])->name('generateBOMPDF');
    Route::get('/ScreengenerateBOM/{id}/{vid}/{version}' , [App\Http\Controllers\BOMController::class,'ScreenBomCalculation'])->name('ScreengenerateBOMPDF');
    Route::get('/DoorOrderSheet/{id}/{vid}/{version}' , [App\Http\Controllers\BOMController::class,'DoorOrderSheet'])->name('DoorOrderSheetPDF');
    Route::get('/FrameTransoms/{id}/{vid}/{version}' , [App\Http\Controllers\BOMController::class,'FrameTransoms'])->name('FrameTransomsPDF');
    Route::get('/GlassOrderSheet/{id}/{vid}/{version}' , [App\Http\Controllers\BOMController::class,'GlassOrderSheet'])->name('GlassOrderSheet');
    Route::get('/GlazingBeadsDoors/{id}/{vid}/{version}' , [App\Http\Controllers\BOMController::class,'GlazingBeadsDoors'])->name('GlazingBeadsDoors');

    Route::post('/generateBOMPrint' , [App\Http\Controllers\BOMController::class,'BomCalculationPrint'])->name('generateBOMPrint');
    Route::post('/ScreengenerateBOMPrint' , [App\Http\Controllers\BOMController::class,'ScreenBomCalculationPrint'])->name('ScreengenerateBOMPrint');
    Route::post('/DoorOrderSheetUrl' , [App\Http\Controllers\BOMController::class,'DoorOrderSheetUrl'])->name('DoorOrderSheetUrl');
    Route::get('/QualityControlPrint/{v}/{qid}' , [App\Http\Controllers\BOMController::class,'QualityControlPrint'])->name('QualityControlPrint');

    Route::post('/FrameTransomsUrl' , [App\Http\Controllers\BOMController::class,'FrameTransomsUrl'])->name('FrameTransomsUrl');

    // Quote Summary
    Route::post('/QuoteSummary' , [App\Http\Controllers\DoorScheduleController::class,'QuoteSummary'])->name('QuoteSummary');
    Route::post('/QuoteSummaryOnChangeCustomer' , [App\Http\Controllers\DoorScheduleController::class,'QuoteSummaryOnChangeCustomer'])->name('QuoteSummaryOnChangeCustomer');

    // Copy old quotation
    Route::post('/copy-existing-quotation', [App\Http\Controllers\DoorScheduleController::class,'copyExistingQuotation'])->name('quotation/copy-existing-quotation');
    // Copy old doorset
    Route::post('/copy-existing-doorset', [App\Http\Controllers\DoorScheduleController::class,'copyExistingDoorSet'])->name('quotation/copy-existing-doorset');
    Route::post('/copy-existing-doorset-screen', [App\Http\Controllers\DoorScheduleController::class,'copyExistingSideScreen'])->name('quotation/copy-existing-doorset-screen');

    // Delete Quotation
    Route::post('/deletequotation',[App\Http\Controllers\DoorScheduleController::class,'deletequotation'])->name('deletequotation');

    Route::get('/excelexport/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'export'])->name('export');

    Route::get('/excelexportNew/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'exportNew'])->name('exportNew');
    Route::get('/ExportBomCalculation/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'ExportBomCalculation'])->name('ExportBomCalculation');
    Route::get('/ExportDoorTypeBom/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'ExportDoorTypeBom'])->name('ExportDoorTypeBom');
    Route::get('/ExportSideScreen/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'ExportSideScreen'])->name('ExportSideScreen');
    Route::get('/excelexportVicaimaUrl/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'excelexportVicaima'])->name('excelexportVicaima');
    Route::get('/ExportIronmongery/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'ExportIronmongery'])->name('ExportIronmongery');

    // export all quotions
    Route::get('/exportAllQuotationsurl', [App\Http\Controllers\DoorScheduleController::class,'exportAllQuotations'])->name('exportAllQuotationsurl');
    //end
    // Edit header delete 'Site Delivery Address'
    Route::post('/delquotationDeliveryAddress' , [App\Http\Controllers\DoorScheduleController::class,'delquotationDeliveryAddress'])->name('delquotationDeliveryAddress');
    Route::get('/userparent', [App\Http\Controllers\DoorScheduleController::class,'userparent'])->name('quotation/userparent');

    // Send To Client
    Route::post('/sendToClientUrl',[App\Http\Controllers\SendToClientController::class,'sendToClientUrl'])->name('sendToClientUrl');

    // showAccousticdata in Modal
    Route::post('/showAccoustic', [App\Http\Controllers\DoorScheduleController::class,'showAccoustic'])->name('showAccoustic');
    Route::post('/doorStandardPrice', [App\Http\Controllers\DoorScheduleController::class,'doorStandardPrice'])->name('doorStandardPrice');
    Route::post('/IronmongeryIDPrice', [App\Http\Controllers\DoorScheduleController::class,'IronmongeryIDPrice'])->name('IronmongeryIDPrice');
    Route::post('/generalLabourCost', [App\Http\Controllers\DoorScheduleController::class,'generalLabourCost'])->name('generalLabourCost');
    Route::post('/FrameCost', [App\Http\Controllers\DoorScheduleController::class,'FrameCost'])->name('FrameCost');

    // Select project fetch currency
    Route::post('/projectfetchCurrency', [App\Http\Controllers\DoorScheduleController::class,'projectfetchCurrency'])->name('projectfetchCurrency');

    Route::get('/door-list-show/{id}/{vid}', [App\Http\Controllers\DoorScheduleController::class,'doorListShow'])->name('quotation/door-list-show');
    Route::post('/door-list-delete', [App\Http\Controllers\DoorScheduleController::class,'doorListDelete'])->name('quotation/door-list-delete');
});

///architect
Route::prefix('Architect')->group(function () {
    Route::get('/add',[App\Http\Controllers\ArchitectController::class,'add'])->name('Architect/add');
    Route::get('/list', [App\Http\Controllers\ArchitectController::class,'list'])->name('Architect/list');
    Route::get('/details/{id}', [App\Http\Controllers\ArchitectController::class,'details'])->name('Architect/details');
    Route::get('/profile', [App\Http\Controllers\ArchitectController::class,'profile'])->name('Architect/profile');
    Route::get('/edit/{id}', [App\Http\Controllers\ArchitectController::class,'edit'])->name('Architect/edit');
    Route::post('/store', [App\Http\Controllers\ArchitectController::class,'store'])->name('Architect/store');
    Route::post('/deleteArchitect', [App\Http\Controllers\ArchitectController::class,'deleteArchitect'])->name('deleteArchitect');

    });

    Route::get('generate/quotation/{id}', [App\Http\Controllers\ArchitectPdfGenerateController::class,'generate'])->name('generate/quotation');

Route::prefix('file')->group(function () {
    Route::get('/ceate-quatation-file', [App\Http\Controllers\ArchitectureController::class,'index'])->name('file/ceate-quatation-file');
    Route::post('/store-filename', [App\Http\Controllers\ArchitectureController::class,'storeFileName'])->name('file/store-filename');
    Route::get('/choose-add-data-option/{id}', [App\Http\Controllers\ArchitectureController::class,'chooseAddDataOption'])->name('file/choose-add-data-option');
    Route::get('/quation-file-list', [App\Http\Controllers\ArchitectureController::class,'QuationFileList'])->name('file/quation-file-list');
    Route::get('/file-import/{id}', [App\Http\Controllers\ArchitectureController::class,'FileImport'])->name('file/file-import');
    Route::get('/add-quation-details/{id}', [App\Http\Controllers\ArchitectureController::class,'AddQuatationDetails'])->name('file/add-quation-details');
    Route::get('/delete/{id}', [App\Http\Controllers\ArchitectureController::class,'delete'])->name('file/delete');
});

Route::prefix('form')->group(function () {
    Route::get('/item', [App\Http\Controllers\ItemFormController::class,'index'])->name('form/item');
    Route::post('/store-filename', [App\Http\Controllers\ItemFormController::class,'storeFileName'])->name('form/store-filename');
    Route::get('/configuration-file-list', [App\Http\Controllers\ItemFormController::class,'configurationFormList'])->name('form/configuration-file-list');
    Route::get('/view/{id}', [App\Http\Controllers\ItemFormController::class,'viewPage'])->name('form/view');

});

Route::prefix('project')->group(function () {
    Route::get('/create', [App\Http\Controllers\ProjectController::class,'index'])->name('project/create');
    Route::get('/update/{id}', [App\Http\Controllers\ProjectController::class,'index'])->name('project/update/');
    Route::post('/store', [App\Http\Controllers\ProjectController::class,'store'])->name('project/store');
    Route::get('/quotation/{id}', [App\Http\Controllers\ProjectController::class,'quotation'])->name('project/quotation/');
    Route::post('/upload-file', [App\Http\Controllers\ProjectController::class,'fileUpload'])->name('project/upload-file');

    Route::get('/projectNewQuotation/{projectId}/{customerId}',[App\Http\Controllers\ProjectController::class,'projectNewQuotation'])->name('projectNewQuotation');
    Route::get('/projectNewQuotation/{projectId}',[App\Http\Controllers\ProjectController::class,'projectNewQuotation'])->name('projectNewQuotation');


    Route::get('/list', [App\Http\Controllers\ProjectController2::class,'list'])->name('project/list');
    Route::get('/surveyReport/{id}', [App\Http\Controllers\DoorScheduleController::class,'surveyReport'])->name('project/surveyReport');



    Route::post('/get-project-list', [App\Http\Controllers\ProjectController2::class,'getProjectList'])->name('project/get-project-list');
    Route::post('/floorStore', [App\Http\Controllers\ProjectController2::class,'floorStore'])->name('project/floorStore');
    Route::post('/get-assigned-projects', [App\Http\Controllers\ProjectController2::class,'getAssignedProjects'])->name('project/get-assigned-projects');
    Route::get('/quotation-list/{id}', [App\Http\Controllers\ProjectController2::class,'getProjectDetails'])->name('project/quotation-list/');
    Route::match(['get','post'],'/quotation-list-ajax', [App\Http\Controllers\ProjectController2::class,'quotationListAjax2'])->name('project/quotation-list-ajax');
    Route::post('/addcomment',[App\Http\Controllers\ProjectController2::class,'addComment'])->name('addcomment');
    Route::post('/defaults',[App\Http\Controllers\ProjectController2::class, 'defaultsStore'])->name('project/defaultsStore');

    // all data export for project
    Route::get('/get-project-list-allexport', [App\Http\Controllers\ProjectController2::class,'getProjectListExportAll'])->name('project/get-project-list-allexport');
    //end
    Route::get('/ironmongery-add',[App\Http\Controllers\ProjectController2::class,'ironmongeryadd'])->name('ironmongeryadd');
    Route::get('/ironmongery-list',[App\Http\Controllers\ProjectController2::class,'ironmongeryList'])->name('ironmongery-list');
    Route::get('/add-ironmongery/{pid}',[App\Http\Controllers\ProjectController2::class,'addironmongery'])->name('addironmongery');
    Route::post('/subaddironmongery',[App\Http\Controllers\ProjectController2::class,'subaddironmongery'])->name('subaddironmongery');
    Route::post('/storeaddironmongery',[App\Http\Controllers\ProjectController2::class,'storeaddironmongery'])->name('storeaddironmongery');
    Route::post('/updAddIronmongery',[App\Http\Controllers\ProjectController2::class,'updAddIronmongery'])->name('updAddIronmongery');
    Route::post('/delAddIronmongery',[App\Http\Controllers\ProjectController2::class,'delAddIronmongery'])->name('delAddIronmongery');
    Route::post('/addquotation',[App\Http\Controllers\ProjectController::class,'addquotation'])->name('addquotation');
    Route::post('/addquotation2',[App\Http\Controllers\ProjectController::class,'addquotation2'])->name('addquotation2');

    Route::post('/deactivateproject',[App\Http\Controllers\ProjectController2::class,'deactivateproject'])->name('deactivateproject');
    Route::post('/activateproject',[App\Http\Controllers\ProjectController2::class,'activateproject'])->name('activateproject');
    Route::post('/deleteproject',[App\Http\Controllers\ProjectController2::class,'deleteproject'])->name('deleteproject');
    Route::post('/deleteProjectFile',[App\Http\Controllers\ProjectController::class,'deleteProjectFile'])->name('deleteProjectFile');

    Route::post('/invite', [App\Http\Controllers\ProjectController2::class,'invite'])->name('project/invite');
    Route::get('/invitation/list', [App\Http\Controllers\ProjectController2::class,'invitation_list'])->name('project/invitation/list');
    Route::post('/invitation/records', [App\Http\Controllers\ProjectController2::class,'invitation_records'])->name('project/invitation/records');
    Route::post('/invitation/status',[App\Http\Controllers\ProjectController2::class,'invitation_status'])->name('project/invitation/status');
    Route::post('/assign',[App\Http\Controllers\ProjectController2::class,'project_assign'])->name('project/assign');
    //sending mail for the invite project by mail
    Route::post('quotation/invite/send-mail', [App\Http\Controllers\ProjectController2::class,'send_mail'])->name('quotation/invite/send-mail');
    Route::get('invitation', [App\Http\Controllers\ProjectController2::class,'project_invitation'])->name('project/invitation');
    Route::post('/change-assign-project',[App\Http\Controllers\ProjectController2::class,'change_assign_project'])->name('change-assign-project');
    Route::get('/assign-projects',[App\Http\Controllers\ProjectController2::class,'assign_projects'])->name('assign-projects');
    Route::get('/accept-project-by-company/{id}',[App\Http\Controllers\ProjectController2::class,'accept_project_by_company'])->name('accept-project-by-company');
    Route::post('/instructionSave',[App\Http\Controllers\ProjectController2::class,'instructionSave'])->name('project/instructionSave');
    Route::get('/testMail',[App\Http\Controllers\ProjectController2::class,'testMail'])->name('project/testMail');



});

Route::prefix('ironmongery-info')->group(function (){

    Route::get('/create', [App\Http\Controllers\IronmongeryInfo::class,'index'])->name('ironmongery-info/create');
    Route::post('/store', [App\Http\Controllers\IronmongeryInfo::class,'store'])->name('ironmongery-info/store');
    Route::get('/update/{id}', [App\Http\Controllers\IronmongeryInfo::class,'index'])->name('ironmongery-info/update/');
    Route::get('/records/{id}', [App\Http\Controllers\IronmongeryInfo::class,'records'])->name('ironmongery-info/records');
    Route::get('/list/{importId}', [App\Http\Controllers\IronmongeryInfo::class,'list'])->name('ironmongery-info/list');
    Route::get('/list', [App\Http\Controllers\IronmongeryInfo::class,'list']);
    Route::post('/select', [App\Http\Controllers\IronmongeryInfo::class,'select'])->name('ironmongery-info/select');
    Route::post('/filter-iron-mongery-category', [App\Http\Controllers\IronmongeryInfo::class,'filterIronMongeryFilter'])->name('ironmongery-info/filter-iron-mongery-category');
    Route::post('/delete/{id}', [App\Http\Controllers\IronmongeryInfo::class,'delete'])->name('ironmongery-info/delete');
    Route::get('/IronmongeryExport', [App\Http\Controllers\IronmongeryInfo::class,'IronmongeryExport'])->name('IronmongeryExport');
    Route::post('/IronmongeryImport', [App\Http\Controllers\IronmongeryInfo::class,'IronmongeryImport'])->name('IronmongeryImport');
    Route::get('/IronmongeryTableInsert', [App\Http\Controllers\IronmongeryInfo::class,'IronmongeryTableInsert'])->name('IronmongeryTableInsert');
});


Route::prefix('non-configural-items')->group(function (){
    Route::get('/create', [App\Http\Controllers\NonConfiguralItems::class,'create'])->name('non-configural-items/create');
    Route::post('/store', [App\Http\Controllers\NonConfiguralItems::class,'store'])->name('non-configural-items/store');
    Route::get('/list', [App\Http\Controllers\NonConfiguralItems::class,'index'])->name('non-configural-items/list');
    Route::get('/edit/{id}', [App\Http\Controllers\NonConfiguralItems::class,'edit'])->name('non-configural-items/edit/');
    Route::get('/delete/{id}', [App\Http\Controllers\NonConfiguralItems::class,'delete'])->name('non-configural-items/delete/');
    Route::post('/nonConfigstore', [App\Http\Controllers\NonConfiguralItems::class, 'nonConfigstore'])->name('non-configural-items/nonConfigstore');
    Route::post('/nonConfigUpdate', [App\Http\Controllers\NonConfiguralItems::class, 'nonConfigUpdate'])->name('non-configural-items/nonConfigUpdate');
    Route::post('/nonConfigDelete', [App\Http\Controllers\NonConfiguralItems::class, 'nonConfigDelete'])->name('non-configural-items/nonConfigDelete');
});

//setting section
Route::prefix('setting')->group(function (){
    Route::get('/generalSetting', [App\Http\Controllers\setting\GeneralSettingController::class,'generalSetting'])->name('generalSetting');
    Route::post('/subgeneralSetting',[App\Http\Controllers\setting\GeneralSettingController::class,'subgeneralSetting'])->name('subgeneralSetting');
    Route::get('/DoorFrameConstruction',[App\Http\Controllers\setting\GeneralSettingController::class,'DoorFrameConstruction'])->name('DoorFrameConstruction');
    Route::post('/storeDoorFrameConstruction',[App\Http\Controllers\setting\GeneralSettingController::class,'storeDoorFrameConstruction'])->name('storeDoorFrameConstruction');

    Route::get('/mail-Format', [App\Http\Controllers\setting\PdfsettingController::class,'settingpdf1'])->name('settingpdf');
    Route::post('/submitpdf1', [App\Http\Controllers\setting\PdfsettingController::class,'submitpdf1'])->name('submitpdf1');
    Route::post('/submitpdf2', [App\Http\Controllers\setting\PdfsettingController::class,'submitpdf2'])->name('submitpdf2');
    Route::post('/submitpdf3', [App\Http\Controllers\setting\PdfsettingController::class,'submitpdf3'])->name('submitpdf3');
    Route::post('/submitpdf4', [App\Http\Controllers\setting\PdfsettingController::class,'submitpdf4'])->name('submitpdf4');
    Route::post('ckeditor/upload', [App\Http\Controllers\setting\PdfsettingController::class,'upload'])->name('ckeditor.upload');

    // Tooltip
    Route::get('/tooltip', [App\Http\Controllers\setting\tooltipController::class,'tooltip'])->name('tooltip');
    Route::post('/submittooltip', [App\Http\Controllers\setting\tooltipController::class,'submittooltip'])->name('submittooltip');

    // Quotation Prefix
    Route::get('/quotation-prefix',[App\Http\Controllers\setting\QuotationPrefixController::class,'QuotationPrefix'])->name('QuotationPrefix');
    Route::get('/order-prefix',[App\Http\Controllers\setting\QuotationPrefixController::class,'OrderPrefix'])->name('OrderPrefix');
    Route::post('/setprefix',[App\Http\Controllers\setting\QuotationPrefixController::class,'setprefix'])->name('setprefix');

    //order prefix

    Route::post('/set-order-prefix',[App\Http\Controllers\setting\QuotationPrefixController::class,'set_order_prefix'])->name('set_order_prefix');

    // Intumescent Seals
    Route::get('/intumescentseals/{pageId}',[App\Http\Controllers\setting\IntumescentController::class,'intumescentseals'])->name('intumescentseals');
    Route::post('/submitintumescentseals',[App\Http\Controllers\setting\IntumescentController::class,'submitintumescentseals'])->name('submitintumescentseals');
    Route::post('/deleteintumescentseals',[App\Http\Controllers\setting\IntumescentController::class,'deleteintumescentseals'])->name('deleteintumescentseals');
    Route::post('/updintumescentseals',[App\Http\Controllers\setting\IntumescentController::class,'updintumescentseals'])->name('updintumescentseals');


    // Build Of Material
    Route::get('/buildofmaterial/generalsetting',[App\Http\Controllers\setting\BuildOfMaterialController::class,'settingbuildofmaterial'])->name('settingbuildofmaterial');
    Route::post('/subsettingbuildofmaterial',[App\Http\Controllers\setting\BuildOfMaterialController::class,'subsettingbuildofmaterial'])->name('subsettingbuildofmaterial');
    Route::get('/buildofmaterial/costsetting',[App\Http\Controllers\setting\BuildOfMaterialController::class,'costsetting'])->name('costsetting');
    Route::get('/buildofmaterial/general_labour_cost_setting',[App\Http\Controllers\setting\BuildOfMaterialController::class,'general_labour_cost_setting'])->name('general_labour_cost_setting');
    Route::post('/buildofmaterial/general_labour_cost_sub_setting',[App\Http\Controllers\setting\BuildOfMaterialController::class,'general_labour_cost_sub_setting'])->name('general_labour_cost_sub_setting');
    Route::post('/subcostsetting',[App\Http\Controllers\setting\BuildOfMaterialController::class,'subcostsetting'])->name('subcostsetting');
    Route::post('/deletecostsetting',[App\Http\Controllers\setting\BuildOfMaterialController::class,'deletecostsetting'])->name('deletecostsetting');
    Route::post('/updcostsetting',[App\Http\Controllers\setting\BuildOfMaterialController::class,'updcostsetting'])->name('updcostsetting');

    // lippingSpecies
    Route::get('/lipping-species',[App\Http\Controllers\setting\lippingSpeciesController::class,'lippingSpecies'])->name('lippingSpecies');
    Route::post('/sublippingSpecies',[App\Http\Controllers\setting\lippingSpeciesController::class,'sublippingSpecies'])->name('sublippingSpecies');
    Route::post('/deletelippingSpecies',[App\Http\Controllers\setting\lippingSpeciesController::class,'deletelippingSpecies'])->name('deletelippingSpecies');
    Route::post('/updlippingSpecies',[App\Http\Controllers\setting\lippingSpeciesController::class,'updlippingSpecies'])->name('updlippingSpecies');

    Route::get('/edit-configurable-door-formula',[App\Http\Controllers\setting\ConfigurableDoorFormulaController::class,'editConfigurableDoorFormula'])->name('edit-configurable-door-formula');
    Route::post('/save-configurable-door-formula',[App\Http\Controllers\setting\ConfigurableDoorFormulaController::class,'saveConfigurableDoorFormula'])->name('save-configurable-door-formula');

    // O&M Manual
    Route::get('/settingOMmanual', [App\Http\Controllers\setting\OMmanualSEttingController::class,'settingOMmanual'])->name('settingOMmanual');
    Route::post('/submitIntroductionPDF', [App\Http\Controllers\setting\OMmanualSEttingController::class,'submitIntroductionPDF'])->name('submitIntroductionPDF');
    Route::post('/submitArchitecIronmon', [App\Http\Controllers\setting\OMmanualSEttingController::class,'submitArchitecIronmon'])->name('submitArchitecIronmon');
    Route::post('/submitDoorFurniture', [App\Http\Controllers\setting\OMmanualSEttingController::class,'submitDoorFurniture'])->name('submitDoorFurniture');
});


// Order
Route::prefix('order')->group(function (){
    Route::get('/orderlist', [App\Http\Controllers\order\OrderController::class,'orderlist'])->name('orderlist');
    Route::match(['get','post'],'/suborderlist', [App\Http\Controllers\order\OrderController::class,'suborderlist'])->name('suborderlist');
    Route::get('/ommanual/{id}/{vid}', [App\Http\Controllers\order\OMMAnualController::class,'ommanual'])->name('ommanual');
    Route::get('/generate/{id}', [App\Http\Controllers\order\OrderController::class,'OrderDetails'])->name('order/generate/');
    Route::get('/pdf-test', [App\Http\Controllers\order\OrderController::class,'pdf_test'])->name('order/pdf-test');

    // all data export for Order
    Route::get('/orderlistAllExport', [App\Http\Controllers\order\OrderController::class,'orderlistAllExport'])->name('orderlistAllExport');
    //end
});

//Door Dimension
Route::get('/door-dimension-add', [App\Http\Controllers\DoorDimensionController::class,'index'])->name('door-dimension-add');
Route::get('/door-dimension-list/{id}', [App\Http\Controllers\DoorDimensionController::class,'list'])->name('door-dimension-list');
Route::post('/door-dimension-list/store', [App\Http\Controllers\DoorDimensionController::class,'store'])->name('door-dimension-list-store');
Route::post('/door-dimension-list/store-custome', [App\Http\Controllers\DoorDimensionController::class,'storeCustome'])->name('door-dimension-list-store-custome');
Route::post('/door-dimension/edit/{id}', [App\Http\Controllers\DoorDimensionController::class,'edit'])->name('DoorDimension/edit');
Route::post('/door-dimension/delete', [App\Http\Controllers\DoorDimensionController::class,'dimension_delete'])->name('DoorDimension/delete');
// Color
Route::prefix('colors')->group(function (){
    Route::get('/create-color', [App\Http\Controllers\ColorController::class,'createColor'])->name('create-color');
    Route::post('/store-color', [App\Http\Controllers\ColorController::class,'storeColor'])->name('store-color');
    // Route::get('/edit-color/{pageId}/{id}', [App\Http\Controllers\ColorController::class,'createColor'])->name('edit-color');
    Route::get('/color-list', [App\Http\Controllers\ColorController::class,'colorList'])->name('color-list');
    Route::get('/color-list-company', [App\Http\Controllers\ColorController::class,'colorListCompany'])->name('color-list-company');
    Route::post('/update-color', [App\Http\Controllers\ColorController::class,'updateSelectedColorOption'])->name('update-color');
    Route::get('/color', [App\Http\Controllers\ColorController::class,'selectColorOption'])->name('color');

    Route::post('/editcolor', [App\Http\Controllers\ColorController::class,'editcolor'])->name('editcolor');
    Route::post('/deletecolor', [App\Http\Controllers\ColorController::class,'deletecolor'])->name('colors/deletecolor');
    Route::post('/ColorDoorLeafFacing', [App\Http\Controllers\ColorController::class,'ColorDoorLeafFacing'])->name('ColorDoorLeafFacing');
});
});

Route::get('/phpmailer', [App\Http\Controllers\PhpMailerController::class,'index'])->name('phpmailer');

Route::post('/notification/markRead', [App\Http\Controllers\DashboardController::class,'notificationMarkRead'])->name('notification/markRead');

Route::prefix('survey')->group(function () {
    Route::get('/add', [App\Http\Controllers\SurveyController::class,'add'])->name('survey/add');
    Route::get('/list', [App\Http\Controllers\SurveyController::class,'list'])->name('survey/list');
    Route::post('/store', [App\Http\Controllers\SurveyController::class,'store'])->name('survey/store');
    Route::get('/details/{id}', [App\Http\Controllers\SurveyController::class,'details'])->name('survey/details');
    Route::get('/profile', [App\Http\Controllers\SurveyController::class,'profile'])->name('survey/profile');
    Route::get('/edit/{id}', [App\Http\Controllers\SurveyController::class,'edit'])->name('survey/edit');
    Route::post('/delete', [App\Http\Controllers\SurveyController::class,'delete'])->name('survey/delete');
    Route::post('/status-change', [App\Http\Controllers\SurveyController::class,'statusChange'])->name('survey/disable');
    Route::post('/update-check-option', [App\Http\Controllers\ProjectController2::class,'updateCheckedOption'])->name('survey/update-check-option');
    Route::post('/project-quotation-survey', [App\Http\Controllers\ProjectController2::class,'projectQuotationSurvey'])->name('survey/project-quotation-survey');
    Route::post('/update-from-to-date', [App\Http\Controllers\ProjectController2::class,'updateFromToDate'])->name('survey/update-from-to-date');
    Route::post('/update-tasks', [App\Http\Controllers\ProjectController2::class,'updateTasks'])->name('survey/update-tasks');
    Route::post('/update-attachment', [App\Http\Controllers\ProjectController2::class,'updateAttachment'])->name('survey/update-attachment');
    Route::post('/update-change-request', [App\Http\Controllers\ProjectController2::class,'updateChangeRequest'])->name('survey/update-change-request');
});
