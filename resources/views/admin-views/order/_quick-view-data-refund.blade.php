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
                                <th> ID</th>
                                <th>Product Name</th>
                                <th>Product Qty</th>
                                <th>Unit price</th>
                                <th> price</th>

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
    <td>{{ $item['name'] }}</td>
    <td>
    <div class=" quantity">
    <button  class="text-danger">-</button>
    <input class="text-center"  type="text" value="2" style="width: 30px; ">
    <button class=" text-danger">+</button>
    </div>

    </td>
    <td>{{ $item['price'] }}</td>
    <td>{{$item['price']*$item['quantity'] ?? '' }}</td>
</tr>
@endforeach
<tr>
    <td></td>
    <td></td>
    <td></td>
    <td>Subtotal</td>
    <td>{{ $subTotal ?? '0' }}</td>
</tr>
@php
$GST=$subTotal*5/100;
$total=$subTotal+$GST;
@endphp
<tr>
    <td></td>
    <td></td>
    <td></td>
    <td>GST 5%</td>
    <td>{{ $GST ?? '0'}}</td>
</tr>
<tr>
    <td></td>
    <td></td>
    <td></td>
    <td>Total</td>
    <td>{{ $total ?? '0' }}</td>
</tr>


@endif


                        </tbody>

                    </table>

                </div>
            </div>

        </div>


        <div class="d-flex justify-content-end">
            <button type="reset" class="btn btn-secondary mr-1">{{translate('reset')}}</button>
            <button type="submit" id="" onclick="addRefund()" class="btn btn-primary">{{translate('Refund')}}</button>
        </div>
    </form>
</div>
