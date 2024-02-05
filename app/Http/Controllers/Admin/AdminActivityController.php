<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Translation;
use App\Models\AdminActivity;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class AdminActivityController extends Controller
{
    function index(Request $request): Renderable
    {
        $activities = AdminActivity::latest()->paginate(Helpers::getPagination());
        return view('admin-views.admin_activity.index', compact('activities'));
    }
}
