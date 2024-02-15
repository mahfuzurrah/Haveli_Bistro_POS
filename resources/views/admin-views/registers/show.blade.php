@extends('layouts.admin.app')

@section('title', translate('Register Details'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0 d-flex align-items-center gap-1">
                <img width="20" class="avatar-img" src="{{asset('assets/admin/img/icons/order_details.png')}}" alt="">
                <span class="page-header-title">
                    {{translate('Register Details')}}
                </span>
            </h2>
            
        </div>
        <!-- End Page Header -->

        <div class="row" id="printableArea">
            <div class="col-lg-12 mb-3 mb-lg-0">
                <!-- Card -->
                <div class="card mb-3 mb-lg-5">
                    <!-- Header -->
                    <div class="px-card py-3">
                        <div class="row gy-2">
                            <div class="col-12">
                                <div>
                                    <h2 class="page-header-title h1 mb-3">{{translate('Register')}} #{{$register->id}}</h2>
                                    <h5 class="text-capitalize">
                                        {{translate('Shift')}} :
                                        <label class="badge-soft-info px-2 rounded">
                                        {{translate('Shift')}} {{ $register->shift }}
                                        </label>
                                    </h5>

                                    <h5 class="text-capitalize">
                                        {{translate('Status')}} :
                                        @if($register->close_time)
                                        <label class="badge-soft-success px-2 rounded">{{ translate('Closed') }}</label>
                                        @else
                                        <label class="badge-soft-danger px-2 rounded">{{ translate('Opened') }}</label>
                                        @endif
                                    </h5>
                                </div>
                            </div>
                            <div class="col-sm-6 d-flex flex-column justify-content-between">
                                <div class="text-sm-left">
                                    <div class="d-flex gap-3 justify-content-sm-start my-3">
                                        <span>{{translate('Open Amount')}} :</span>
                                        <span class="text-dark">${{ number_format($register->open_amount) }}</span>
                                    </div>

                                    <div class="text-capitalize d-flex gap-3 justify-content-sm-start mb-3">
                                        <span>{{translate('Open Time')}} :</span>
                                        <span class="text-dark">{{ date('M, d Y H:i A', strtotime($register->open_time)) }}</span>
                                    </div>

                                    <div class="text-capitalize d-flex gap-3 justify-content-sm-start mb-3">
                                        <span>{{translate('Open Note')}} :</span>
                                        <span class="text-dark">{{ $register->open_notes }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="text-sm-right">
                                    <div class="d-flex gap-3 justify-content-sm-end my-3">
                                        <span>{{translate('Close Amount')}} :</span>
                                        <span class="text-dark">${{ number_format($register->close_amount) }}</span>
                                    </div>

                                    <div class="text-capitalize d-flex gap-3 justify-content-sm-end mb-3">
                                        <span>{{translate('Close Time')}} :</span>
                                        @if ($register->close_time)
                                        <span class="text-dark">{{ date('M, d Y H:i A', strtotime($register->close_time)) }}</span>
                                        @else
                                        <span class="text-dark">{{ translate('N/A') }}</span>
                                        @endif
                                    </div>

                                    <div class="text-capitalize d-flex gap-3 justify-content-sm-end mb-3">
                                        <span>{{translate('Close Note')}} :</span>
                                        <span class="text-dark">{{ $register->close_notes }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.registers.index') }}" class="btn btn-danger ">{{translate('Cancel')}}</a>
                <!-- End Card -->
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection
