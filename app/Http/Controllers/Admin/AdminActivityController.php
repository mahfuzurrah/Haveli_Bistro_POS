<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Translation;
use App\Models\AdminActivity;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminActivityController extends Controller
{
    function index(Request $request)
    {
        $date = date('Y-m-d');
        $admin_id = auth('admin')->user()->id;
        $checkin = AdminActivity::where('admin_id',$admin_id)->whereDate('start_date', $date)->first();

        $checkin = AdminActivity::where('admin_id',$admin_id)->where(function($query) use ($date){
            $query->where('start_date', $date)
            ->orWhere('start_date', date('Y-m-d',strtotime("-1 days")))
            ->where('end_date', null);
        })->first();

        return view('admin-views.admin_activity.index', compact('checkin'));
    }

    public function checkin()
    {
        $data['start_time'] = date("H:i:s");
        $data['admin_id'] = auth('admin')->user()->id;
        $data['start_date'] = date('Y-m-d');
        $checkin = AdminActivity::where('admin_id',$data['admin_id'])->whereDate('start_date', $data['start_date'])->first();
        if(!$checkin){
            $checkin = AdminActivity::create($data);
            Toastr::success(translate('Clock-in Successful!'));
            return back();
        }
        Toastr::error(translate('Timer is already started!'));
        return back();
    }

    public function checkout()
    {
        $data['end_time'] = date("H:i:s");
        $data['end_date'] = date('Y-m-d');
        $data['admin_id'] = auth('admin')->user()->id;
        $checkin = AdminActivity::where('admin_id',$data['admin_id'])->where('end_date', null)->first();
        if($checkin){
            $startTime = $checkin->start_date."".$checkin->start_time;
            $time1 = Carbon::parse($startTime);
            $time2 = Carbon::parse($data['end_date']." ".$data['end_time']);
            $interval = $time1->diff($time2);
            $totalIterval = $time1->diffInHours($time2);
            $data['work_time'] = $interval->format('%h:%i:%s');
            $checkin->update($data);
            Toastr::success(translate('Clock-out Successful!'));
            return back();
        }
        Toastr::error(translate('Something went wrong!'));
        return back();
    }

    public function list(Request $request)
    {
        $admin_id = auth('admin')->user()->id;
        $checkins = AdminActivity::where('admin_id',$admin_id)->latest()->paginate(Helpers::getPagination());
        return view('admin-views.admin_activity.list', compact('checkins'));
    }
}
