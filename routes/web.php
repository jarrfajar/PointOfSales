<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenerimaanBarangController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReturPenerimaanBarangController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\StokBarangController;
use App\Http\Controllers\SupplierController;
use App\Models\HeaderPenerimaanBarang;
use App\Models\StockBarang;
use App\Services\SerialNumberService;
use Illuminate\Database\Eloquent\Collection;
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
Route::get('/login', [AuthController::class, 'ShowLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::redirect('/', '/dashboard');
    Route::get('/dashboard', function () {
        return view('pages.dashboard-ecommerce-dashboard', ['type_menu' => 'dashboard']);
    });
});

// Dashboard
Route::get('/dashboard-general-dashboard', function () {
    return view('pages.dashboard-general-dashboard', ['type_menu' => 'dashboard']);
});

Route::get('/test', function () {
    $penerimaan_barang = HeaderPenerimaanBarang::with('barangs')->find(1);

    return response()->json(['data' => $penerimaan_barang]);
});

Route::get('/cabang', [BranchController::class, 'index'])->name('cabang');
Route::get('/cabang/{id}', [BranchController::class, 'show']);
Route::post('/cabang', [BranchController::class, 'store']);
Route::put('/cabang/{id}', [BranchController::class, 'update']);
Route::delete('/cabang/{id}', [BranchController::class, 'destroy']);

Route::get('/gudang', [GudangController::class, 'index'])->name('gudang');
Route::get('/gudang/{id}', [GudangController::class, 'show']);
Route::post('/gudang', [GudangController::class, 'store']);
Route::put('/gudang/{id}', [GudangController::class, 'update']);
Route::delete('/gudang/{id}', [GudangController::class, 'destroy']);
Route::get('/gudang-search', [GudangController::class, 'search']);

Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan');
Route::get('/satuan/{id}', [SatuanController::class, 'show']);
Route::post('/satuan', [SatuanController::class, 'store']);
Route::put('/satuan/{id}', [SatuanController::class, 'update']);
Route::delete('/satuan/{id}', [SatuanController::class, 'destroy']);
Route::get('/satuan-search', [SatuanController::class, 'search']);

Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
Route::get('/kategori/{id}', [KategoriController::class, 'show']);
Route::post('/kategori', [KategoriController::class, 'store']);
Route::put('/kategori/{id}', [KategoriController::class, 'update']);
Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);
Route::get('/kategori-search', [KategoriController::class, 'search']);

Route::get('/barang', [BarangController::class, 'index'])->name('barang');
Route::get('/barang/{id}', [BarangController::class, 'show']);
Route::post('/barang', [BarangController::class, 'store']);
Route::put('/barang/{id}', [BarangController::class, 'update']);
Route::delete('/barang/{id}', [BarangController::class, 'destroy']);
Route::get('/barang-search', [BarangController::class, 'search']);

Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier');
Route::get('/supplier/{id}', [SupplierController::class, 'show']);
Route::post('/supplier', [SupplierController::class, 'store']);
Route::put('/supplier/{id}', [SupplierController::class, 'update']);
Route::delete('/supplier/{id}', [SupplierController::class, 'destroy']);
Route::get('/supplier-search', [SupplierController::class, 'search']);

/**----------------------------------PEMBELIAN----------------------------------- */
Route::get('/purchase-order', [PurchaseOrderController::class, 'index'])->name('purchase_order');
Route::get('/purchase-order/{id}', [PurchaseOrderController::class, 'show']);
Route::get('/purchase-order/get/{id}', [PurchaseOrderController::class, 'get']);
Route::post('/purchase-order', [PurchaseOrderController::class, 'store']);
Route::put('/purchase-order/{id}', [PurchaseOrderController::class, 'update']);
Route::delete('/purchase-order/{id}', [PurchaseOrderController::class, 'destroy']);
Route::put('/purchase-order/approve/{id}', [PurchaseOrderController::class, 'approve']);
Route::put('/purchase-order/reject/{id}', [PurchaseOrderController::class, 'reject']);

Route::get('/penerimaan-barang', [PenerimaanBarangController::class, 'index'])->name('bapb');
Route::get('/penerimaan-barang/{id}', [PenerimaanBarangController::class, 'show']);
Route::post('/penerimaan-barang', [PenerimaanBarangController::class, 'store']);
Route::put('/penerimaan-barang/{id}', [PenerimaanBarangController::class, 'update']);
Route::delete('/penerimaan-barang/{id}', [PenerimaanBarangController::class, 'destroy']);

Route::get('/retur-penerimaan-barang', [ReturPenerimaanBarangController::class, 'index']);
Route::get('/retur-penerimaan-barang/{id}', [ReturPenerimaanBarangController::class, 'show']);
Route::post('/retur-penerimaan-barang', [ReturPenerimaanBarangController::class, 'store']);
Route::put('/retur-penerimaan-barang/{id}', [ReturPenerimaanBarangController::class, 'update']);
Route::delete('/retur-penerimaan-barang/{id}', [ReturPenerimaanBarangController::class, 'destroy']);

/**----------------------------------STOK BARANG----------------------------------- */
Route::get('/stok-barang', [StokBarangController::class, 'index']);
Route::get('/barang-masuk', [StokBarangController::class, 'masuk']);
Route::get('/barang-keluar', [StokBarangController::class, 'keluar']);
Route::get('/barang-retur', [StokBarangController::class, 'retur']);


// Layout
Route::get('/layout-default-layout', function () {
    return view('pages.layout-default-layout', ['type_menu' => 'layout']);
});

// Blank Page
Route::get('/blank-page', function () {
    return view('pages.blank-page', ['type_menu' => '']);
});

// Bootstrap
Route::get('/bootstrap-alert', function () {
    return view('pages.bootstrap-alert', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-badge', function () {
    return view('pages.bootstrap-badge', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-breadcrumb', function () {
    return view('pages.bootstrap-breadcrumb', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-buttons', function () {
    return view('pages.bootstrap-buttons', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-card', function () {
    return view('pages.bootstrap-card', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-carousel', function () {
    return view('pages.bootstrap-carousel', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-collapse', function () {
    return view('pages.bootstrap-collapse', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-dropdown', function () {
    return view('pages.bootstrap-dropdown', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-form', function () {
    return view('pages.bootstrap-form', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-list-group', function () {
    return view('pages.bootstrap-list-group', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-media-object', function () {
    return view('pages.bootstrap-media-object', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-modal', function () {
    return view('pages.bootstrap-modal', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-nav', function () {
    return view('pages.bootstrap-nav', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-navbar', function () {
    return view('pages.bootstrap-navbar', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-pagination', function () {
    return view('pages.bootstrap-pagination', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-popover', function () {
    return view('pages.bootstrap-popover', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-progress', function () {
    return view('pages.bootstrap-progress', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-table', function () {
    return view('pages.bootstrap-table', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-tooltip', function () {
    return view('pages.bootstrap-tooltip', ['type_menu' => 'bootstrap']);
});
Route::get('/bootstrap-typography', function () {
    return view('pages.bootstrap-typography', ['type_menu' => 'bootstrap']);
});


// components
Route::get('/components-article', function () {
    return view('pages.components-article', ['type_menu' => 'components']);
});
Route::get('/components-avatar', function () {
    return view('pages.components-avatar', ['type_menu' => 'components']);
});
Route::get('/components-chat-box', function () {
    return view('pages.components-chat-box', ['type_menu' => 'components']);
});
Route::get('/components-empty-state', function () {
    return view('pages.components-empty-state', ['type_menu' => 'components']);
});
Route::get('/components-gallery', function () {
    return view('pages.components-gallery', ['type_menu' => 'components']);
});
Route::get('/components-hero', function () {
    return view('pages.components-hero', ['type_menu' => 'components']);
});
Route::get('/components-multiple-upload', function () {
    return view('pages.components-multiple-upload', ['type_menu' => 'components']);
});
Route::get('/components-pricing', function () {
    return view('pages.components-pricing', ['type_menu' => 'components']);
});
Route::get('/components-statistic', function () {
    return view('pages.components-statistic', ['type_menu' => 'components']);
});
Route::get('/components-tab', function () {
    return view('pages.components-tab', ['type_menu' => 'components']);
});
Route::get('/components-table', function () {
    return view('pages.components-table', ['type_menu' => 'components']);
});
Route::get('/components-user', function () {
    return view('pages.components-user', ['type_menu' => 'components']);
});
Route::get('/components-wizard', function () {
    return view('pages.components-wizard', ['type_menu' => 'components']);
});

// forms
Route::get('/forms-advanced-form', function () {
    return view('pages.forms-advanced-form', ['type_menu' => 'forms']);
});
Route::get('/forms-editor', function () {
    return view('pages.forms-editor', ['type_menu' => 'forms']);
});
Route::get('/forms-validation', function () {
    return view('pages.forms-validation', ['type_menu' => 'forms']);
});

// google maps
// belum tersedia

// modules
Route::get('/modules-calendar', function () {
    return view('pages.modules-calendar', ['type_menu' => 'modules']);
});
Route::get('/modules-chartjs', function () {
    return view('pages.modules-chartjs', ['type_menu' => 'modules']);
});
Route::get('/modules-datatables', function () {
    return view('pages.modules-datatables', ['type_menu' => 'modules']);
});
Route::get('/modules-flag', function () {
    return view('pages.modules-flag', ['type_menu' => 'modules']);
});
Route::get('/modules-font-awesome', function () {
    return view('pages.modules-font-awesome', ['type_menu' => 'modules']);
});
Route::get('/modules-ion-icons', function () {
    return view('pages.modules-ion-icons', ['type_menu' => 'modules']);
});
Route::get('/modules-owl-carousel', function () {
    return view('pages.modules-owl-carousel', ['type_menu' => 'modules']);
});
Route::get('/modules-sparkline', function () {
    return view('pages.modules-sparkline', ['type_menu' => 'modules']);
});
Route::get('/modules-sweet-alert', function () {
    return view('pages.modules-sweet-alert', ['type_menu' => 'modules']);
});
Route::get('/modules-toastr', function () {
    return view('pages.modules-toastr', ['type_menu' => 'modules']);
});
Route::get('/modules-vector-map', function () {
    return view('pages.modules-vector-map', ['type_menu' => 'modules']);
});
Route::get('/modules-weather-icon', function () {
    return view('pages.modules-weather-icon', ['type_menu' => 'modules']);
});

// auth
Route::get('/auth-forgot-password', function () {
    return view('pages.auth-forgot-password', ['type_menu' => 'auth']);
});
Route::get('/auth-login', function () {
    return view('pages.auth-login', ['type_menu' => 'auth']);
});
Route::get('/auth-login2', function () {
    return view('pages.auth-login2', ['type_menu' => 'auth']);
});
Route::get('/auth-register', function () {
    return view('pages.auth-register', ['type_menu' => 'auth']);
});
Route::get('/auth-reset-password', function () {
    return view('pages.auth-reset-password', ['type_menu' => 'auth']);
});

// error
Route::get('/error-403', function () {
    return view('pages.error-403', ['type_menu' => 'error']);
});
Route::get('/error-404', function () {
    return view('pages.error-404', ['type_menu' => 'error']);
});
Route::get('/error-500', function () {
    return view('pages.error-500', ['type_menu' => 'error']);
});
Route::get('/error-503', function () {
    return view('pages.error-503', ['type_menu' => 'error']);
});

// features
Route::get('/features-activities', function () {
    return view('pages.features-activities', ['type_menu' => 'features']);
});
Route::get('/features-post-create', function () {
    return view('pages.features-post-create', ['type_menu' => 'features']);
});
Route::get('/features-post', function () {
    return view('pages.features-post', ['type_menu' => 'features']);
});
Route::get('/features-profile', function () {
    return view('pages.features-profile', ['type_menu' => 'features']);
});
Route::get('/features-settings', function () {
    return view('pages.features-settings', ['type_menu' => 'features']);
});
Route::get('/features-setting-detail', function () {
    return view('pages.features-setting-detail', ['type_menu' => 'features']);
});
Route::get('/features-tickets', function () {
    return view('pages.features-tickets', ['type_menu' => 'features']);
});

// utilities
Route::get('/utilities-contact', function () {
    return view('pages.utilities-contact', ['type_menu' => 'utilities']);
});
Route::get('/utilities-invoice', function () {
    return view('pages.utilities-invoice', ['type_menu' => 'utilities']);
});
Route::get('/utilities-subscribe', function () {
    return view('pages.utilities-subscribe', ['type_menu' => 'utilities']);
});

// credits
Route::get('/credits', function () {
    return view('pages.credits', ['type_menu' => '']);
});
