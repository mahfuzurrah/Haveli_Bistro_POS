@extends('layouts.admin.app')

@section('title', translate('Registers List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <img width="20" class="avatar-img" src="{{asset('assets/admin/img/icons/category.png')}}" alt="">
                <span class="page-header-title">
                    {{translate('Registers Open/Close')}} {{translate('List')}}
                </span>
            </h2>
        </div>
        <!-- End Page Header -->


        <div class="row g-3">
            <div class="col-12">
                <div class="card card-body">
                    <form action="{{route('admin.category.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @php($data = Helpers::get_business_settings('language'))
                        @php($default_lang = Helpers::get_default_language())

                        @if ($data && array_key_exists('code', $data[0]))
                        <ul class="nav w-fit-content nav-tabs mb-4">
                            @foreach ($data as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link {{ $lang['default'] == true ? 'active' : '' }}" href="#"
                                    id="{{ $lang['code'] }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang['code']) . '(' . strtoupper($lang['code']) . ')' }}</a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="row align-items-end">
                            <div class="col-12">
                                @foreach ($data as $lang)
                                    <div class="form-group {{ $lang['default'] == false ? 'd-none' : '' }} lang_form"
                                        id="{{ $lang['code'] }}-form">
                                    </div>
                                @endforeach
                                @else
                                <div class="row gy-4">
                                    <div class="col-md-6 mb-4">
                                        <input type="hidden" name="lang[]" value="{{ $default_lang }}"> 
                                        <input name="position" value="0" class="d--none">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12 mb-3">
                <div class="card">
                <div class="row justify-content-between align-items-center mb-3 mt-2 mx-1">
                    <div class="col-sm-8 col-md-8 col-lg-8">
                        <div>
                            <form action="{{ route('admin.registers.index') }}" method="GET">
                                <div class="row gy-3 gx-2 align-items-end">
                                    <div class="col-md-4 col-lg-3">
                                        <label for="select_branch">{{translate('Select Shift')}}</label>
                                        <!-- Select -->
                                        <select class="form-control" name="shift">
                                            <option value="" @if((request()->shift ?? '') == '') selected @endif>{{ translate('Select Shift') }}</option>
                                            <option value="1" @if((request()->shift ?? '') == 1) selected @endif>{{ translate('Shift 1') }}</option>
                                            <option value="2" @if((request()->shift ?? '') == 2) selected @endif>{{ translate('Shift 2') }}</option>
                                        </select>
                                        <!-- End Select -->
                                    </div>
                                    <div class="col-md-4 col-lg-3">
                                        <div class="form-group mb-0">
                                            <label class="text-dark">{{ translate('Start Date') }}</label>
                                            <input type="date" name="from" value="{{ request()->from ?? '' }}" id="from_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-lg-3">
                                        <div class="form-group mb-0">
                                            <label class="text-dark">{{ translate('End Date') }}</label>
                                            <input type="date" value="{{ request()->to ?? '' }}" name="to" id="to_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-3 d-flex gap-2">
                                        <a href="{{ route('admin.registers.index') }}"> <button type="button" class="btn btn-secondary flex-grow-1">{{ translate('Clear') }}</button></a>
                                        <button type="submit" class="btn btn-primary text-nowrap flex-grow-1">{{ translate('Show Data') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 d-flex justify-content-end">
                        <div>
                            <button type="button" class="btn btn-outline-primary" data-toggle="dropdown" aria-expanded="false">
                                <i class="tio-download-to"></i>
                                {{translate('export')}}
                                <i class="tio-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a type="submit" class="dropdown-item d-flex align-items-center gap-2" href="#">
                                        <img width="14" src="{{asset('assets/admin/img/icons/excel.png')}}" alt="">
                                        {{translate('excel')}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                    <!-- Table -->
                    <div class="">
                        <div class="table-responsive datatable-custom">
                            <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{translate('SL')}}</th>
                                        <th>{{translate('Shift')}}</th>
                                        <th>{{translate('Open Cash')}}</th>
                                        <th>{{translate('Open Time')}}</th>
                                        <th>{{translate('Close Cash')}}</th>
                                        <th>{{translate('Close Time')}}</th>
                                        <th>{{translate('Status')}}</th>
                                        <th>{{translate('Actions')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @if ($registers->count())
                                        @foreach($registers as $key => $register)
                                            <tr>
                                                <td>{{$registers->firstitem()+$key}}</td>
                                                <td><div class="text-capitalize"> {{ translate('Shift') }} {{ $register->shift }}</div></td>
                                                <td><div class="text-capitalize">${{ number_format($register->open_amount,2) }}</div></td>
                                                <td><div class="text-capitalize">{{ $register->open_time }}</div></td>
                                                <td><div class="text-capitalize">${{ number_format($register->open_amount, 2) }}</div></td>
                                                <td><div class="text-capitalize">{{ $register->close_time ?? '--' }}</div></td>
                                                <td>
                                                    <div class="text-capitalize">  
                                                        @if($register->close_time)
                                                        <label class="badge badge-soft-info px-2 rounded">{{ translate('Closed') }}</label>
                                                        @else
                                                        <label class="badge badge-soft-danger px-2 rounded">{{ translate('Opened') }}</label>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-sm" type="button"><span>{{ translate('Print') }}</span></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive mt-4 px-3">
                            <div class="d-flex justify-content-lg-end">
                                {!! $registers->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            var datatable = $('.table').DataTable({
                "paging": false
            });

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('change', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
@endpush

