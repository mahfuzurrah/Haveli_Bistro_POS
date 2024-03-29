@extends('layouts.admin.app')

@section('title', translate('Clock Report'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <img width="20" class="avatar-img" src="{{asset('assets/admin/img/icons/product_report.png')}}" alt="">
                <span class="page-header-title">
                    {{translate('Clock_Report')}}
                </span>
            </h2>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-body">
                <div class="media flex-column flex-sm-row flex-wrap align-items-sm-center gap-4">
                    <!-- Avatar -->
                    <div class="avatar avatar-xl">
                        <img class="avatar-img" src="{{asset('assets/admin')}}/svg/illustrations/order.png"
                                alt="Image Description">
                    </div>
                    <!-- End Avatar -->

                    <div class="media-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="">
                                <h2 class="page-header-title">{{translate('Clock_Report_Overview')}}</h2>

                                <div class="">
                                    <span>{{translate('admin')}}:</span>
                                    <a href="#">{{auth('admin')->user()->f_name.' '.auth('admin')->user()->l_name}}</a>
                                </div>
                            </div>

                            <div class="d-flex">
                                <a class="btn btn-icon btn-primary px-2 rounded-circle" href="{{route('admin.dashboard')}}">
                                    <i class="tio-home-outlined"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <form action="javascript:" id="search-form" method="POST">
                    @csrf
                    <div class="row g-2">
                        <div class="col-sm-4">
                            <select class="form-control" name="admin_id" id="admin_id">
                                <option value="0">{{translate('select Employee')}}</option>
                                @foreach(\App\Model\Admin::all() as $employee)
                                    <option @if (request()->input('admin_id') != null && request()->input('admin_id') == $employee['id']) selected @endif
                                        value="{{$employee['id']}}">
                                        {{$employee['f_name'].' '.$employee['l_name']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <input type="date" name="from" id="from_date" value="{{ request()->has('from') ? request()->get('from') : ''  }}"
                                    class="form-control" required>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <input type="date" name="to" id="to_date" value="{{ request()->has('to') ? request()->get('to') : ''  }}"
                                    class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit"
                                    class="btn btn-primary btn-block">{{translate('show')}}</button>
                        </div>
                    </div>
                </form>
                    {{-- @if (request()->input('admin_id') != null) --}}
                        <div class="row g-2 mt-3">
                            <div class="col-md-6 d-flex flex-column gap-2">
                                <strong>
                                    {{translate('Total Hours')}} : <span id="total_hours"></span>
                                </strong>
                                <strong>
                                    {{translate('Total Shifts')}} : <span id="total_checkin"></span>
                                </strong>
                            </div>
                        </div>
                    {{-- @endif --}}

                <!-- Table -->
                <div class="table-responsive mt-3" id="set-rows">
                    @include('admin-views.report.partials._table-clock',['checkins'=>[]])
                </div>
                <!-- End Table -->
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF NAV SCROLLER
            // =======================================================
            $('.js-nav-scroller').each(function () {
                new HsNavScroller($(this)).init()
            });

            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });


            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'd-none'
                    },
                    {
                        extend: 'excel',
                        className: 'd-none'
                    },
                    {
                        extend: 'csv',
                        className: 'd-none'
                    },
                    {
                        extend: 'pdf',
                        className: 'd-none'
                    },
                    {
                        extend: 'print',
                        className: 'd-none'
                    },
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: '<div class="text-center p-4">' +
                        '<img class="mb-3" src="{{asset('assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">' +
                        '<p class="mb-0">{{translate('No data to show')}}</p>' +
                        '</div>'
                }
            });

            // INITIALIZATION OF TAGIFY
            // =======================================================
            $('.js-tagify').each(function () {
                var tagify = $.HSCore.components.HSTagify.init($(this));
            });
        });
    </script>

    <script>
        $('#search-form').on('submit', function () {
            $.post({
                url: "{{route('admin.time.filter.report')}}",
                data: $('#search-form').serialize(),

                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#total_hours').html(data.total_hours);
                    $('#total_checkin').html(data.total_checkin);
                    $('#set-rows').html(data.view);
                    $('.card-footer').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });

    </script>
    <script>
        $('#from_date,#to_date').change(function () {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('{{translate('Invalid date range!')}}', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }
        });
    </script>
@endpush
