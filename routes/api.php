<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\Auth\RegisterController;
use  App\Http\Controllers\Auth\LoginController;
use  App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ProfileController;
use  App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Tag\TagController;
use App\Http\Controllers\Wallet\AddCategoryController;
use App\Http\Controllers\Wallet\AddExpenseOrIncomeController;
use App\Http\Controllers\Wallet\CreateWallet;
use App\Http\Controllers\Wallet\GetCategoriesController;
use App\Http\Controllers\Wallet\CategorieHistoryController;
use App\Http\Controllers\Wallet\EditCategorieController;
use App\Http\Controllers\Wallet\DeleteCategorieController;
use App\Http\Controllers\Wallet\DeleteTransactionController;
use App\Http\Controllers\Wallet\EditTransactionController;
use App\Http\Controllers\Wallet\DeleteWalletController;
use App\Http\Controllers\Auth\DeleteAccountController;
use App\Http\Controllers\Admin\GetTagsController;
use App\Http\Controllers\Admin\GetUsersController;
use App\Http\Controllers\Admin\EditTagController;
use App\Http\Controllers\Admin\DeleteTagController;
use App\Http\Controllers\Admin\AddTagController;
use App\Http\Controllers\Tag\AddTagToUserController;
use App\Http\Requests\Tag\AddTagToUserRequest;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('tag', [TagController::class, 'checkAvailability']);

Route::post('register',[RegisterController::class,'register']);
Route::post('login',[LoginController::class,'login']);
Route::post('password/forget-password',[ForgetPasswordController::class,'forgetPassword']);

Route::post('password/reset',[ResetPasswordController::class,'passwordReset']);
Route::post('deleteAccount',[DeleteAccountController::class,'deleteAccount']);
Route::middleware(['auth:sanctum'])->group( function () {
    Route::get('/profile', function (Request $request) {
         
        $user = $request->user();
        return  $user;

});
Route::put('profile',[ProfileController::class,'update']);
    Route::post('email-verification',[EmailVerificationController::class,'email_verification']);
    Route::get('email-verification',[EmailVerificationController::class,'sendemailverification']);
    Route::post('create-wallet',[CreateWallet::class,'createWallet']);
    Route::post('logout',[LogoutController::class,'signout']);
    Route::post('createCategory',[AddCategoryController::class,'addCategory']);
    Route::post('addExpenseOrIncome',[AddExpenseOrIncomeController::class,'addExpenseOrIncome']);
    Route::post('getCategories',[GetCategoriesController::class,'getCategories']);
    Route::post('categorieHistory',[CategorieHistoryController::class,'categorieHistory']);
    Route::post('editCategorie',[EditCategorieController::class,'editCategorie']);
    Route::post('deleteCategorie',[DeleteCategorieController::class,'deleteCategorie']);
    Route::post('deleteTransaction',[DeleteTransactionController::class,'deleteTransaction']);
    Route::post('editTransaction',[EditTransactionController::class,'modifyTransaction']);
    Route::post('deleteWallet',[DeleteWalletController::class,'deleteWallet']);
    Route::post('addTagToUser',[AddTagToUserController::class,'addTagToUser']);

});

Route::get('getTags',[GetTagsController::class,'getTags']);
Route::get('getUsers',[GetUsersController::class,'getUsers']);
Route::post('editTag',[EditTagController::class,'editTag']);
Route::post('deleteTag',[DeleteTagController::class,'deleteTag']);
Route::post('addTag',[AddTagController::class,'addTag']);

