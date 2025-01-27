<?php

use App\Models\Expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->middleware('guest');

route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', function (Request $request) {
        return [
            'user' => $request->user(),
            'permissions' => $request->user()->getAllPermissions()->pluck('name'),
            'roles' =>  $request->user()->getRoleNames()
        ];
    });

    Route::post('/logout', [\App\Http\Controllers\Auth\LogoutController::class, 'logout']);

    Route::get('/dashboard/data', [\App\Http\Controllers\DashboardController::class, 'index']);
    Route::get('/dashboard/data-2', [\App\Http\Controllers\DashboardController::class, 'index']);


    Route::group(['prefix' => 'toselect'], function () {
        Route::get('/basicquantities', [\App\Http\Controllers\BasicSellingQuantityController::class, 'toselect']);
        Route::get('/collectiontypes', [\App\Http\Controllers\CollectionTypeController::class, 'toselect']);
        Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'toselect']);
        Route::get('/roles', [\App\Http\Controllers\RolesController::class, 'toselect']);
        Route::get('/paymentmethods', [\App\Http\Controllers\PaymentmethodController::class, 'toselect']);
        Route::get('/expenses', [\App\Http\Controllers\ExpensedefinitionController::class, 'toselect']);
    });

    Route::group(['prefix' => 'product'], function () {
        Route::get('/all', [App\Http\Controllers\ProductController::class, 'index']);
        Route::get('/all/products/models', [App\Http\Controllers\ProductController::class, 'productAndModelsJoin']);
        Route::get('/all/withmodels', [App\Http\Controllers\ProductsmodelsController::class, 'searchProductWithModels']);
        Route::post('/new', [App\Http\Controllers\ProductController::class, 'store']);
        Route::get('/find/{product}', [App\Http\Controllers\ProductController::class, 'show']);
        Route::put('/update/{product}', [App\Http\Controllers\ProductController::class, 'update']);
        Route::get('/find/{id}/{product_name}', [App\Http\Controllers\ProductController::class, 'getProductByIdandName']);
        Route::get('/models/find/{id}', [App\Http\Controllers\ProductsmodelsController::class, 'getProductModelsFromProductId']);
        Route::get('/models/{id}/stock/data', [App\Http\Controllers\ProductstockhistoryController::class, 'show']);
        Route::get('/models/{id}/stock/history', [App\Http\Controllers\ProductstockhistoryController::class, 'stockhistory']);
        Route::post('/stock/{model_id}/increase', [App\Http\Controllers\ProductstockhistoryController::class, 'increaseStock']);
        Route::post('/stock/{model_id}/decrease', [App\Http\Controllers\ProductstockhistoryController::class, 'decreaseStock']);
        Route::get('/all/unattended', [App\Http\Controllers\ProductController::class, 'getUnattendedProductsWithCategoriesAndModels']);
    });

    Route::group(['prefix' => 'supplier'], function () {
        Route::get('/model/{model}', [App\Http\Controllers\ProductsupplierController::class, 'getSupplierPergivenProductModel']);
        Route::get('/all', [App\Http\Controllers\SupplierController::class, 'index']);
        Route::get('/to-table', [App\Http\Controllers\SupplierController::class, 'toSupplierTable']);
        Route::post('/updateorcreate', [App\Http\Controllers\SupplierController::class, 'edit']);
        Route::post('/delete/{supplier}', [App\Http\Controllers\SupplierController::class, 'destroy']);
    });

    Route::group(['prefix' => 'stock'], function () {
        Route::post('/new', [App\Http\Controllers\StockhistoryController::class, 'store']);
    });
    Route::group(['prefix' => 'category'], function () {
        Route::get('/all', [App\Http\Controllers\CategoryController::class, 'index']);
        Route::post('/updateorcreate', [App\Http\Controllers\CategoryController::class, 'edit']);
        Route::post('/delete/{category}', [App\Http\Controllers\CategoryController::class, 'destroy']);
    });
    Route::group(['prefix' => 'sale'], function () {
        Route::post('/new', [App\Http\Controllers\SaleController::class, 'store']);
        Route::get('/all', [App\Http\Controllers\SaleController::class, 'index']);
        Route::get('/to-search-deep', [App\Http\Controllers\SaleController::class, 'tosearch']);
        Route::get('/get/{sale}', [App\Http\Controllers\SaleController::class, 'show']);
        Route::get('/get/sale-from-invoice/{sale:sale_invoice}', [App\Http\Controllers\SaleController::class, 'getSaleFromInvoice']);
        Route::get('/view-invoice/{invoiceID}', [App\Http\Controllers\SaleController::class, 'showinvoice']);

    });
   
    Route::group(['prefix' => 'proforma'], function () {
        Route::post('/new', [App\Http\Controllers\ProformaInvoiceController::class, 'store']);
        Route::get('/all', [App\Http\Controllers\ProformaInvoiceController::class, 'index']);
        Route::get('/view-invoice/{proformaInvoiceID}', [App\Http\Controllers\ProformaInvoiceController::class, 'show']);
        Route::delete('/delete/{proformaInvoice}', [App\Http\Controllers\ProformaInvoiceController::class, 'destroy']);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/all', [App\Http\Controllers\Auth\UserController::class, 'index']);
        Route::post('/create', [App\Http\Controllers\Auth\UserController::class, 'store']);
        Route::post('/update/{user}', [App\Http\Controllers\Auth\UserController::class, 'edit']);
        Route::get('/get/info/{user}', [App\Http\Controllers\Auth\UserController::class, 'show']);
        Route::post('/delete/{user}', [App\Http\Controllers\Auth\UserController::class, 'destroy']);
        Route::post('/password/reset/{user}', [App\Http\Controllers\Auth\UserController::class, 'resetPassword']);
        Route::put('/update/credentials', [\App\Http\Controllers\UserprofileController::class, 'update']);
        Route::put('/update/credentials/validate', [\App\Http\Controllers\UserprofileController::class, 'validationcheck']);
    });

    Route::group(['prefix' => 'roles'], function () {
        Route::get('/all', [\App\Http\Controllers\RolesController::class, 'index']);
        Route::post('/updateorcreate', [\App\Http\Controllers\RolesController::class, 'updateOrCreate']);
        Route::get('/{rolename}/permissions', [\App\Http\Controllers\RolesController::class, 'getPermissionFromRoleName']);
        Route::post('/permissions/new', [\App\Http\Controllers\RolesController::class, 'applyNewPermissions']);
    });
    Route::group(['prefix' => 'payment'], function () {
        Route::get('/history/all', [\App\Http\Controllers\PaymenthistoryController::class, 'index']);
    });
    Route::group(['prefix' => 'refund'], function () {
        Route::post('/new', [\App\Http\Controllers\RefundsController::class, 'create']);
        Route::get('/history', [\App\Http\Controllers\RefundsController::class, 'index']);
    });
    Route::group(['prefix' => 'expense'], function () {
        Route::post('/updateorcreate', [\App\Http\Controllers\ExpensedefinitionController::class, 'store']);
        Route::get('/all', [\App\Http\Controllers\ExpensedefinitionController::class, 'index']);
        Route::delete('/delete/{expensedefinition}', [\App\Http\Controllers\ExpensedefinitionController::class, 'destroy']);
        Route::post('/submit', [\App\Http\Controllers\ExpensesController::class, 'create'])->middleware('role_or_permission:create expense|Super Admin');
        Route::get('/submits/all', [\App\Http\Controllers\ExpensesController::class, 'allExpenses']);
        Route::get('/submits/get/{expenses}', [\App\Http\Controllers\ExpensesController::class, 'show']);
        Route::post('/take-action/{expenses}',[\App\Http\Controllers\ExpensesController::class, 'takeAction'])->middleware('role_or_permission:authorize expense|Super Admin');
        Route::get('/pending-count',[\App\Http\Controllers\ExpensesController::class, 'getPendingExpenseCount']);
    });
    Route::group(['prefix' => 'report'],function(){
        Route::post('/product-sale-report',[\App\Http\Controllers\ReportController::class,'generateProductSaleReport']);
        Route::post('/income-week-report',[\App\Http\Controllers\Reports\IncomestatementweeklyController::class,'generateWeeklyIncomeStatement']);
        Route::post('/income-month-report',[\App\Http\Controllers\Reports\IncomestatementmonthlyController::class,'generatemonthlyincomestatement']);
    });

    Route::get('/unread-count/all',[\App\Http\Controllers\UnreadcountContorller::class,'index']);
    Route::get('/business-profile/get',[\App\Http\Controllers\BusinessprofileController::class,'index']);
    Route::post('/business-profile/create-update',[\App\Http\Controllers\BusinessprofileController::class,'createorupdate']);
});
