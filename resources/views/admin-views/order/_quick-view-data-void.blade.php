<div class="modal-header p-2">
    <h4 class="modal-title product-title">Order Id:{{ $order->id }}</h4>
    <button class="close call-when-done" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="add-to-void-form" action="{{ route('admin.pos.add-to-void', [$order->id]) }}" method="post">
        @csrf
        <div class="row pl-2">
            <div class="col-12 col-lg-12">
                <div class="form-group">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Item Name</th>
                                <th>Item Qty.</th>
                                <th>Item Price</th>
                                <th>Total</th>
                                <th>Select</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($order->details)
                                @foreach($order->details as $key => $item)
                                @if (!in_array($item->product_id, $order->getRefundedOrdersProductsId()) && !in_array($item->product_id, $order->getVoidedOrdersProductsId()))
                                        @php $totalPrice = $item->price * $item->quantity ?? 0 @endphp
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ Str::limit($item->getProductName(), 20, '...') }} </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ \App\CentralLogics\Helpers::set_symbol($item->price) }}</td>
                                            <td>{{ \App\CentralLogics\Helpers::set_symbol($totalPrice) }}</td>
                                            <td>
                                                <input type="checkbox" checked class="order_products" data-price="{{ $totalPrice }}" name="order_products[{{$key}}]" value="{{ $item->id }}">
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Subtotal</td>
                                <td>$<span class="sub-total-void">0</span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>GST 5%</td>
                                <td>$<span class="gst-void">0</span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total</td>
                                <td>$<span class="total-void">0</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" disabled class="btn btn-primary submit-void-btn">{{translate('Void')}}</button>
        </div>
    </form>
</div>
