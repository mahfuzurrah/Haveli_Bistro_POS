@extends('layouts.admin.app')

@section('title', translate('Register Open/Close'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <img width="20" class="avatar-img" src="{{ asset('assets/admin/img/icons/category.png') }}" alt="">
                <span class="page-header-title">
                {{ translate('Edit Register') }}
                </span>
            </h2>
        </div>
        <!-- End Page Header -->

        <div class="row g-3">
            <div class="col-12">
                <div class="card card-body">
                    <form id="registerForm" action="{{ route('admin.registers.update', [$register->id]) }}" method="post" enctype="multipart/form-data">
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
                                        <div class="form-group {{ $lang['default'] == false ? 'd-none' : '' }} lang_form" id="{{ $lang['code'] }}-form">
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">{{translate('Select Shift')}} <span class="text-danger">*</span></label>
                                    <select name="shift" id="shiftId" class="form-control">
                                        <option value="1" @if($register->shift == 1) selected @endif>{{translate('Shift 1')}}</option>
                                        <option value="2" @if($register->shift == 2) selected @endif>{{translate('Shift 2')}}</option>
                                    </select>
                                    @error('shift')
                                        <span class="validation-error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">{{translate('Enter Open Cash')}} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="open_amount" placeholder="{{translate('Enter open cash amount')}}" required value="{{ $register->open_amount }}">
                                    @error('opent_amount')
                                        <span class="validation-error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">{{translate('Add Note')}}</label>
                                    <textarea placeholder="{{translate('Add note here')}}" name="open_notes" class="form-control" rows="3">{{ $register->open_notes }}</textarea>
                                    @error('open_notes')
                                        <span class="validation-error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">{{translate('Enter Close Cash')}} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="close_amount" placeholder="{{translate('Enter close cash amount')}}" required value="{{ $register->close_amount }}">
                                    @error('close_amount')
                                        <span class="validation-error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">{{translate('Add Close Note')}}</label>
                                    <textarea placeholder="{{translate('Add note here')}}" name="close_notes" class="form-control" rows="3">{{ $register->close_notes }}</textarea>
                                    @error('close_notes')
                                        <span class="validation-error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <a class="btn btn-info" href="{{ route('admin.registers.index') }}">{{translate('Cancel')}}</a>
                                <button class="btn btn-success" type="submit">{{translate('Save')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
<script>
    $(document).ready(function () {

        $('body').on('click', '.btn-close-register', function() {
            Swal.fire({
                title: '{{translate("Are you sure?")}}',
                text: '{{translate("You want to close this register?")}}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: 'default',
                cancelButtonText: '{{translate("No")}}',
                confirmButtonText:'{{translate("Yes")}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('form#registerForm').submit();
                }
            })
        });
    });
    </script>
@endpush