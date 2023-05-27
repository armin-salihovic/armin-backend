<?php

use App\Http\Controllers\Admin\CvController;
use Illuminate\Support\Facades\Route;

Route::module('projects');
Route::module('pages');
Route::module('cvs');

Route::get('cvs', [CvController::class, 'cv'])->name('cvs');
