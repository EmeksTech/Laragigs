<?php

use App\Models\Listing;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;

//show all listings
Route::get('/', [ListingController::class, 'index']);
//show create form
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth');
//store listing
Route::post('/listings', [ListingController::class, 'store'])->middleware('auth');

//show edit page
Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');

//update listing
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');;

//delete listing
Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth');

//manage listings
Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');

//single listing page
Route::get('/listings/{listing}', [ListingController::class, 'show']);


//show register form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');
//create user
Route::post('/users', [UserController::class, 'store']);
//Logout user
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

//loging form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');
//login user route
Route::post('/users/authenticate', [UserController::class, 'authenticate']);
