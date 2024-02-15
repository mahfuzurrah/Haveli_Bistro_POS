<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Register;

use Brian2694\Toastr\Facades\Toastr;
use Rap2hpoutre\FastExcel\FastExcel;
use App\CentralLogics\Helpers;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $registers = Register::where('admin_id', auth('admin')->user()->id)->where(function($query) use($request) {
            if ($request->shift) {
                $query->where('shift', $request->shift);
            }
            if ($request->from) {
                $endTime = $request->to ?? date('Y-m-d');
                $query->whereDate('open_time', '>=', $request->from)->whereDate('open_time', '<=', $endTime);
            }
        })->latest()->paginate(20);
        return view('admin-views.registers.index', ['registers' => $registers]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id=null)
    {
        if (!$id) {
            $register = Register::where('admin_id', auth('admin')->user()->id)->whereDate('open_time', date('Y-m-d'))->opened()->first();
        } else {
            $register = Register::find($id) ?? null;
        }

        if ($id && $register && $register->close_time) {
            Toastr::warning(translate('Register already closed'));
            return redirect(route('admin.registers.create'));   
        }
        
        return view('admin-views.registers.form', ['register' => $register]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount'       => 'required|numeric',
            'shift'      => 'required',
        ]);

        $register = Register::create([
            'admin_id' => auth('admin')->user()->id,
            'shift' => $request->shift,
            'open_amount' => $request->amount,
            'open_notes' => $request->notes,
            'open_time' => date('Y-m-d H:i:s'),
        ]); 

        if (!$register) {
            Toastr::error(translate('Something went wrong!'));
        }

        Toastr::success(translate('Register Successfully Opened!'));
        return redirect(route('admin.registers.create', [$register->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $register = Register::where('admin_id', auth('admin')->user()->id)->findOrFail($id);
        return view('admin-views.registers.show', ['register' => $register]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $register = Register::find($id) ?? null;

        if (!$register) {
            Toastr::warning(translate('Something went wrong!'));
            return back();   
        }
        
        return view('admin-views.registers.edit', ['register' => $register]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function closeRegister(Request $request, $id)
    {
        $request->validate([
            'amount'     => 'required|numeric',
        ]);

        $register = Register::where('admin_id', auth('admin')->user()->id)->findOrFail($id);

        $register->update([
            'close_amount' => $request->amount,
            'close_notes' => $request->notes,
            'close_time' => date('Y-m-d H:i:s'),
        ]); 

        if (!$register) {
            Toastr::error(translate('Something went wrong!'));
        }

        Toastr::success(translate('Register Successfully Closed!'));
        return redirect(route('admin.registers.create'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'shift'     => 'required',
            'open_amount'     => 'required|numeric',
            'close_amount'    => 'required|numeric',
        ]);

        $register = Register::where('admin_id', auth('admin')->user()->id)->findOrFail($id);

        $register->update($request->except(['_token', 'lang'])); 

        if (!$register) {
            Toastr::error(translate('Something went wrong!'));
            return back();
        }

        Toastr::success(translate('Register Successfully Updated!'));
        return redirect(route('admin.registers.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function print($id)
    {

    }

    public function exportExcel(Request $request)
    {
        $registers = Register::where('admin_id', auth('admin')->user()->id)->where(function($query) use($request) {
            if ($request->shift) {
                $query->where('shift', $request->shift);
            }
            if ($request->from) {
                $endTime = $request->to ?? date('Y-m-d');
                $query->whereDate('open_time', '>=', $request->from)->whereDate('open_time', '<=', $endTime);
            }
        })->latest()->get();

        $data = array();

        foreach ($registers as $key => $register) {
            $data[] = array(
                'SL' => ++$key,
                'Shift' => 'Shift '.$register->shift,
                'Open Time' => date('M, d Y h:m A', strtotime($register->open_time)),
                'Open amount' => Helpers::set_symbol($register->open_amount),
                'Open Note' => $register->open_notes,
                'Close Time' => date('M, d Y h:m A', strtotime($register->open_time)),
                'Close amount' => Helpers::set_symbol($register->open_amount),
                'Close Note' => $register->open_notes,
            );
        }

        return (new FastExcel($data))->download('registers.xlsx');
    }
}
