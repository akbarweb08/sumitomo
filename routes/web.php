<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Master Data
    Route::get('/masterdata', [\App\Http\Controllers\MasterDataController::class, 'index'])->name('masterdata.index');
    Route::post('/masterdata', [\App\Http\Controllers\MasterDataController::class, 'store'])->name('masterdata.store');
    Route::post('/masterdata/update', [\App\Http\Controllers\MasterDataController::class, 'update'])->name('masterdata.update');
    Route::delete('/masterdata/{id}', [\App\Http\Controllers\MasterDataController::class, 'destroy'])->name('masterdata.destroy');
    
    // Master User
    Route::get('/masteruser', [\App\Http\Controllers\MasterUserController::class, 'index'])->name('masteruser.index');
    Route::post('/masteruser', [\App\Http\Controllers\MasterUserController::class, 'store'])->name('masteruser.store');
    Route::post('/masteruser/update', [\App\Http\Controllers\MasterUserController::class, 'update'])->name('masteruser.update');
    Route::delete('/masteruser/{id}', [\App\Http\Controllers\MasterUserController::class, 'destroy'])->name('masteruser.destroy');

    // Master Receipt
    Route::get('/masterreceipt', [\App\Http\Controllers\MasterReceiptController::class, 'index'])->name('masterreceipt.index');
    Route::post('/masterreceipt', [\App\Http\Controllers\MasterReceiptController::class, 'store'])->name('masterreceipt.store');
    Route::delete('/masterreceipt/{id?}', [\App\Http\Controllers\MasterReceiptController::class, 'destroy'])->name('masterreceipt.destroy');

    // Master Supplier
    Route::get('/mastersupplier', [\App\Http\Controllers\MasterSupplierController::class, 'index'])->name('mastersupplier.index');
    Route::post('/mastersupplier', [\App\Http\Controllers\MasterSupplierController::class, 'store'])->name('mastersupplier.store');
    Route::post('/mastersupplier/update', [\App\Http\Controllers\MasterSupplierController::class, 'update'])->name('mastersupplier.update');
    Route::delete('/mastersupplier/{id}', [\App\Http\Controllers\MasterSupplierController::class, 'destroy'])->name('mastersupplier.destroy');

    // Pallet Data
    Route::get('/datalist', [\App\Http\Controllers\DataListController::class, 'index'])->name('datalist.index');
    Route::get('/reporting', [\App\Http\Controllers\ReportingController::class, 'index'])->name('reporting.index');
    Route::get('/recordeddata', [\App\Http\Controllers\RecordedDataController::class, 'index'])->name('recordeddata.index');
    Route::get('/recordeddata/show', [\App\Http\Controllers\RecordedDataController::class, 'show'])->name('recordeddata.show');
    // Box Error & Pallet Error
    Route::get('/boxerror', [\App\Http\Controllers\BoxErrorController::class, 'index'])->name('boxerror.index');
    Route::post('/boxerror/fix', [\App\Http\Controllers\BoxErrorController::class, 'fix'])->name('boxerror.fix');
    Route::get('/palleterror', [\App\Http\Controllers\PalletErrorController::class, 'index'])->name('palleterror.index');

    // Sketch Module
    Route::get('/sketch/{lot}', [\App\Http\Controllers\SketchController::class, 'show'])->name('sketch.show');
    Route::post('/sketch/deleteInvoice', [\App\Http\Controllers\PalletProcessController::class, 'deleteInvoice'])->name('sketch.deleteInvoice');
    Route::post('/sketch/editColor', [\App\Http\Controllers\PalletProcessController::class, 'editColor'])->name('sketch.editColor');
    Route::post('/sketch/saveColor', [\App\Http\Controllers\PalletProcessController::class, 'saveColor'])->name('sketch.saveColor');
    Route::post('/sketch/palletAction', [\App\Http\Controllers\PalletProcessController::class, 'palletAction'])->name('sketch.palletAction');
    Route::post('/sketch/record', [\App\Http\Controllers\PalletProcessController::class, 'recordData'])->name('sketch.record');
    Route::get('/sketch/move/proses', [\App\Http\Controllers\PalletProcessController::class, 'prosesDataMove'])->name('sketch.move');
    
    // Driver Tasks
    Route::get('/tugas', [\App\Http\Controllers\DriverTaskController::class, 'index'])->name('tugas.index');
    Route::get('/admin/tasks', [\App\Http\Controllers\DriverTaskController::class, 'adminIndex'])->name('admin.tasks');
    Route::get('/fetch-drivers', [\App\Http\Controllers\DriverTaskController::class, 'fetchDrivers'])->name('driver.fetch');
    Route::post('/assign-driver', [\App\Http\Controllers\DriverTaskController::class, 'assign'])->name('driver.assign');
    Route::post('/tugas/{id}/complete', [\App\Http\Controllers\DriverTaskController::class, 'complete'])->name('tugas.complete');
});
