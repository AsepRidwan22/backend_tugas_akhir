<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutControllers;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ChatMessageController;
use App\Http\Controllers\Api\Pasien\PostController;
use App\Http\Controllers\API\Dokter\IdentitasDokterController;
use App\Http\Controllers\API\Pasien\IdentitasPasienController;
use App\Http\Controllers\API\Pasien\KesehatanController;
use App\Http\Controllers\API\Pasien\KondisiTubuhController;
use App\Http\Controllers\API\Pasien\LaporanTestDarahController;
use App\Http\Controllers\API\Pasien\PemantauanGulaDarahMandiriController;
use App\Http\Controllers\API\Pasien\ProgramLatihanController;
use App\Http\Controllers\API\Pasien\RencanaMakanController;
use App\Http\Controllers\API\Pasien\RencanaPenangananKomplikasiController;
use App\Http\Controllers\API\Pasien\TargetPengetahuanController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Broadcast::routes(['middleware' => ['jwt.verify']]);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'jwt.verify',
], function () {
    Route::apiResource('chat', ChatController::class)->only(['index', 'store', 'show']);
    Route::apiResource('chat_message', ChatMessageController::class)->only(['index', 'store']);
    Route::post('auth/logout', [App\Http\Controllers\API\Auth\LogoutControllers::class, 'performs'])->name('auth.logout');
    Route::get('users', [App\Http\Controllers\API\Auth\UsersControllers::class, 'index'])->name('auth.users');
});

Route::group([
    'prefix' => 'pasien',
    'as' => 'pasien.',
], function () {
    Route::post('auth/login', [LoginController::class, 'pasienLogin'])->name('auth.login')->middleware(['throttle.public:5,60']);
    Route::post('auth/register', [RegisterController::class, 'pasienRegister'])->name('auth.register');
    Route::group([
        'middleware' => ['auth.role:Pasien', 'auth.pin'],
    ], function () {
        Route::get('identitas', [IdentitasPasienController::class, 'index'])->name('identitas.index');
        Route::post('identitas/store', [IdentitasPasienController::class, 'store'])->name('identitas.store');
        Route::put('identitas', [IdentitasPasienController::class, 'update'])->name('identitas.update');
    });

    Route::group([
        'middleware' => ['auth.role:Pasien'],
    ], function () {
        Route::get('kesehatan', [KesehatanController::class, 'index'])->name('kesehatan.index');
        Route::get('kesehatan/get', [KesehatanController::class, 'get'])->name('kesehatan.get');
        Route::post('insert/kesehatan', [KesehatanController::class, 'insert'])->name('kesehatan.insert');

        Route::get('/posts', [PostController::class, "index"]);
        Route::get('/posts/{post:slug}', [PostController::class, "show"]);
        Route::post('update/kesehatan/{id}', [KesehatanController::class, 'update'])->name('kesehatan.update');
        Route::delete('delete/kesehatan/{id}', [KesehatanController::class, 'delete'])->name('kesehatan.delete');

        Route::get('kondisi-tubuh', [KondisiTubuhController::class, 'index'])->name('kondisi-tubuh.index');
        Route::post('insert/kondisi-tubuh', [KondisiTubuhController::class, 'insert'])->name('kondisi-tubuh.insert');
        Route::post('update/kondisi-tubuh/{id}', [KondisiTubuhController::class, 'update'])->name('kondisi-tubuh.update');
        Route::delete('delete/kondisi-tubuh/{id}', [KondisiTubuhController::class, 'delete'])->name('kondisi-tubuh.delete');

        Route::get('laporan-test-darah', [LaporanTestDarahController::class, 'index'])->name('laporan-test-darah.index');
        Route::post('insert/laporan-test-darah', [LaporanTestDarahController::class, 'insert'])->name('laporan-test-darah.insert');
        Route::post('update/laporan-test-darah/{id}', [LaporanTestDarahController::class, 'update'])->name('laporan-test-darah.update');
        Route::delete('delete/laporan-test-darah/{id}', [LaporanTestDarahController::class, 'delete'])->name('laporan-test-darah.delete');

        Route::get('pemantauan-gula-darah-mandiri', [PemantauanGulaDarahMandiriController::class, 'index'])->name('pemantauan-gula-darah-mandiri.index');
        Route::post('insert/pemantauan-gula-darah-mandiri', [PemantauanGulaDarahMandiriController::class, 'insert'])->name('pemantauan-gula-darah-mandiri.insert');
        Route::post('update/pemantauan-gula-darah-mandiri/{id}', [PemantauanGulaDarahMandiriController::class, 'update'])->name('pemantauan-gula-darah-mandiri.update');
        Route::delete('delete/pemantauan-gula-darah-mandiri/{id}', [PemantauanGulaDarahMandiriController::class, 'delete'])->name('pemantauan-gula-darah-mandiri.delete');

        Route::get('program-latihan', [ProgramLatihanController::class, 'index'])->name('program-latihan.index');
        Route::post('insert/program-latihan', [ProgramLatihanController::class, 'insert'])->name('program-latihan.insert');
        Route::post('update/program-latihan/{id}', [ProgramLatihanController::class, 'update'])->name('program-latihan.update');
        Route::delete('delete/program-latihan/{id}', [ProgramLatihanController::class, 'delete'])->name('program-latihan.delete');

        Route::get('rencana-makan', [RencanaMakanController::class, 'index'])->name('rencana-makan.index');
        Route::post('insert/rencana-makan', [RencanaMakanController::class, 'insert'])->name('rencana-makan.insert');
        Route::post('update/rencana-makan/{id}', [RencanaMakanController::class, 'update'])->name('rencana-makan.update');
        Route::delete('delete/rencana-makan/{id}', [RencanaMakanController::class, 'delete'])->name('rencana-makan.delete');

        Route::get('rencana-penanganan-komplikasi', [RencanaPenangananKomplikasiController::class, 'index'])->name('rencana-penanganan-komplikasi.index');
        Route::post('insert/rencana-penanganan-komplikasi', [RencanaPenangananKomplikasiController::class, 'insert'])->name('rencana-penanganan-komplikasi.insert');
        Route::post('update/rencana-penanganan-komplikasi/{id}', [RencanaPenangananKomplikasiController::class, 'update'])->name('rencana-penanganan-komplikasi.update');
        Route::delete('delete/rencana-penanganan-komplikasi/{id}', [RencanaPenangananKomplikasiController::class, 'delete'])->name('rencana-penanganan-komplikasi.delete');

        Route::get('target-pengetahuan', [TargetPengetahuanController::class, 'index'])->name('target-pengetahuan.index');
        Route::post('insert/target-pengetahuan', [TargetPengetahuanController::class, 'insert'])->name('target-pengetahuan.insert');
        Route::post('update/target-pengetahuan/{id}', [TargetPengetahuanController::class, 'update'])->name('target-pengetahuan.update');
        Route::delete('delete/target-pengetahuan/{id}', [TargetPengetahuanController::class, 'delete'])->name('target-pengetahuan.delete');
    });
});

Route::group([
    'prefix' => 'dokter',
    'as' => 'dokter.'
], function () {
    Route::post('auth/login', [LoginController::class, 'dokterLogin'])->name('auth.login');
    Route::group([
        'middleware' => 'auth.role:Dokter',
    ], function () {
        Route::get('identitas', [IdentitasDokterController::class, 'index'])->name('identitas.index');
        Route::post('identitas/store', [IdentitasDokterController::class, 'store'])->name('identitas.store');
        Route::put('identitas', [IdentitasDokterController::class, 'update'])->name('identitas.update');
        Route::get('list-identitas-pasien', [App\Http\Controllers\API\Dokter\DokterController::class, 'listIdentitasPasien'])->name('listIdentitasPasien.index');
        Route::get('get-kesehatan/{id}', [App\Http\Controllers\API\Dokter\DokterController::class, 'getKesehatan'])->name('getKesehatan.index');
    });
});

Route::get('auth/me', [UserController::class, 'getAuthenticatedUser']);
