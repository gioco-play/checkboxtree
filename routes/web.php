<?php

use Encore\CheckboxTree\Http\Controllers\CheckboxTreeController;

Route::get('checkboxtree', CheckboxTreeController::class.'@index');