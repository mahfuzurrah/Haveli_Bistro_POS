<div class="modal-header p-2">
    <h4 class="modal-title product-title"></h4>
    <button class="close call-when-done" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <table class="table">
        <thead>

            <tr>
                <th>Hold Id</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Item</th>
                <th>Total Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

            @forelse ($orders as $item)


                <tr>
                    <td>{{ $item->id }} </td>
                    <td> {{ date('Y-m-d', strtotime($item->created_at)) }}</td>
                    <td>{{ isset($item->customer) ? $item->customer->f_name :'Walk in Customer' }} </td>
                    <td>{{ $item->details->count() ?? '' }} </td>
                    <td>{{ $item->order_amount ?? '' }} </td>
                    <td>

                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <button class="btn-primary " data-id="{{ $item->id }}" onclick="addToCartHold({{ $item->id }})">

                                    {{ translate('Sale') }}
                                </button>
                            </div>

                    </td>
                </tr>

            @empty
            <tr>
                <td colspan="5"></td>
            </tr>

            @endforelse



        </tbody>
    </table>
</div>
