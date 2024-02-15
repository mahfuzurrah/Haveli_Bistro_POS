@extends('layouts.admin.app')

@section('title','')

@push('css_or_js')
    <style>
        @media print {
            .non-printable {
                display: none;
            }

            .printable {
                display: block;
            }
        }

        .hr-style-2 {
            border: 0;
            height: 1px;
            background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
        }

        .hr-style-1 {
            overflow: visible;
            padding: 0;
            border: none;
            border-top: medium double #000000;
            text-align: center;
        }
        #printableAreaContent * {
            font-weight: normal !important;
        }
    </style>

    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 2px;
        }

    </style>
@endpush

@section('content')

    <div class="content container-fluid" style="color: black">
        <div class="row justify-content-center" id="printableArea">
            <div class="col-md-12">
                <center>
                    <input type="button" class="btn btn-primary non-printable "  onclick="printDiv('printableArea')"
                           value="{{translate('Print ')}}"/>
                    <a href="{{ route('admin.registers.index') }}" class="btn btn-danger non-printable">{{translate('Cancel')}}</a>
                </center>
                <hr class="non-printable">
            </div>
            <div class="col-5" id="printableAreaContent">
                <div class="text-center pt-4 mb-3">
                    <h2 style="line-height: 1">{{\App\Model\BusinessSetting::where(['key'=>'restaurant_name'])->first()->value}}</h2>
                    <h5 style="font-size: 20px;font-weight: lighter;line-height: 1">
                        {{\App\Model\BusinessSetting::where(['key'=>'address'])->first()->value}}
                    </h5>
                    <h5 style="font-size: 16px;font-weight: lighter;line-height: 1">
                        Phone : {{\App\Model\BusinessSetting::where(['key'=>'phone'])->first()->value}}
                    </h5>
                </div>
                <hr class="text-dark hr-style-1">

                <div class="row mt-4">
                    <div class="col-6">
                        <h5>{{translate('Register ID : ')}} {{$register->id}}</h5>
                    </div>
                    <div class="col-6">
                        <h5 style="font-weight: lighter">
                            <span class="font-weight-normal">{{date('M, d Y h:m a', strtotime($register->created_at))}}</span>
                        </h5>
                    </div>
                    <div class="col-12">
                        <h5>
                            {{translate('Name : ')}}<span class="font-weight-normal">{{$register->admin->f_name.' '.$register->admin->l_name}}</span>
                        </h5>
                        <h5>
                            {{translate('Phone : ')}}<span class="font-weight-normal">{{$register->admin->phone}}</span>
                        </h5>
                        <h5>
                            {{translate('Shift : ')}}<span class="font-weight-normal">{{translate('Shift')}} {{$register->shift}}</span>
                        </h5>
                    </div>
                </div>
                <h5 class="text-uppercase"></h5>
                <hr class="text-dark hr-style-2">
                <table class="table table-bordered mt-3">
                    <thead>
                    <tr>
                        <th style="width: 10%">{{translate('Status')}}</th>
                        <th class="">{{translate('Note')}}</th>
                        <th style="text-align:right; padding-right:4px">{{translate('Cash Amount')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="">
                                {{ translate('Open') }}
                            </td>
                            <td class="">
                                <span style="word-break: break-all;"> {{ $register->open_notes }}</span><br>
                            </td>
                            <td style="width: 28%;padding-right:4px; text-align:right">
                                {{ \App\CentralLogics\Helpers::set_symbol($register->open_amount) }}
                            </td>
                        </tr>

                        <tr>
                        <td class="">
                                {{ translate('Close') }}
                            </td>
                            <td class="">
                                <span style="word-break: break-all;"> {{ $register->close_notes }}</span><br>
                            </td>
                            <td style="width: 28%;padding-right:4px; text-align:right">
                                {{ \App\CentralLogics\Helpers::set_symbol($register->close_amount) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr class="text-dark hr-style-2">
                <h5 class="text-center pt-3">
                    {{translate('"""THANK YOU"""')}}
                </h5>
                <hr class="text-dark hr-style-2">
                <div class="text-center">{{\App\Model\BusinessSetting::where(['key'=>'footer_text'])->first()->value}}</div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        function printDiv(divName) {

            if($('html').attr('dir') === 'rtl') {
                $('html').attr('dir', 'ltr')
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                $('#printableAreaContent').attr('dir', 'rtl')
                window.print();
                document.body.innerHTML = originalContents;
                $('html').attr('dir', 'rtl')
            }else{
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }

        }
    </script>
@endpush
