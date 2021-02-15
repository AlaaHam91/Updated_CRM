<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Users
Route::middleware('auth:api')->post('/logout',[\App\Http\Controllers\Api\AuthController::class,'logout']);
Route::post('register',[\App\Http\Controllers\Api\AuthController::class,'register']);
Route::post('login',[\App\Http\Controllers\Api\AuthController::class,'login']);
Route::get('users',[\App\Http\Controllers\Api\AuthController::class,'index']);

//Cities Routes..
Route::get('cities',[\App\Http\Controllers\Api\CityController::class,'index']);
Route::get('city/{id}',[\App\Http\Controllers\Api\CityController::class,'show']);
Route::post('city',[\App\Http\Controllers\Api\CityController::class,'store']);
Route::put('city/{id}',[\App\Http\Controllers\Api\CityController::class,'update']);
Route::delete('city/{id}',[\App\Http\Controllers\Api\CityController::class,'destroy']);

//Countries Routes
Route::get('countries',[\App\Http\Controllers\Api\CountryController::class,'index']);
Route::get('country/{id}',[\App\Http\Controllers\Api\CountryController::class,'show']);
Route::post('country',[\App\Http\Controllers\Api\CountryController::class,'store']);
Route::put('country/{id}',[\App\Http\Controllers\Api\CountryController::class,'update']);
Route::delete('country/{id}',[\App\Http\Controllers\Api\CountryController::class,'destroy']);
Route::get('country/navigate/{id}',[\App\Http\Controllers\Api\CountryController::class,'navigate']);

//Job Types Routes
Route::get('jobTypes',[\App\Http\Controllers\Api\JobTypeController::class,'index']);
Route::get('jobType/{id}',[\App\Http\Controllers\Api\JobTypeController::class,'show']);
Route::post('jobType',[\App\Http\Controllers\Api\JobTypeController::class,'store']);
Route::put('jobType/{id}',[\App\Http\Controllers\Api\JobTypeController::class,'update']);
Route::delete('jobType/{JobType}',[\App\Http\Controllers\Api\JobTypeController::class,'destroy']);

//Jobs Routes
Route::get('jobs',[\App\Http\Controllers\Api\JobController::class,'index']);
Route::get('job/{id}',[\App\Http\Controllers\Api\JobController::class,'show']);
Route::post('job',[\App\Http\Controllers\Api\JobController::class,'store']);
Route::put('job/{id}',[\App\Http\Controllers\Api\JobController::class,'update']);
Route::delete('job/{id}',[\App\Http\Controllers\Api\JobController::class,'destroy']);
//Finish Type
Route::get('finishTypes',[\App\Http\Controllers\Api\FinishTypeController::class,'index']);
Route::get('finishType/{id}',[\App\Http\Controllers\Api\FinishTypeController::class,'show']);
Route::post('finishType',[\App\Http\Controllers\Api\FinishTypeController::class,'store']);
Route::put('finishType/{id}',[\App\Http\Controllers\Api\FinishTypeController::class,'update']);
Route::delete('finishType/{id}',[\App\Http\Controllers\Api\FinishTypeController::class,'destroy']);
//Companies Routes
Route::get('company',[\App\Http\Controllers\Api\CompaniesController::class,'index']);
Route::get('company/{id}',[\App\Http\Controllers\Api\CompaniesController::class,'show']);
Route::post('company',[\App\Http\Controllers\Api\CompaniesController::class,'store']);
Route::put('company/{id}',[\App\Http\Controllers\Api\CompaniesController::class,'update']);
Route::delete('company/{id}',[\App\Http\Controllers\Api\CompaniesController::class,'destroy']);

//Acquaintances Routes
Route::get('acquaintances',[\App\Http\Controllers\Api\AcquaintanceController::class,'index']);
Route::get('acquaintance/{id}',[\App\Http\Controllers\Api\AcquaintanceController::class,'show']);
Route::post('acquaintance',[\App\Http\Controllers\Api\AcquaintanceController::class,'store']);
Route::put('acquaintance/{id}',[\App\Http\Controllers\Api\AcquaintanceController::class,'update']);
Route::delete('acquaintance/{id}',[\App\Http\Controllers\Api\AcquaintanceController::class,'destroy']);

//Company People Routes
Route::get('cpeople',[\App\Http\Controllers\Api\CompanyPeopleController::class,'index']);
Route::get('cpeople/{id}',[\App\Http\Controllers\Api\CompanyPeopleController::class,'show']);
Route::post('cpeople',[\App\Http\Controllers\Api\CompanyPeopleController::class,'store']);
Route::put('cpeople/{id}',[\App\Http\Controllers\Api\CompanyPeopleController::class,'update']);
Route::delete('cpeople/{id}',[\App\Http\Controllers\Api\CompanyPeopleController::class,'destroy']);


//Activities Routes
Route::get('activities',[\App\Http\Controllers\Api\ActivitiesController::class,'index']);
Route::get('activity/{id}',[\App\Http\Controllers\Api\ActivitiesController::class,'show']);
Route::post('activity',[\App\Http\Controllers\Api\ActivitiesController::class,'store']);
Route::put('activity/{id}',[\App\Http\Controllers\Api\ActivitiesController::class,'update']);
Route::delete('activity/{id}',[\App\Http\Controllers\Api\ActivitiesController::class,'destroy']);
Route::get('activity/navigate/{id}',[\App\Http\Controllers\Api\ActivitiesController::class,'navigate']);

//Interests Routes
Route::get('interests',[\App\Http\Controllers\Api\InterestsController::class,'index']);
Route::get('interest/{id}',[\App\Http\Controllers\Api\InterestsController::class,'show']);
Route::post('interest',[\App\Http\Controllers\Api\InterestsController::class,'store']);
Route::put('interest/{id}',[\App\Http\Controllers\Api\InterestsController::class,'update']);
Route::delete('interest/{id}',[\App\Http\Controllers\Api\InterestsController::class,'destroy']);
Route::get('interest/navigate/{id}',[\App\Http\Controllers\Api\InterestsController::class,'navigate']);

//Languages Routes
Route::get('languages',[\App\Http\Controllers\Api\LanguagesController::class,'index']);
Route::get('language/{id}',[\App\Http\Controllers\Api\LanguagesController::class,'show']);
Route::post('language',[\App\Http\Controllers\Api\LanguagesController::class,'store']);
Route::put('language/{id}',[\App\Http\Controllers\Api\LanguagesController::class,'update']);
Route::delete('language/{id}',[\App\Http\Controllers\Api\LanguagesController::class,'destroy']);
Route::get('languages/navigate/{id}',[\App\Http\Controllers\Api\LanguagesController::class,'navigate']);

//Contact Sources Routes
Route::get('contactSource',[\App\Http\Controllers\Api\ContactSourcesController::class,'index']);
Route::get('contactSource/{id}',[\App\Http\Controllers\Api\ContactSourcesController::class,'show']);
Route::post('contactSource',[\App\Http\Controllers\Api\ContactSourcesController::class,'store']);
Route::put('contactSource/{id}',[\App\Http\Controllers\Api\ContactSourcesController::class,'update']);
Route::delete('contactSource/{id}',[\App\Http\Controllers\Api\ContactSourcesController::class,'destroy']);
Route::get('contactSource/navigate/{id}',[\App\Http\Controllers\Api\ContactSourcesController::class,'navigate']);

//Acquaintance Methods Routes
Route::get('acquaintanceMethods',[\App\Http\Controllers\Api\AcquaintanceMethodsController::class,'index']);
Route::get('acquaintanceMethod/{id}',[\App\Http\Controllers\Api\AcquaintanceMethodsController::class,'show']);
Route::post('acquaintanceMethod',[\App\Http\Controllers\Api\AcquaintanceMethodsController::class,'store']);
Route::put('acquaintanceMethod/{id}',[\App\Http\Controllers\Api\AcquaintanceMethodsController::class,'update']);
Route::delete('acquaintanceMethod/{id}',[\App\Http\Controllers\Api\AcquaintanceMethodsController::class,'destroy']);
Route::get('acquaintanceMethod/navigate/{id}',[\App\Http\Controllers\Api\AcquaintanceMethodsController::class,'navigate']);

//Acquaintance Routes
Route::get('acquaintances',[\App\Http\Controllers\Api\AcquaintanceController::class,'index']);
Route::get('acquaintances/{id}',[\App\Http\Controllers\Api\AcquaintanceController::class,'show']);
Route::post('acquaintances',[\App\Http\Controllers\Api\AcquaintanceController::class,'store']);
Route::put('acquaintances/{id}',[\App\Http\Controllers\Api\AcquaintanceController::class,'update']);
Route::delete('acquaintances/{id}',[\App\Http\Controllers\Api\AcquaintanceController::class,'destroy']);
Route::get('acquaintances/{id}/navigate',[\App\Http\Controllers\Api\AcquaintanceController::class,'navigate']);

//Document Types
Route::get('documentTypes',[\App\Http\Controllers\Api\DocumentTypesController::class,'index']);
Route::get('documentType/{id}',[\App\Http\Controllers\Api\DocumentTypesController::class,'show']);
Route::post('documentType',[\App\Http\Controllers\Api\DocumentTypesController::class,'store']);
Route::put('documentType/{id}',[\App\Http\Controllers\Api\DocumentTypesController::class,'update']);
Route::delete('documentType/{id}',[\App\Http\Controllers\Api\DocumentTypesController::class,'destroy']);
Route::get('documentType/{id}/navigate',[\App\Http\Controllers\Api\DocumentTypesController::class,'navigate']);

//Social Networks Routes
Route::get('socialNetworks',[\App\Http\Controllers\Api\SocialNetworksController::class,'index']);
Route::get('socialNetwork/{id}',[\App\Http\Controllers\Api\SocialNetworksController::class,'show']);
Route::post('socialNetwork',[\App\Http\Controllers\Api\SocialNetworksController::class,'store']);
Route::put('socialNetwork/{id}',[\App\Http\Controllers\Api\SocialNetworksController::class,'update']);
Route::delete('socialNetwork/{id}',[\App\Http\Controllers\Api\SocialNetworksController::class,'destroy']);
Route::get('socialNetwork/{id}/navigate',[\App\Http\Controllers\Api\SocialNetworksController::class,'navigate']);

//Currency Routes
Route::get('currency',[\App\Http\Controllers\Api\CurrencyController::class,'index']);
Route::get('currency/{id}',[\App\Http\Controllers\Api\CurrencyController::class,'show']);
Route::post('currency',[\App\Http\Controllers\Api\CurrencyController::class,'store']);
Route::put('currency/{id}',[\App\Http\Controllers\Api\CurrencyController::class,'update']);
Route::delete('currency/{id}',[\App\Http\Controllers\Api\CurrencyController::class,'destroy']);
Route::get('currency/navigate/{id}',[\App\Http\Controllers\Api\CurrencyController::class,'navigate']);

//Administrations
Route::get('administrations',[\App\Http\Controllers\Api\AdministrationController::class,'index']);
Route::get('administration/{id}',[\App\Http\Controllers\Api\AdministrationController::class,'show']);
Route::post('administration',[\App\Http\Controllers\Api\AdministrationController::class,'store']);
Route::put('administration/{id}',[\App\Http\Controllers\Api\AdministrationController::class,'update']);
Route::delete('administration/{id}',[\App\Http\Controllers\Api\AdministrationController::class,'destroy']);
Route::get('administration/navigate/{id}',[\App\Http\Controllers\Api\AdministrationController::class,'navigate']);
//branches
Route::get('branches',[\App\Http\Controllers\Api\BranchController::class,'index']);
Route::get('branch/{id}',[\App\Http\Controllers\Api\BranchController::class,'show']);
Route::post('branch',[\App\Http\Controllers\Api\BranchController::class,'store']);
Route::put('branch/{id}',[\App\Http\Controllers\Api\BranchController::class,'update']);
Route::delete('branch/{id}',[\App\Http\Controllers\Api\BranchController::class,'destroy']);
Route::get('branch/navigate/{id}',[\App\Http\Controllers\Api\BranchController::class,'navigate']);

//branches
Route::get('hierarchies',[\App\Http\Controllers\Api\HierarchyController::class,'index']);
Route::get('hierarchy/{id}',[\App\Http\Controllers\Api\HierarchyController::class,'show']);
Route::post('hierarchy',[\App\Http\Controllers\Api\HierarchyController::class,'store']);
Route::put('hierarchy/{id}',[\App\Http\Controllers\Api\HierarchyController::class,'update']);
Route::delete('hierarchy/{id}',[\App\Http\Controllers\Api\HierarchyController::class,'destroy']);
Route::get('hierarchy/navigate/{id}',[\App\Http\Controllers\Api\HierarchyController::class,'navigate']);
Route::get('hierarchyTree/tree',[\App\Http\Controllers\Api\HierarchyController::class,'getTree']);

//departments
Route::get('departments',[\App\Http\Controllers\Api\DepartmentController::class,'index']);
Route::get('department/{id}',[\App\Http\Controllers\Api\DepartmentController::class,'show']);
Route::post('department',[\App\Http\Controllers\Api\DepartmentController::class,'store']);
Route::put('department/{id}',[\App\Http\Controllers\Api\DepartmentController::class,'update']);
Route::delete('department/{id}',[\App\Http\Controllers\Api\DepartmentController::class,'destroy']);
Route::get('department/navigate/{id}',[\App\Http\Controllers\Api\DepartmentController::class,'navigate']);

//Education
Route::get('education',[\App\Http\Controllers\Api\EducationController::class,'index']);
Route::get('education/{id}',[\App\Http\Controllers\Api\EducationController::class,'show']);
Route::post('education',[\App\Http\Controllers\Api\EducationController::class,'store']);
Route::put('education/{id}',[\App\Http\Controllers\Api\EducationController::class,'update']);
Route::delete('education/{id}',[\App\Http\Controllers\Api\EducationController::class,'destroy']);
Route::get('education/navigate/{id}',[\App\Http\Controllers\Api\EducationController::class,'navigate']);

//projects
Route::get('projects',[\App\Http\Controllers\Api\ProjectController::class,'index']);
Route::get('project/{id}',[\App\Http\Controllers\Api\ProjectController::class,'show']);
Route::post('project',[\App\Http\Controllers\Api\ProjectController::class,'store']);
Route::put('project/{id}',[\App\Http\Controllers\Api\ProjectController::class,'update']);
Route::delete('project/{id}',[\App\Http\Controllers\Api\ProjectController::class,'destroy']);
Route::get('project/navigate/{id}',[\App\Http\Controllers\Api\ProjectController::class,'navigate']);

//additionalfields
Route::post('additionalField',[\App\Http\Controllers\Api\AdditionalFieldController::class,'store']);
Route::get('additionalfields',[\App\Http\Controllers\Api\AdditionalFieldController::class,'index']);
Route::get('additionalfield/{id}',[\App\Http\Controllers\Api\AdditionalFieldController::class,'show']);
Route::delete('additionalfield/{id}',[\App\Http\Controllers\Api\AdditionalFieldController::class,'destroy']);

//communication method
Route::post('communicationMethod',[\App\Http\Controllers\Api\CommunicationMethodController::class,'store']);
Route::get('communicationMethod/{id}',[\App\Http\Controllers\Api\CommunicationMethodController::class,'show']);
Route::get('communicationMethod',[\App\Http\Controllers\Api\CommunicationMethodController::class,'index']);
Route::delete('communicationMethod/{id}',[\App\Http\Controllers\Api\CommunicationMethodController::class,'destroy']);

//admin divisions types
Route::post('adminDivisionType',[\App\Http\Controllers\Api\AdministrativeDivisionTypeController::class,'store']);
Route::put('adminDivisionType/{id}',[\App\Http\Controllers\Api\AdministrativeDivisionTypeController::class,'update']);
Route::delete('adminDivisionType/{id}',[\App\Http\Controllers\Api\AdministrativeDivisionTypeController::class,'destroy']);
Route::get('adminDivisionType',[\App\Http\Controllers\Api\AdministrativeDivisionTypeController::class,'index']);
Route::get('adminDivisionType/{id}',[\App\Http\Controllers\Api\AdministrativeDivisionTypeController::class,'show']);
Route::get('adminDivisionType/navigate/{id}',[\App\Http\Controllers\Api\AdministrativeDivisionTypeController::class,'navigate']);

//admin divisions
Route::post('adminDivision',[\App\Http\Controllers\Api\AdministrativeDivisionController::class,'store']);
Route::put('adminDivision/{id}',[\App\Http\Controllers\Api\AdministrativeDivisionController::class,'update']);
Route::delete('adminDivision/{id}',[\App\Http\Controllers\Api\AdministrativeDivisionController::class,'destroy']);
Route::get('adminDivision',[\App\Http\Controllers\Api\AdministrativeDivisionController::class,'index']);
Route::get('adminDivision/{id}',[\App\Http\Controllers\Api\AdministrativeDivisionController::class,'show']);
Route::get('adminDivision/navigate/{id}',[\App\Http\Controllers\Api\AdministrativeDivisionController::class,'navigate']);
//products
Route::get('products',[\App\Http\Controllers\Api\ProductController::class,'index']);
Route::post('product',[\App\Http\Controllers\Api\ProductController::class,'store']);
Route::get('product/{id}',[\App\Http\Controllers\Api\ProductController::class,'show']);
Route::put('product/{id}',[\App\Http\Controllers\Api\ProductController::class,'update']);
Route::delete('product/{id}',[\App\Http\Controllers\Api\ProductController::class,'delete']);








