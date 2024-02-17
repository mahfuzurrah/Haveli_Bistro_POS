<?php

namespace App\Http\Controllers\Admin\Auth;

use App\CentralLogics\helpers;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Models\AdminActivity;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use App\Models\Register;

use Brian2694\Toastr\Facades\Toastr;

class LoginController extends Controller
{
    private Admin $admin;

    public function __construct(Admin $admin)
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);

        $this->admin = $admin;
    }

    /**
     * @param $tmp
     * @return void
     */
    public function captcha($tmp)
    {

        $phrase = new PhraseBuilder;
        $code = $phrase->build(4);
        $builder = new CaptchaBuilder($code, $phrase);
        $builder->setBackgroundColor(220, 210, 230);
        $builder->setMaxAngle(25);
        $builder->setMaxBehindLines(0);
        $builder->setMaxFrontLines(0);
        $builder->build($width = 100, $height = 40, $font = null);
        $phrase = $builder->getPhrase();

        if (Session::has('default_captcha_code')) {
            Session::forget('default_captcha_code');
        }
        Session::put('default_captcha_code', $phrase);
        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type:image/jpeg");
        $builder->output();
    }

    /**
     * @return Renderable
     */
    public function login(): Renderable
    {
        return view('admin-views.auth.login');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function submit(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        //recaptcha validation
        // $recaptcha = Helpers::get_business_settings('recaptcha');
        // if (isset($recaptcha) && $recaptcha['status'] == 1) {
        //     $request->validate([
        //         'g-recaptcha-response' => [
        //             function ($attribute, $value, $fail) {
        //                 $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
        //                 $response = $value;
        //                 $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
        //                 $response = \file_get_contents($url);
        //                 $response = json_decode($response);
        //                 if (!$response->success) {
        //                     $fail(translate('ReCaptcha Failed'));
        //                 }
        //             },
        //         ],
        //     ]);
        // } else {
        //     if (strtolower($request->default_captcha_value) != strtolower(Session('default_captcha_code'))) {
        //         Session::forget('default_captcha_code');
        //         return back()->withErrors(translate('Captcha Failed'));
        //     }
        // }

        // if (Session::has('default_captcha_code')) {
        //     Session::forget('default_captcha_code');
        // }
        //end recaptcha validation

        $admin = $this->admin->where('email', $request->email)->first();
        if (isset($admin) && $admin->status == false) {
            return back()->withErrors(translate('You have been blocked'));
        }

        if (auth('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('admin.time.add');
            // return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors([translate('Credentials does not match.')]);
    }

    /**
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $date = date('Y-m-d');
        $admin_id = auth('admin')->user()->id;
        $register = Register::where('admin_id', $admin_id)->whereDate('open_time', $date)->opened()->first();
        if ($register) {
            Toastr::warning(translate('Please close you register first!'));
            return redirect()->route('admin.registers.create', [$register->id]);
        }
        $checkin = AdminActivity::where('admin_id',$admin_id)->where(function($query) use ($date){
            $query->where('start_date', $date)
            ->orWhere('start_date', date('Y-m-d',strtotime("-1 days")))
            ->where('end_date', null);
        })->first();
        if (isset($checkin) && $checkin->end_time == null) {
            Toastr::warning(translate('Please do clock out first!'));
            return redirect()->route('admin.time.add');
        }
        auth()->guard('admin')->logout();
        return redirect()->route('admin.auth.login');
    }
}
