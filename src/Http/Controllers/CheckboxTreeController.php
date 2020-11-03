<?php

namespace GiocoPlus\CheckboxTree\Http\Controllers;

use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class CheckboxTreeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('Title')
            ->description('Description')
            ->body(view('checkboxtree::index'));
    }
}
