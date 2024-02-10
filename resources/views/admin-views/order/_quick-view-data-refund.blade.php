<div class="modal-header p-2">
    <h4 class="modal-title product-title">Order Id : {{ $order->id ?? '' }}</h4>
    <button class="close call-when-done" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form  method="post" id="add-to-refund-form">
        @csrf
        <div class="row pl-2">
            <div class="col-12 col-lg-12">
                <div class="form-group">
                    <table class="table">
                        <thead>
                            <tr>
                                <th> No</th>
                                <th>Item Name</th>
                                <th>Item Qty.</th>
                                <th>Item Price</th>
                                <th> Total</th>
                                <th> Delete</th>

                            </tr>
                        </thead>
                        <tbody>

   @if (session()->has('refund_item'))
    @php
    $subTotal=0;
    $GST=0;
    $total=0;
   @endphp
@foreach (session()->get('refund_item') as $key=>$item)

 @php
    $subTotal+=$item['price']*$item['quantity'];
 @endphp


<tr>
    <td>{{ $key+1 }}</td>
    <td>{{ Str::limit($item['name'], 20, '...') }} </td>
    <td>
        <div class="container">
            <input type="button" onclick="decrementValue()" value="-" />
            <input class="text-center" type="number" name="quantity" value="1" maxlength="2" max="10" size="1" id="number" />
            <input type="button" onclick="incrementValue()" value="+" />
            </div>

    </td>
    <td>{{ \App\CentralLogics\Helpers::set_symbol($item['price']) }}</td>
    <td>{{ \App\CentralLogics\Helpers::set_symbol($item['price']*$item['quantity'] ?? '') }}</td>
    <td>
            <a href="javascript:removeFromCartRefund({{ $key }})"
                class="btn btn-sm btn-outline-danger square-btn form-control">
                <i class="tio-delete"></i>
            </a>
    </td>
</tr>


@endforeach
<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>Subtotal</td>
    <td>{{ \App\CentralLogics\Helpers::set_symbol($subTotal ?? '0') }}</td>
</tr>
@php
$GST=$subTotal*5/100;
$total=$subTotal+$GST;
@endphp
<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>GST 5%</td>
    <td>{{ \App\CentralLogics\Helpers::set_symbol($GST ?? '0')}}</td>
</tr>
<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>Total</td>
    <td>{{ \App\CentralLogics\Helpers::set_symbol($total ?? '0') }}</td>
</tr>

@endif
  </tbody>
  </table>
  </div>
  </div>

        </div>


        <div class="d-flex justify-content-end">

            <button type="submit" id="" onclick="addRefund()" class="btn btn-primary">{{translate('Refund')}}</button>
        </div>

</div>
<script type="text/javascript">
    function incrementValue()
    {
        var value = parseInt(document.getElementById('number').value, 10);
        value = isNaN(value) ? 0 : value;
        if(value<10){
            value++;
                document.getElementById('number').value = value;
        }
    }
    function decrementValue()
    {
        var value = parseInt(document.getElementById('number').value, 10);
        value = isNaN(value) ? 0 : value;
        if(value>1){
            value--;
                document.getElementById('number').value = value;
        }

    }
    </script>
