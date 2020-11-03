<?php

use GiocoPlus\CheckboxTree\Http\Controllers\CheckboxTreeController;

Route::get('checkboxtree', CheckboxTreeController::class.'@index');