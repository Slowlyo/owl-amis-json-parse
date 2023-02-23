<?php

use Slowlyo\SlowAmisJsonParse\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('slow-amis-json-parse', [Controllers\SlowAmisJsonParseController::class, 'index']);
Route::post('slow-amis-json-parse', [Controllers\SlowAmisJsonParseController::class, 'parse']);
