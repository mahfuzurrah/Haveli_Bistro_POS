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

    public function report()
    {
        return view('admin-views.report.time_report');
    }

    public function reportBackup(Request $request)
    {
        // return $request->all();
        $checkins = AdminActivity::with('admin')->where(function($query) use($request){
            if ($request->has('admin_id') && $request->has('from') && $request->has('to')) {
                $start_date = $request->from;
                $end_date = $request->to;
                $query->where('admin_id', $request->admin_id)
                ->whereDate('start_date' ,'>=' , $start_date)
                ->whereDate('end_date','<=' , $end_date);
            }
        })->latest()->paginate(Helpers::getPagination());
        $time_arr = [];
        foreach($checkins as $checkin){
            $time_arr[] = $checkin->work_time;
        }
         $time_arr;
         $time = strtotime('00:00:00');
         $total_time = 0;
         foreach( $time_arr as $ele )
         {
            $sec_time = strtotime($ele) - $time;
            $total_time = $total_time + $sec_time;
         }
         $hours = intval($total_time / 3600);
         $total_time = $total_time - ($hours * 3600);
         $min = intval($total_time / 60);
         $sec = $total_time - ($min * 60);
         $total_hours =  $hours.":".$min.":".$sec;
        return view('admin-views.report.time_report', compact('checkins','total_hours'));
    }

    public function reportFilter(Request $request)
    {
        $fromDate = Carbon::parse($request->from)->startOfDay();
        $toDate = Carbon::parse($request->to)->endOfDay();

        $checkins = AdminActivity::with('admin')->where(function($query) use($request){
            if ($request->has('admin_id') && $request->has('from') && $request->has('to')) {
                $start_date = $request->from;
                $end_date = $request->to;
                $query->where('admin_id', $request->admin_id)
                ->whereDate('start_date' ,'>=' , $start_date)
                ->whereDate('end_date','<=' , $end_date);
            }
        })->latest()->get();
        $time_arr = [];
        foreach($checkins as $checkin){
            $time_arr[] = $checkin->work_time;
        }
         $time_arr;
         $time = strtotime('00:00:00');
         $total_time = 0;
         foreach( $time_arr as $ele )
         {
            $sec_time = strtotime($ele) - $time;
            $total_time = $total_time + $sec_time;
         }
         $hours = intval($total_time / 3600);
         $total_time = $total_time - ($hours * 3600);
         $min = intval($total_time / 60);
         $sec = $total_time - ($min * 60);
         $total_hours =  $hours.":".$min.":".$sec;
         $total_checkin = count($checkins);
        session()->put('export_data', $checkins);

        return response()->json([
            'total_hours' => $total_hours,
            'total_checkin' => $total_checkin,
            'view' => view('admin-views.report.partials._table-clock', ['checkins' => $checkins])->render(),
        ]);
    }
}
