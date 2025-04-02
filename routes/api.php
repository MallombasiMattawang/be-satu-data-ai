<?php

use Illuminate\Support\Facades\Route;


//route login
Route::post('/login', [App\Http\Controllers\Api\Auth\LoginController::class, 'index']);

//group route with middleware "auth"
Route::group(['middleware' => 'auth:api'], function () {

    //logout
    Route::post('/logout', [App\Http\Controllers\Api\Auth\LoginController::class, 'logout']);
});

//group route with prefix "admin"
Route::prefix('admin')->group(function () {
    //group route with middleware "auth:api"
    Route::group(['middleware' => 'auth:api'], function () {

        //dashboard
        Route::get('/dashboard', App\Http\Controllers\Api\Admin\DashboardController::class);


        //permissions
        Route::get('/permissions', [\App\Http\Controllers\Api\Admin\PermissionController::class, 'index'])
            ->middleware('permission:permissions.index');

        //permissions all
        Route::get('/permissions/all', [\App\Http\Controllers\Api\Admin\PermissionController::class, 'all'])
            ->middleware('permission:permissions.index');

        //roles all
        Route::get('/roles/all', [\App\Http\Controllers\Api\Admin\RoleController::class, 'all'])
            ->middleware('permission:roles.index');

        //roles
        Route::apiResource('/roles', App\Http\Controllers\Api\Admin\RoleController::class)
            ->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');

        //users
        Route::apiResource('/users', App\Http\Controllers\Api\Admin\UserController::class)
            ->middleware('permission:users.index|users.create|users.edit|users.delete');

        //summaryAll RBM
        Route::get('/dashboard-rbm', [\App\Http\Controllers\Api\Admin\RbmController::class, 'summaryAll']);


        //Maps RBM
        Route::get('/maps-rbm', [\App\Http\Controllers\Api\Admin\RbmController::class, 'maps']);

        //filter Maps RBM
        Route::get('/filter-rbm', [\App\Http\Controllers\Api\Admin\RbmController::class, 'paramFilter']);

        //Patroli RBM
        Route::get('/patroli-rbm', [\App\Http\Controllers\Api\Admin\RbmController::class, 'index']);

        //Patroli RBM Station Show
        Route::get('/patroli-rbm-station/{station}', [\App\Http\Controllers\Api\Admin\RbmController::class, 'summaryByStation']);

        //Patroli RBM Show
        Route::get('/patroli-rbm/{patroli_id}/{year}', [\App\Http\Controllers\Api\Admin\RbmController::class, 'showByPatroliId']);

        //Patroli RBM summary
        Route::get('/summary-rbm/{patroli_id}/{year}', [\App\Http\Controllers\Api\Admin\RbmController::class, 'summaryByPatroliId']);

        Route::post('/import-db-rbm', [App\Http\Controllers\Api\Admin\ImportDbRbmController::class, 'importExcel']);

        Route::post('/import-db-wru', [App\Http\Controllers\Api\Admin\ImportDbWruController::class, 'importExcel']);

        //summaryAll WRU
        Route::get('/dashboard-wru', [\App\Http\Controllers\Api\Admin\WruController::class, 'summaryAll']);

        //Maps WRU
        Route::get('/maps-wru', [\App\Http\Controllers\Api\Admin\WruController::class, 'maps']);

        //filter Maps RBM
        Route::get('/filter-wru', [\App\Http\Controllers\Api\Admin\WruController::class, 'paramFilter']);

        ###################################################################################################

        // Route untuk index dan store (tanpa parameter ID)
        Route::get('/asets', [App\Http\Controllers\Api\Admin\AsetController::class, 'index']);
            // ->middleware('permission:asets.index');
        Route::get('/asets-expired', [App\Http\Controllers\Api\Admin\AsetController::class, 'expired']);
            // ->middleware('permission:asets.index');
        Route::get('/asets-filter/{kategori?}/{kondisi?}/{status?}/{lokasi?}', [App\Http\Controllers\Api\Admin\AsetController::class, 'filter']);
        Route::post('/asets', [App\Http\Controllers\Api\Admin\AsetController::class, 'store']);
        // ->middleware('permission:asets.store');

        // Route untuk show, update, dan destroy (dengan parameter ID)
        Route::get('/asets/{id}', [App\Http\Controllers\Api\Admin\AsetController::class, 'show']);
        // ->middleware('permission:asets.show');
        Route::put('/asets/{id}', [App\Http\Controllers\Api\Admin\AsetController::class, 'update']);
        // ->middleware('permission:asets.update');
        Route::delete('/asets/{id}', [App\Http\Controllers\Api\Admin\AsetController::class, 'destroy']);
        // ->middleware('permission:asets.delete');

        // Route kategori aset untuk index dan store (tanpa parameter ID)
        Route::get('/kategori-asets', [App\Http\Controllers\Api\Admin\KategoriAsetController::class, 'index']);
        // ->middleware('permission:asets.index');
        Route::post('/kategori-asets', [App\Http\Controllers\Api\Admin\KategoriAsetController::class, 'store']);
        // ->middleware('permission:asets.store');
        // Route kategori aset selectbox
        Route::get('/kategori-asets/all', [App\Http\Controllers\Api\Admin\KategoriAsetController::class, 'all']);

        // Route untuk show, update, dan destroy (dengan parameter ID)
        Route::get('/kategori-asets/{id}', [App\Http\Controllers\Api\Admin\KategoriAsetController::class, 'show']);
        // ->middleware('permission:asets.show');
        Route::put('/kategori-asets/{id}', [App\Http\Controllers\Api\Admin\KategoriAsetController::class, 'update']);
        // ->middleware('permission:asets.update');
        Route::delete('/kategori-asets/{id}', [App\Http\Controllers\Api\Admin\KategoriAsetController::class, 'destroy']);
        // ->middleware('permission:asets.delete');    

        // Route kondisi aset untuk index dan store (tanpa parameter ID)
        Route::get('/kondisi-asets', [App\Http\Controllers\Api\Admin\KondisiAsetController::class, 'index']);
        Route::get('/kondisi-asets/all', [App\Http\Controllers\Api\Admin\KondisiAsetController::class, 'all']);
        // ->middleware('permission:asets.index');
        Route::post('/kondisi-asets', [App\Http\Controllers\Api\Admin\KondisiAsetController::class, 'store']);
        // ->middleware('permission:asets.store');

        // Route untuk show, update, dan destroy (dengan parameter ID)
        Route::get('/kondisi-asets/{id}', [App\Http\Controllers\Api\Admin\KondisiAsetController::class, 'show']);
        // ->middleware('permission:asets.show');
        Route::put('/kondisi-asets/{id}', [App\Http\Controllers\Api\Admin\KondisiAsetController::class, 'update']);
        // ->middleware('permission:asets.update');
        Route::delete('/kondisi-asets/{id}', [App\Http\Controllers\Api\Admin\KondisiAsetController::class, 'destroy']);
        // ->middleware('permission:asets.delete');        

        // Route status aset untuk index dan store (tanpa parameter ID)
        Route::get('/status-asets', [App\Http\Controllers\Api\Admin\StatusAsetController::class, 'index']);
        Route::get('/status-asets/all', [App\Http\Controllers\Api\Admin\StatusAsetController::class, 'all']);
        // ->middleware('permission:asets.index');
        Route::post('/status-asets', [App\Http\Controllers\Api\Admin\StatusAsetController::class, 'store']);
        // ->middleware('permission:asets.store');

        // Route untuk show, update, dan destroy (dengan parameter ID)
        Route::get('/status-asets/{id}', [App\Http\Controllers\Api\Admin\StatusAsetController::class, 'show']);
        // ->middleware('permission:asets.show');
        Route::put('/status-asets/{id}', [App\Http\Controllers\Api\Admin\StatusAsetController::class, 'update']);
        // ->middleware('permission:asets.update');
        Route::delete('/status-asets/{id}', [App\Http\Controllers\Api\Admin\StatusAsetController::class, 'destroy']);
        // ->middleware('permission:asets.delete');  

        // Route lokasi aset untuk index dan store (tanpa parameter ID)
        Route::get('/lokasi-asets', [App\Http\Controllers\Api\Admin\LokasiAsetController::class, 'index']);
        Route::get('/lokasi-asets/all', [App\Http\Controllers\Api\Admin\LokasiAsetController::class, 'all']);
        // ->middleware('permission:asets.index');
        Route::post('/lokasi-asets', [App\Http\Controllers\Api\Admin\LokasiAsetController::class, 'store']);
        // ->middleware('permission:asets.store');

        // Route untuk show, update, dan destroy (dengan parameter ID)
        Route::get('/lokasi-asets/{id}', [App\Http\Controllers\Api\Admin\LokasiAsetController::class, 'show']);
        // ->middleware('permission:asets.show');
        Route::put('/lokasi-asets/{id}', [App\Http\Controllers\Api\Admin\LokasiAsetController::class, 'update']);
        // ->middleware('permission:asets.update');
        Route::delete('/lokasi-asets/{id}', [App\Http\Controllers\Api\Admin\LokasiAsetController::class, 'destroy']);
        // ->middleware('permission:asets.delete');   

        // Route masa aset untuk index dan store (tanpa parameter ID)
        Route::get('/masa-asets', [App\Http\Controllers\Api\Admin\MasaAsetController::class, 'index']);
        Route::get('/masa-asets/all', [App\Http\Controllers\Api\Admin\MasaAsetController::class, 'all']);
        // ->middleware('permission:asets.index');
        Route::post('/masa-asets', [App\Http\Controllers\Api\Admin\MasaAsetController::class, 'store']);
        // ->middleware('permission:asets.store');

        // Route untuk show, update, dan destroy (dengan parameter ID)
        Route::get('/masa-asets/{id}', [App\Http\Controllers\Api\Admin\MasaAsetController::class, 'show']);
        // ->middleware('permission:asets.show');
        Route::put('/masa-asets/{id}', [App\Http\Controllers\Api\Admin\MasaAsetController::class, 'update']);
        // ->middleware('permission:asets.update');
        Route::delete('/masa-asets/{id}', [App\Http\Controllers\Api\Admin\MasaAsetController::class, 'destroy']);
        // ->middleware('permission:asets.delete');  

        // Route inspeksi aset untuk index dan store (tanpa parameter ID)
        Route::get('/inspeksi-asets', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'index']);
        Route::get('/inspeksi-asets/all', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'all']);
        Route::get('/inspeksi-asets-view/{tanggal_inspeksi}', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'view']);
        Route::get('/inspeksi-asets-pdf/{tanggal_inspeksi}', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'generatePdf']);
        // ->middleware('permission:asets.index');
        Route::post('/inspeksi-asets', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'store']);
        // ->middleware('permission:asets.store');

        // Route untuk show, update, dan destroy (dengan parameter ID)
        Route::get('/inspeksi-asets/{id}', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'show']);
        // ->middleware('permission:asets.show');
        Route::put('/inspeksi-asets/{id}', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'update']);
        // ->middleware('permission:asets.update');
        Route::delete('/inspeksi-asets/{id}', [App\Http\Controllers\Api\Admin\InspeksiAsetController::class, 'destroy']);
        // ->middleware('permission:asets.delete');
    });
});
