<?php

use Illuminate\Support\Facades\Route;

Route::get('/ui/{any?}', fn () => view('app'))->where('any', '.*');
