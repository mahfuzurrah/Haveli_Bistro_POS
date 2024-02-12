@extends('layouts.admin.app')

@section('title', translate('Clock-in / Clock-out'))

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
                    {{ translate('Clock-in / Clock-out') }}
                </span>
            </h2>
        </div>
        <!-- End Page Header -->


        <div class="row g-3">
            <div class="col-12">
                <div class="card card-body">
                    <form action="#" method="post" enctype="multipart/form-data">
                        @csrf
                        @php($data = Helpers::get_business_settings('language'))
                        @php($default_lang = Helpers::get_default_language())

                        @if ($data && array_key_exists('code', $data[0]))
                            <ul class="nav w-fit-content nav-tabs mb-4">
                                @foreach ($data as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link lang_link {{ $lang['default'] == true ? 'active' : '' }}"
                                            href="#"
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
                                        <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                    @endforeach
                                @endif
                                <div class="d-flex justify-content-start mb-3 gap-3">
                                    @if (!isset($checkin))
                                   <a href="{{ route('admin.time.checkin') }}"><button type="button" id="reset" class="btn btn-success">{{ translate('Clock In') }}</button></a>
                                    @elseif(!isset($checkin->end_time))
                                    <a href="javascript:"
                                    onclick="Swal.fire({
                                            title: '{{ translate('Do you want to clock out') }}?',
                                            showDenyButton: true,
                                            showCancelButton: true,
                                            confirmButtonColor: '#FC6A57',
                                            cancelButtonColor: '#363636',
                                            confirmButtonText: '{{ translate('Yes') }}',
                                            denyButtonText: `{{ translate('Do not Clock out') }}`,
                                            }).then((result) => {
                                            if (result.value) {
                                            location.href='{{ route('admin.time.checkout') }}';
                                            } else{
                                            Swal.fire('Canceled', '', 'info')
                                            }
                                            })"><button type="button" class="btn btn-warning">{{ translate('Clock Out') }}</button></a>
                                    @endif
                                    @if(isset($checkin))
                                    <div class="timr_otrdv">
                                        @if($checkin->end_time == null)
                                           <h2><div id="timer"></div> </h2>
                                        @else
                                        <div class="scuss_tmr"><h2> {{ $checkin->work_time }}   <span>hrs</span></h2></div>
                                        @endif
                                    </div>
					            @endif
                                </div>
                            </div>
                        </div>
                    </form>

    {{-- <div class="col-12 mb-3">
        <div class="card">
            <div class="card-top px-card pt-4">
                <div class="row justify-content-between align-items-center gy-2">
                    <div class="col-sm-4 col-md-6 col-lg-8">
                        <h5 class="d-flex gap-1 mb-0">
                            {{ translate('') }}
                            <span class="badge badge-soft-dark rounded-50 fz-12">{{ $activities->total() }}</span>
                        </h5>
                    </div>
                    <div class="col-sm-8 col-md-6 col-lg-4">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                    placeholder="{{ translate('Search by date') }}" aria-label="Search"
                                    value="" required autocomplete="off">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">{{ translate('Search') }}</button>
                                </div>
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
                                <th>{{ translate('SL') }}</th>
                                <th>{{ translate('Category_Image') }}</th>
                                <th>{{ translate('name') }}</th>
                                <th>{{ translate('status') }}</th>
                                <th>{{ translate('priority') }}</th>
                                <th class="text-center">{{ translate('action') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($activities as $key => $activity)
                                <tr>
                                    <td>{{ $activities->firstitem() + $key }}</td>
                                    <td>
                                        <div>
                                            <img width="50" class="avatar-img rounded"
                                                src="{{ asset('storage/app/public/category') }}/{{ $activity['image'] }}"
                                                onerror="this.src='{{ asset('assets/admin/img/icons/category_img.png') }}'"
                                                alt="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-capitalize">{{ $activity['name'] }}</div>
                                    </td>
                                    <td>
                                        <div class="">
                                            <label class="switcher">
                                                <input class="switcher_input" type="checkbox"
                                                    {{ $activity['status'] == 1 ? 'checked' : '' }}
                                                    id="{{ $activity['id'] }}" onchange="status_change(this)"
                                                    data-url="{{ route('admin.category.status', [$activity['id'], 1]) }}">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>

                                    </td>
                                    <td>
                                        <div class="">
                                            <select name="priority" class="custom-select"
                                                onchange="location.href='{{ route('admin.category.priority', ['id' => $activity['id'], 'priority' => '']) }}' + this.value">
                                                @for ($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}"
                                                        {{ $activity->priority == $i ? 'selected' : '' }}>
                                                        {{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-outline-info btn-sm edit square-btn"
                                                href="{{ route('admin.category.edit', [$activity['id']]) }}">
                                                <i class="tio-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm delete square-btn"
                                                onclick="form_alert('category-{{ $activity['id'] }}','{{ translate('Want to delete this') }}')">
                                                <i class="tio-delete"></i>
                                            </button>
                                        </div>
                                        <form action="{{ route('admin.category.delete', [$activity['id']]) }}"
                                            method="post" id="category-{{ $activity['id'] }}">
                                            @csrf @method('delete')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-4 px-3">
                    <div class="d-flex justify-content-lg-end">
                        <!-- Pagination -->
                        {!! $activities->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- End Table -->
    </div>
    </div>

@endsection

@push('script_2')
    <script>

        //CALCULATE TIME
        calculateTime()

        function calculateTime() {
            var dt = new Date();
            var hrs = ("0" + dt.getHours()).slice(-2);
            var mins = ("0" + dt.getMinutes()).slice(-2);
            var seconds = ("0" + dt.getSeconds()).slice(-2);
            var time =  hrs + ":" + mins + ":" + seconds;
            var time_new =  dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
            var start_time = "{{ $checkin->start_time ?? '00:00:00'}}";
            var date =  "{{ $checkin->start_date ??  date('Y-m-d') }}";
            var date_new =  "{{ date('Y-m-d') }}";
            var timeStart = new Date(date +" " + start_time);
            var timeEnd = new Date(date_new+" " + time_new);
            if(timeEnd >= timeStart){
            let d = (new Date(timeEnd)) - (new Date(timeStart));
            let weekdays     = Math.floor(d/1000/60/60/24/7);
            let days         = Math.floor(d/1000/60/60/24 - weekdays*7);
            let hours        = Math.floor(d/1000/60/60    - weekdays*7*24            - days*24);
            let minutes      = Math.floor(d/1000/60       - weekdays*7*24*60         - days*24*60         - hours*60);
            let seconds      = Math.floor(d/1000          - weekdays*7*24*60*60      - days*24*60*60      - hours*60*60      - minutes*60);
            let milliseconds = Math.floor(d               - weekdays*7*24*60*60*1000 - days*24*60*60*1000 - hours*60*60*1000 - minutes*60*1000 - seconds*1000);
            var totalHours = hours + ":" + minutes + ":" + seconds;
            $("#timer").text(totalHours);
            setTimeout('calculateTime()', 1000);

            }
        }
    </script>
    <script>
        $(".lang_link").click(function(e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#" + lang + "-form").removeClass('d-none');
            if (lang == '{{ $default_lang }}') {
                $(".from_part_2").removeClass('d-none');
            } else {
                $(".from_part_2").addClass('d-none');
            }
        });
    </script>
    <script>
        $(document).on('ready', function() {

            // INITIALIZATION OF DATATABLES
            // =======================================================
            // var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            var datatable = $('.table').DataTable({
                "paging": false
            });

            $('#column1_search').on('keyup', function() {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });


            $('#column3_search').on('change', function() {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script>
        function readURL(input, viewer_id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#' + viewer_id).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this, 'viewer');
        });
        $("#customFileEg2").change(function() {
            readURL(this, 'viewer2');
        });
    </script>

    <script>
        function change_priority(id, priority, message) {
            console.log(id);
            console.log(priority);
            console.log(message);
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('No') }}',
                confirmButtonText: '{{ translate('Yes') }}',
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
                            toastr.success("{{ translate('Priority changed successfully') }}");
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        },
                        error: function(xhr) {
                            toastr.error("{{ translate('Priority changed failed') }}");
                        }
                    });
                }
            })
        }
    </script>
@endpush
