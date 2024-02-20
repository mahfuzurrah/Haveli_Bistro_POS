<link rel="stylesheet" href="https://cdn.datatables.net/1.11.1/css/jquery.dataTables.min.css">

<table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 mt-3" id="datatable">
    <thead class="thead-light">
        <tr>
            <th>{{translate('SL')}} </th>
            <th>{{translate('Employee Name')}}</th>
            <th>{{translate('Start Date')}}</th>
            <th>{{translate('End Date')}}</th>
            <th>{{translate('Start Time')}}</th>
            <th>{{translate('End Time')}}</th>
            <th>{{translate('Work Time')}}</th>
        </tr>
    </thead>
    <tbody>
    @foreach($checkins as $key=>$row)
        <tr>
            <td class="">
                {{$key+1}}
            </td>
            <td><div class="text-capitalize">{{$row['admin']['admin_name'] ?? '--'}}</div></td>
            <td><div class="text-capitalize">{{ date('M-d-y', strtotime($row['start_date'])) ?? '--'}}</div></td>
            <td><div class="text-capitalize">{{date('M-d-y', strtotime($row['end_date'])) ?? '--'}}</div></td>
            <td><div class="text-capitalize">{{$row['start_time'] ?? '--'}}</div></td>
            <td><div class="text-capitalize">{{$row['end_time'] ?? '--'}}</div></td>
            <td><div class="text-capitalize">{{$row['work_time'] ?? '--'}}</div></td>
        </tr>
    @endforeach
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function () {
        $('input').addClass('form-control');
    });

    // INITIALIZATION OF DATATABLES
    // =======================================================
    var datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
        dom: 'Bfrtip',
        "iDisplayLength": 25,
    });
</script>
