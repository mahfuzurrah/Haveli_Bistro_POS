@extends('layouts.admin.app')

@section('title', translate('Clock List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex flex-wrap gap-2 align-items-center mb-4">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <img width="20" class="avatar-img" src="{{asset('assets/admin/img/icons/category.png')}}" alt="">
                <span class="page-header-title">
                    {{translate('Clock-in / Clock-out')}}
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
                                        @endif
                                        <input name="position" value="0" class="d--none">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-top px-card pt-4">
                        <div class="row justify-content-between align-items-center gy-2">
                            <div class="col-sm-4 col-md-6 col-lg-8">
                                <h5 class="d-flex gap-1 mb-0">
                                    {{translate('Clock_Table')}}
                                    <span class="badge badge-soft-dark rounded-50 fz-12">{{ $checkins->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <form action="{{url()->current()}}" method="GET">
                                    <div class="input-group">
                                        {{-- <input id="datatableSearch_" type="search" name="search"
                                            class="form-control"
                                            placeholder="{{translate('Search by category name')}}" aria-label="Search"
                                            value="" required autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">{{translate('Search')}}</button>
                                        </div> --}}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Table -->
                    <div class="py-4">
                        <div class="table-responsive datatable-custom">
                            <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{translate('SL')}}</th>
                                        {{-- <th>{{translate('Category_Image')}}</th> --}}
                                        {{-- <th>{{translate('name')}}</th> --}}
                                        <th>{{translate('Start Date')}}</th>
                                        <th>{{translate('End Date')}}</th>
                                        <th>{{translate('Start Time')}}</th>
                                        <th>{{translate('End Time')}}</th>
                                        <th>{{translate('Work Time')}}</th>
                                        {{-- <th class="text-center">{{translate('action')}}</th> --}}
                                    </tr>
                                </thead>

                                <tbody>
                                @foreach($checkins as $key=>$checkin)
                                    <tr>
                                        <td>{{$checkins->firstitem()+$key}}</td>
                                        <td><div class="text-capitalize">{{$checkin['start_date'] ?? '--'}}</div></td>
                                        <td><div class="text-capitalize">{{$checkin['end_date'] ?? '--'}}</div></td>
                                        <td><div class="text-capitalize">{{$checkin['start_time'] ?? '--'}}</div></td>
                                        <td><div class="text-capitalize">{{$checkin['end_time'] ?? '--'}}</div></td>
                                        <td><div class="text-capitalize">{{$checkin['work_time'] ?? '--'}}</div></td>

                                        {{-- <td>
                                            <div>
                                                <img width="50" class="avatar-img rounded" src="{{asset('storage/app/public/category')}}/{{$category['image']}}" onerror="this.src='{{asset('assets/admin/img/icons/category_img.png')}}'" alt="">
                                            </div>
                                        </td>
                                        <td><div class="text-capitalize">{{$category['name']}}</div></td> --}}
                                        {{-- <td>
                                                <div class="">
                                                    <label class="switcher">
                                                        <input class="switcher_input" type="checkbox" {{$category['status']==1? 'checked' : ''}} id="{{$category['id']}}"
                                                        onchange="status_change(this)" data-url="{{route('admin.category.status',[$category['id'],1])}}"
                                                        >
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </div>

                                        </td>
                                        <td>
                                            <div class="">
                                                <select name="priority" class="custom-select"
                                                        onchange="location.href='{{ route('admin.category.priority', ['id' => $category['id'], 'priority' => '']) }}' + this.value">
                                                    @for($i = 1; $i <= 10; $i++)
                                                        <option value="{{ $i }}" {{ $category->priority == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </td> --}}
                                        {{-- <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a class="btn btn-outline-info btn-sm edit square-btn"
                                                href="{{route('admin.category.edit',[$category['id']])}}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-sm delete square-btn"
                                                    onclick="form_alert('category-{{$category['id']}}','{{translate("Want to delete this")}}')">
                                                    <i class="tio-delete"></i>
                                                </button>
                                            </div>
                                            <form action="{{route('admin.category.delete',[$category['id']])}}"
                                                method="post" id="category-{{$category['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </td> --}}
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive mt-4 px-3">
                            <div class="d-flex justify-content-lg-end">
                                <!-- Pagination -->
                                {!! $checkins->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(".lang_link").click(function(e){
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#"+lang+"-form").removeClass('d-none');
            if(lang == '{{$default_lang}}')
            {
                $(".from_part_2").removeClass('d-none');
            }
            else
            {
                $(".from_part_2").addClass('d-none');
            }
        });
    </script>
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            // var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

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


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script>
        function readURL(input, viewer_id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+viewer_id).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this, 'viewer');
        });
        $("#customFileEg2").change(function () {
            readURL(this, 'viewer2');
        });
    </script>

    <script>

       function change_priority(id, priority, message) {
           console.log(id);
           console.log(priority);
           console.log(message);
           Swal.fire({
               title: '{{translate("Are you sure?")}}',
               text: message,
               type: 'warning',
               showCancelButton: true,
               cancelButtonColor: 'default',
               confirmButtonColor: '#FC6A57',
               cancelButtonText: '{{translate("No")}}',
               confirmButtonText: '{{translate("Yes")}}',
               reverseButtons: true
           }).then((result) => {
               if (result.value) {
                   const csrfToken = $('meta[name="csrf-token"]').attr('content');

                   // Create a FormData object to pass data to the backend
                   const formData = new FormData();
                   formData.append('_token', csrfToken);
                   formData.append('id', id); // Append category ID
                   formData.append('priority', priority); // Append selected priority

                   $.ajax({
                       url: "{{ route('admin.category.priority') }}",
                       method: "POST",
                       data: formData,
                       processData: false,
                       contentType: false,
                       success: function(response) {
                           toastr.success("{{translate('Priority changed successfully')}}");
                           setTimeout(function() {
                               location.reload();
                           }, 2000);
                       },
                       error: function(xhr) {
                           toastr.error("{{translate('Priority changed failed')}}");
                       }
                   });
               }
           })
       }
    </script>

@endpush
