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


// Auth::routes();
//
// Route::get('/login', function () {
//     return view('login');
// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');
Route::get('/register', function () {
    return view('error/404');
});


// Administrator -----------------------------------------------
Route::group(['prefix' => '/administrator'], function()
{
  Route::resource('user', 'UserController');
  Route::resource('role', 'RoleController');
  Route::resource('ubahpassword', 'UbahPassController');
  Route::resource('ubahpasswordsaya', 'UbahPasssayaController');
});




// MASTER --------------------------------------------------------------------------------------------------

// -- item biaya--------------------------------------------------------------------------------------
Route::group(['prefix' => '/item_biaya'], function()
{
  Route::get('/', 'CostItemController@index')->name('Item Biaya')->middleware('access:CanView');
  Route::get('/create', 'CostItemController@create')->name('Item Biaya')->middleware('access:CanAdd');
  Route::post('/create_post', 'CostItemController@createpost')->name('Item Biaya')->middleware('access:CanAdd');
  Route::get('/edit/{Cost_Item_Id?}', 'CostItemController@edit')->name('Item Biaya')->middleware('access:CanEdit');
  Route::post('/edit_post/{Cost_Item_Id?}', 'CostItemController@editpost')->name('Item Biaya')->middleware('access:CanEdit');
  Route::post('/delete/{Cost_Item_Id?}', 'CostItemController@delete')->name('Item Biaya')->middleware('access:CanDelete');
});
// -- akhir item biaya--------------------------------------------------------------------------------//

// AKHIR MASTER ---------------------------------------------------------------------------------------------//







// BIAYA MATA KULIAH -------------------------------------------------------------------------------------------

// -- set jenis mata kuliah --------------------------------------------------------------------------------------
Route::group(['prefix' => '/set_jenis_mata_kuliah'], function()
{
  Route::get('', 'CourseCostTypeController@index')->name('Set Jenis Mata Kuliah')->middleware('access:CanView');
  Route::post('/create', 'CourseCostTypeController@createpost')->name('Set Jenis Mata Kuliah')->middleware('access:CanAdd');
});
// -- akhir set jenis mata kuliah -------------------------------------------------------------------------------//

// -- biaya per sks ----------------------------------------------------------------------------------------------
Route::group(['prefix' => '/biaya_per_sks'], function()
{
  Route::get('','CourseCostSksController@index')->name('Biaya Per Sks')->middleware('access:CanView');
  Route::get('/create','CourseCostSksController@create')->name('Biaya Per Sks')->middleware('access:CanAdd');
  Route::post('/create_post', 'CourseCostSksController@create_post')->name('Biaya Per Sks')->middleware('access:CanAdd');
  Route::get('/edit/{Course_Cost_Sks_Id?}','CourseCostSksController@edit')->name('Biaya Per Sks')->middleware('access:CanEdit');
  Route::post('/edit_post/{Course_Cost_Sks_Id?}','CourseCostSksController@edit_post')->name('Biaya Per Sks')->middleware('access:CanEdit');
  Route::get('/delete/{Course_Cost_Sks_Id?}','CourseCostSksController@delete')->name('Biaya Per Sks')->middleware('access:CanDelete');
  Route::get('/cetakSks', 'CourseCostSksController@cetakSks')->name('Biaya Per Sks')->middleware('access:CanCetak');// jangan lupa kasih acsess
});
// -- akhir biaya per sks --------------------------------------------------------------------------------------//

// -- biaya per paket ----------------------------------------------------------------------------------------------
Route::group(['prefix' => '/biaya_per_paket'], function()
{
  Route::get('','CourseCostPackageController@index')->name('Biaya Per Paket')->middleware('access:CanView');
  Route::get('/create','CourseCostPackageController@create')->name('Biaya Per Paket')->middleware('access:CanAdd');
  Route::post('/create_post', 'CourseCostPackageController@create_post')->name('Biaya Per Paket')->middleware('access:CanAdd');
  Route::get('/edit/{Course_Cost_Package_Id?}','CourseCostPackageController@edit')->name('Biaya Per Paket')->middleware('access:CanEdit');
  Route::post('/edit_post/{Course_Cost_Package_Id?}','CourseCostPackageController@edit_post')->name('Biaya Per Paket')->middleware('access:CanEdit');
  Route::get('/delete/{Course_Cost_Package_Id?}','CourseCostPackageController@delete')->name('Biaya Per Paket')->middleware('access:CanDelete');
});
// -- akhir biaya per paket --------------------------------------------------------------------------------------//

// AKHIR BIAYA MATA KULIAH -------------------------------------------------------------------------------------//






// BIAYA REGISTRASI --------------------------------------------------------------------------------------------

// -- Set Biaya Registrasi ------------------------------------------------------------------------------------
Route::group(['prefix' => 'set_biaya_registrasi'], function()
{
  Route::get('','CostSchedController@index')->name('Set Biaya Registrasi')->middleware('access:CanView');
  Route::get('/create','CostSchedController@Create')->name('Set Biaya Registrasi')->middleware('access:CanAdd');
  Route::post('/create_post','CostSchedController@Create_Post')->name('Set Biaya Registrasi')->middleware('access:CanAdd');
  Route::get('/copy_data','CostSchedController@Copy_Data')->name('Set Biaya Registrasi')->middleware('access:CanCopyData');
  Route::post('/copy_data','CostSchedController@Copy_Data_Post')->name('Set Biaya Registrasi')->middleware('access:CanCopyData');
  Route::post('/Set_Student_Bill','CostSchedController@Set_Student_Bill')->name('Set Biaya Registrasi')->middleware('access:CanHitungTagihan');
  Route::get('/edit/{Cost_Sched_Id?}','CostSchedController@Edit')->name('Set Biaya Registrasi')->middleware('access:CanEdit');
  Route::post('/edit/{Cost_Sched_Id?}','CostSchedController@Edit_Post')->name('Set Biaya Registrasi')->middleware('access:CanEdit');
  Route::get('/delete/{Cost_Sched_Id?}','CostSchedController@delete')->name('Set Biaya Registrasi')->middleware('access:CanDelete');

  Route::get('/re-insert','CostSchedController@ReInsert')->name('set_registrasi.reinsert');
});
// -- akhir Set Biaya Registrasi ------------------------------------------------------------------------------

// -- Set Biaya Registrasi (Resume) ----------------------------------------------------------------------------
Route::group(['prefix' => 'set_biaya_registrasi_resume'], function()
{
  Route::get('/resume','CostSchedDetailController@Resume')->name('Set Biaya Registrasi Resume')->middleware('access:CanView');
  Route::get('/edit/{Cost_Sched_Detail_Id}','CostSchedDetailController@Edit')->name('Set Biaya Registrasi Resume')->middleware('access:CanEdit');
  Route::post('/edit/{Cost_Sched_Detail_Id}','CostSchedDetailController@Edit_Post')->name('Set Biaya Registrasi Resume')->middleware('access:CanEdit');
});
// -- akhir Set Biaya Registrasi (Resume) ----------------------------------------------------------------------

// -- Set Biaya Tambahan --------------------------------------------------------------------------------------
Route::group(['prefix' => 'set_biaya_tambahan'], function()
{
  Route::get('','CostSchedPersonalPlusController@Index')->name('Set Biaya Tambahan')->middleware('access:CanView');
  Route::get('/create','CostSchedPersonalPlusController@Create')->name('Set Biaya Tambahan')->middleware('access:CanAdd');
  Route::post('/create_post','CostSchedPersonalPlusController@Create_Post')->name('Set Biaya Tambahan')->middleware('access:CanAdd');
  Route::get('/edit/{Cost_Sched_Personal_Plus_Id?}','CostSchedPersonalPlusController@Edit')->name('Set Biaya Tambahan')->middleware('access:CanEdit');
  Route::post('/edit_post/{Cost_Sched_Personal_Plus_Id?}','CostSchedPersonalPlusController@Edit_Post')->name('Set Biaya Tambahan')->middleware('access:CanEdit');
  Route::post('/delete/{Cost_Sched_Personal_Plus_Id?}','CostSchedPersonalPlusController@Delete')->name('Set Biaya Tambahan')->middleware('access:CanDelete');

  Route::get('/Department_Id','CostSchedPersonalPlusController@Department_Id')->name('Set Biaya Tambahan')->middleware('access:CanView');
  Route::get('/Register_Number','CostSchedPersonalPlusController@Register_Number')->name('Set Biaya Tambahan')->middleware('access:CanView');
});

// -- akhir set biaya tambahan --------------------------------------------------------------------------------

// Set Minimum Pembayaran Open
Route::group(['prefix' => 'set-minimum-pembayaran-open'], function () {
  Route::get('', 'SetMinimunOpenPaymentController@Index')->name('set-minimum.index');
  Route::post('', 'SetMinimunOpenPaymentController@Create')->name('set-minimum.create');
  Route::put('', 'SetMinimunOpenPaymentController@Update')->name('set-minimum.update');
  Route::delete('', 'SetMinimunOpenPaymentController@Delete')->name('set-minimum.delete');
});

// Perizinan KRS dan Ujian
Route::group(['prefix' => 'perizinan-krs-dan-ujian'], function () {
  Route::get('', 'SetStudentAllowedKRSController@Index')->name('perizinan-krs.index');

  Route::put('/krs', 'SetStudentAllowedKRSController@Update')->name('perizinan-krs.update');
  Route::delete('/krs', 'SetStudentAllowedKRSController@Delete')->name('perizinan-krs.delete');

  Route::put('/ujian', 'SetStudentAllowedExamController@Update')->name('perizinan-ujian.update');
  Route::delete('/ujian', 'SetStudentAllowedExamController@Delete')->name('perizinan-ujian.delete');
});

// AKHIR BIAYA REGISTRASI ------------------------------------------------------------------------------------//

// -- Set Ulang biaya pendaftaran ------------------------------
Route::group(['prefix' => 'set_ulang_biaya_pendaftaran'], function ()
{
  Route::get('','ResetRegistrationFeeController@index')->name('Set Ulang Biaya Pendaftaran')->middleware('access:CanView');
  Route::get('/hitungulangsatu', 'ResetRegistrationFeeController@hitungulangsatu')->name('Set Ulang Biaya Pendaftaran')->middleware('access:CanHitung');
});
// -- Set Ulang biaya pendaftaran -----------------------------


// PEMBAYARAN MAHASISWA --------------------------------------------------------------------------------------------

// -- Set Biaya Registrasi Personal ------------------------------------------------------------------------------------
Route::group(['prefix' => 'set_biaya_registrasi_personal'], function()
{
  Route::get('','CostSchedPersonalController@index')->name('Set Biaya Registrasi Personal')->middleware('access:CanView');
  Route::get('/create','CostSchedPersonalController@create')->name('Set Biaya Registrasi Personal')->middleware('access:CanAdd');
  Route::post('/create_post','CostSchedPersonalController@create_post')->name('Set Biaya Registrasi Personal')->middleware('access:CanAdd');
  Route::get('/detail/{Cost_Sched_Personal_Id?}','CostSchedPersonalController@detail')->name('Set Biaya Registrasi Personal')->middleware('access:CanView');
  Route::get('/edit/{Cost_Sched_Personal_Id?}','CostSchedPersonalController@edit')->name('Set Biaya Registrasi Personal')->middleware('access:CanEdit');
  Route::post('/edit_post/{Cost_Sched_Personal_Id?}','CostSchedPersonalController@edit_post')->name('Set Biaya Registrasi Personal')->middleware('access:CanEdit');
  Route::get('/delete/{Cost_Sched_Personal_Id?}','CostSchedPersonalController@delete')->name('Set Biaya Registrasi Personal')->middleware('access:CanDelete');
  Route::get('/simpan','CostSchedPersonalController@simpan')->name('Set Biaya Registrasi Personal');
  Route::get('/create_amount','CostSchedPersonalController@create_amount')->name('Set Biaya Registrasi Personal');

  // ajaxx-----------
  Route::post('/create_post_ajax','CostSchedPersonalController@create_post_ajax')->name('Set Biaya Registrasi Personal')->middleware('access:CanAdd');
  // detaill----------------
  Route::post('/insert_detail_post_ajax','CostSchedPersonalController@insert_detail_post_ajax')->name('Set Biaya Registrasi Personal');
  Route::post('/edit_detail_post_ajax/{Cost_Sched_Personal_Detail_Id}','CostSchedPersonalController@edit_detail_post_ajax')->name('Set Biaya Registrasi Personal');
  Route::get('/delete_detail_ajax/{Cost_Sched_Personal_Detail_Id}','CostSchedPersonalController@delete_detail_ajax')->name('Set Biaya Registrasi Personal');
});
Route::group(['prefix' => 'set_biaya_registrasi_personal_detail'], function()
{
  Route::get('/create','CostSchedPersonalController@create_detail')->name('Set Biaya Registrasi Personal')->middleware('access:CanAdd');
  Route::post('/create_post','CostSchedPersonalController@create_detail_post')->name('Set Biaya Registrasi Personal')->middleware('access:CanAdd');
  Route::get('/edit/{Cost_Sched_Personal_Detail_Id}','CostSchedPersonalController@edit_detail')->name('Set Biaya Registrasi Personal')->middleware('access:CanEdit');
  Route::post('/edit_post/{Cost_Sched_Personal_Detail_Id}','CostSchedPersonalController@edit_detail_post')->name('Set Biaya Registrasi Personal')->middleware('access:CanEdit');
  Route::get('/delete/{Cost_Sched_Personal_Detail_Id}','CostSchedPersonalController@delete_detail')->name('Set Biaya Registrasi Personal')->middleware('access:CanDelete');
});

Route::group(['prefix' => 'riwayat_pembayaran_details'], function()
{
  Route::get('/','RiwayatPembayaranDetailsController@index')->name("Riwayat Pembayaran Details")->middleware('access:CanView');
  Route::get('/pdf/{Reff_Payment_Id?}','RiwayatPembayaranDetailsController@pdf')->name("Riwayat Pembayaran Details Create")->middleware('access:CanView');
  Route::get('/kwitansi/{Reff_Payment_Id?}','RiwayatPembayaranDetailsController@kwitansi')->name("Riwayat Pembayaran Details Create")->middleware('access:CanView');
});
// -- akhir Set Biaya Registrasi Personal ------------------------------------------------------------------------------//

// AKHIR PEMBAYARAN MAHASISWAI ------------------------------------------------------------------------------------//

Route::group(['prefix' => 'set_biaya_keyin'], function()
{
  Route::get('','CostSchedDispensasi@index')->name('Set Biaya Dispensasi Keyin');
  Route::get('/create','CostSchedDispensasi@create')->name('Set Biaya Dispensasi Keyin');
  Route::post('/create_post','CostSchedDispensasi@create_post')->name('Set Biaya Dispensasi Keyin');
  Route::post('/edit_post/{Student_Cost_Krs_Personal_Id}','CostSchedDispensasi@edit_post')->name('Set Biaya Dispensasi Keyin');
  Route::get('/edit/{Student_Cost_Krs_Personal_Id}','CostSchedDispensasi@edit')->name('Set Biaya Dispensasi Keyin');
  Route::post('/delete/{Student_Cost_Krs_Personal_Id}','CostSchedDispensasi@delete')->name('Set Biaya Dispensasi Keyin');
});

// RETUR ------------------------------------------------------------------------------------------------------------

//  Set_Aturan_Pengembalian.........................................................................................
Route::group(['prefix' => 'Set_Aturan_Pengembalian'], function()
{
  Route::get('', 'StudentPaymentController@index')->name('Set Aturan Pengembalian')->middleware('access:CanView');
  Route::get('Create', 'StudentPaymentController@create')->name('Set Aturan Pengembalian')->middleware('access:CanAdd');
  Route::get('/Student', 'StudentPaymentController@student')->name('Set Aturan Pengembalian')->middleware('access:CanView');
  Route::post('/Create_Post', 'StudentPaymentController@create_post')->name('Set Aturan Pengembalian')->middleware('access:CanAdd');
  Route::get('/cetak/{id?}', 'StudentPaymentController@cetak')->name('Set Aturan Pengembalian')->middleware('access:CanCetak');
  Route::get('/pdf/{id?}', 'StudentPaymentController@pdf')->name('Set Aturan Pengembalian')->middleware('access:CanCetak');
  Route::get('/edit/{id?}', 'StudentPaymentController@edit')->name('Set Aturan Pengembalian')->middleware('access:CanEdit');
  Route::post('/edit_post', 'StudentPaymentController@edit_post')->name('Set Aturan Pengembalian')->middleware('access:CanEdit')->middleware('access:CanCetak');
  Route::post('/delete','StudentPaymentController@delete')->name('Set Aturan Pengembalian')->middleware('access:CanDelete');
});
// Akhir Set_Aturan_Pengembalian-----------------------------------------------------------------------------------


// AKHIR RETUR ------------------------------------------------------------------------------------------------------//







// TELLER -----------------------------------------------------------------------------------------------------------

// Entry Pembayaran Mahasiswa ---------------------------------------------------------------------------------------------------
Route::group(['prefix' => 'Entry_Pembayaran_Mahasiswa'], function()
{
  Route::get('','StudentBillController@Index')->name('Entry Pembayaran Mahasiswa')->middleware('access:CanView');
  Route::get('/cancel/{ReffPaymentId?}','StudentBillController@Cancel')->name('Entry Pembayaran Mahasiswa');
  Route::post('/cancel_post/{ReffPaymentId?}','StudentBillController@Cancel_Post')->name('Entry Pembayaran Mahasiswa')->middleware('access:CanBatalTagihan');;
  Route::get('/Edit/{param?}','StudentBillController@Edit')->name('Entry Pembayaran Mahasiswa')->middleware('access:CanEditTagihan');
  Route::get('/Edit_Post','StudentBillController@Edit_Post')->name('Entry Pembayaran Mahasiswa')->middleware('access:CanEditTagihan');
  Route::post('/Bayar','StudentBillController@Bayar')->name('Entry Pembayaran Mahasiswa');
});

Route::group(['prefix' => "open-payment"], function () {
  Route::post('/non-krs', 'OpenPaymentController@PaymentNonKRS')->name('open-payment.nonkrs');
  Route::delete('/non-krs', 'OpenPaymentController@DeletePaymentNonKRS')->name('open-payment.nonkrs.delete');

  Route::get('/student/{id}', 'OpenPaymentController@getReportByMhs')->name('open-payment.report.by-student');
  Route::get('/', 'OpenPaymentController@getReportByDate')->name('open-payment.report');
});

Route::group(['prefix' => "bills"], function () {
  Route::get('/student/{id}', 'OpenPaymentController@GetBills')->name('bills.by-student');
});
//akhir Entry Pembayaran Mahasiswa =============================================================================================//

// Slip Pembayaran Mahasiswa ---------------------------------------------------------------------------------------------------
Route::group(['prefix' => "payment-receipt"], function () {
  Route::get('', 'PaymentReceiptController@Index')->name('payment-receipt.index');
});
//akhir Slip Pembayaran Mahasiswa =============================================================================================//

// laporan --------------------------------------------------------------------------------------------------------------------
Route::group(['prefix' => 'laporan'], function()
{
  Route::get('/Lp_Prodi','LaporanController@Lp_Prodi')->name('Laporan Prodi')->middleware('access:CanView');// -- laporan pembayaran prodi
  Route::get('/Lp_Mahasiswa','LaporanController@Lp_Mahasiswa')->name('Laporan Mahasiswa')->middleware('access:CanView'); // -- laporan pembayaran Mahasiswa
  Route::get('/Rp_Mahasiswa','LaporanController@RP_Mahasiswa')->name('Riwayat Pembayaran Mahasiswa')->middleware('access:CanView'); // -- Riwayat pembayaran Mahasiswa
  Route::get('/Lp_Bank','LaporanController@Lp_Bank')->name('Laporan Bank')->middleware('access:CanView');// -- laporan pembayaran Bank
  Route::get('/Lp_Bank_Detail','LaporanController@Lp_Hari_Bank')->name('Laporan Bank')->middleware('access:CanView'); // -- laporan pembayaran Bank Detail
  Route::get('/P_Mahasiswa_Item','LaporanController@P_Mahasiswa_Item')->name('Pembayaran Mahasiswa Item')->middleware('access:CanView'); // --  Pembayaran Mahasiswa Per Item
  Route::get('/D_Mahasiswa_Item','LaporanController@D_Mahasiswa_Item')->name('Pembayaran Mahasiswa Item')->middleware('access:CanView'); // --  Pembayaran Mahasiswa Per Item
  Route::get('/Lp_Tunggakan_Mahasiswa','LaporanController@Lp_Tunggakan_Mahasiswa')->name('Laporan Tunggakan Mahasiswa')->middleware('access:CanView'); // --  Laporan Tunggakan Mahasiswa
  Route::get('/Detail_Lp_Tunggakan_Mahasiswa','LaporanController@Detail_Lp_Tunggakan_Mahasiswa')->name('Laporan Tunggakan Mahasiswa')->middleware('access:CanViewDetail'); // --  Detail Laporan Tunggakan Mahasiswa

  Route::get('/send_email', 'LaporanController@sendEmail')->name('Laporan Tunggakan Mahasiswa');
});
// Akhir Laporan =============================================================================================================//

// AKHIR TELLER -----------------------------------------------------------------------------------------------------//



// ERROR HANDLER ------------------------------------------
// Route::get('/error/404', function () {
//     rrn View('error/404');
// });


// Akhir ERROR HANDLER =====================================

//--for test
// Route::get('/test', function () {
//     return View('testing');
// });

Route::get('/changePassword','UserController@index')->name('Ubah Password');
Route::post('/changePassword','UserController@editpost')->name('Ubah Password');

Route::group(['prefix' => "student"], function () {
  Route::get('/get-token', 'TokenController@GetToken')->name('student.get-token');

  Route::get('/{id}/deposit','DepositController@GetDeposit')->name('student.get-deposit');
});