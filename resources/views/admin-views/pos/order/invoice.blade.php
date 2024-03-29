<div style="width:480px;margin-left:18px" id="printableAreaContent">
    <div class="marchant-copy-print">
        <div class="text-center pt-3 w-100">
            <h2 style="line-height: 1; font-size: 28px;">{{ \App\Model\BusinessSetting::where(['key' => 'restaurant_name'])->first()->value }}
            </h2>
            <h5 style="font-size: 16px;font-weight: lighter;line-height: 1">
                {!! nl2br(\App\Model\BusinessSetting::where(['key' => 'address'])->first()->value) !!}
            </h5>
            <h5 style="font-size: 16px;font-weight: lighter;line-height: 1">
                {{ translate('Phone') }} : {{ \App\Model\BusinessSetting::where(['key' => 'phone'])->first()->value }}
            </h5>
            <!-- <h5 style="font-size: 16px;font-weight: lighter;line-height: 1">
            {{ translate('Branch: Main Branch') }}
        </h5> -->
        </div>

        <span>--------------------------------------------------------</span>
        <div class="row mt-3">
            <div class="col-6">
                <h4>{{ translate('Order ID') }}: {{ $order['id'] }}</h4>
            </div>
            <div class="col-6">
                <h5 style="font-weight: lighter">
                    {{ date('M d Y, h:i a', strtotime($order['created_at'])) }}
                </h5>
            </div>

            @if($order->admin)
            <div class="col-6">
                <h5 style="font-weight: lighter">
                    {{ translate('Server Name:') }} {{ $order->admin->f_name }} {{ $order->admin->l_name }}
                </h5>
            </div>
            @endif
            @if ($order->table_id)
            <div class="col-6">
                <h5>{{ translate('Table No :') }} {{ $order->table->number ?? '' }}</h5>
            </div>
            @endif

            @if ($order->number_of_people)
            <div class="col-6">
                <h5>{{ translate('People :') }} {{ $order->number_of_people }}</h5>
            </div>
            @endif


            @if ($order->customer)
            <div class="col-12">
                <span>--------------------------------------------------------</span>
                <h5>{{ translate('Customer Name') }} : {{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}
                </h5>
                <h5>{{ translate('Phone') }} : {{ $order->customer['phone'] }}</h5>
            </div>
            @endif
        </div>
        <h5 class="text-uppercase"></h5>
        <span>--------------------------------------------------------</span>
        <table class="table table-bordered mt-3 print-table" style="width: 98%">
            <!-- <thead>
            <tr>
                <th style="width: 10%">{{ translate('QTY') }}</th>
                <th class="">{{ translate('DESC') }}</th>
                <th style="text-align:right; padding-right:4px">{{ translate('Price') }}</th>
            </tr>
            </thead> -->

            <tbody>
                @php($item_price = 0)
                @php($total_tax = 0)
                @php($total_dis_on_pro = 0)
                @php($add_ons_cost = 0)
                @php($add_on_tax = 0)
                @php($add_ons_tax_cost = 0)
                @php($GST = 0)
                @foreach ($order->details as $detail)
                @if ($detail->product)
                @php($add_on_qtys = json_decode($detail['add_on_qtys'], true))
                @php($add_on_prices = json_decode($detail['add_on_prices'], true))
                @php($add_on_taxes = json_decode($detail['add_on_taxes'], true))

                <tr>
                    <td class="">
                        {{ $detail['quantity'] }}
                    </td>
                    <td class="">
                        <span style="word-break: break-all;">
                            {{ Str::limit($detail->product['name'], 200) }}</span><br>
                        @if (count(json_decode($detail['variation'], true)) > 0)
                        <strong><u>{{ translate('variation') }} : </u></strong>
                        @foreach (json_decode($detail['variation'], true) as $variation)
                        @if (isset($variation['name']) && isset($variation['values']))
                        <span class="d-block text-capitalize">
                            <strong>{{ $variation['name'] }} - </strong>
                        </span>
                        @foreach ($variation['values'] as $value)
                        <span class="d-block text-capitalize">
                            {{ $value['label'] }} :
                            <strong>{{ \App\CentralLogics\Helpers::set_symbol($value['optionPrice']) }}</strong>
                        </span>
                        @endforeach
                        @else
                        @if (isset(json_decode($detail['variation'], true)[0]))
                        @foreach (json_decode($detail['variation'], true)[0] as $key1 => $variation)
                        <div class="font-size-sm text-body">
                            <span>{{ $key1 }} : </span>
                            <span class="font-weight-bold">{{ $variation }}</span>
                        </div>
                        @endforeach
                        @endif
                        @break
                        @endif
                        @endforeach
                        @else
                        <div class="font-size-sm text-body">
                            <span>{{ translate('Price') }} : </span>
                            <span class="font-weight-bold">{{ \App\CentralLogics\Helpers::set_symbol($detail->price) }}</span>
                        </div>
                        @endif

                        @foreach (json_decode($detail['add_on_ids'], true) as $key2 => $id)
                        @php($addon = \App\Model\AddOn::find($id))
                        @if ($key2 == 0)
                        <strong><u>{{ translate('Addons') }} : </u></strong>
                        @endif

                        @if ($add_on_qtys == null)
                        @php($add_on_qty = 1)
                        @else
                        @php($add_on_qty = $add_on_qtys[$key2])
                        @endif

                        <div class="font-size-sm text-body">
                            <span>{{ $addon ? $addon['name'] : translate('addon deleted') }} : </span>
                            <span class="font-weight-bold">
                                {{ $add_on_qty }} x
                                {{ \App\CentralLogics\Helpers::set_symbol($add_on_prices[$key2]) }} <br>
                            </span>
                        </div>
                        @php($add_ons_cost += $add_on_prices[$key2] * $add_on_qty)
                        @php($add_ons_tax_cost += $add_on_taxes[$key2] * $add_on_qty)
                        @endforeach
                        <div class="font-size-sm text-body">
                            <span>{{ translate('Discount') }} : </span>
                            <span class="font-weight-bold">{{ \App\CentralLogics\Helpers::set_symbol($detail['discount_on_product'] * $detail['quantity']) }}</span>
                        </div>
                    </td>
                    <td style="width: 28%;padding-right:4px; text-align:right">
                        @php($amount = ($detail['price'] - $detail['discount_on_product']) * $detail['quantity'])
                        {{ \App\CentralLogics\Helpers::set_symbol($amount) }}
                    </td>
                </tr>
                @php($item_price += $amount)
                @php($total_tax += $detail['tax_amount'] * $detail['quantity'])
                @endif
                @endforeach
            </tbody>
        </table>
        <span>--------------------------------------------------------</span>
        <div class="row justify-content-md-end pt-2 pb-2">
            <div class="col-md-9 col-lg-9">
                <dl class="row text-right" style="color: black!important; padding: 0 10px;">
                    {{--<dt class="col-8">{{ translate('Items Price') }}:</dt>
                    <dd class="col-4">{{ \App\CentralLogics\Helpers::set_symbol($item_price) }}</dd>--}}
                    {{-- <dt class="col-8">{{ translate('Tax') }} / {{ translate('VAT') }}:</dt>
                    <dd class="col-4">{{ \App\CentralLogics\Helpers::set_symbol($total_tax + $add_ons_tax_cost) }}</dd> --}}
                    {{--<dt class="col-8">{{ translate('Addon Cost') }}:</dt>
                    <dd class="col-4">{{ \App\CentralLogics\Helpers::set_symbol($add_ons_cost) }}--}}
                        <hr>
                    </dd>

                    <dt class="col-8">
                        <h4>{{ translate('Subtotal') }}:</h4>
                    </dt>
                    @php($subtotal = $add_ons_cost + $item_price + $total_tax + $add_ons_tax_cost)
                    @php($GST = ($subtotal * 5) / 100)
                    <dd class="col-4">
                        <h4>{{ \App\CentralLogics\Helpers::set_symbol($subtotal) }}</h4>
                    </dd>


                    <dt class="col-8">{{ translate('Coupon Discount') }}:</dt>
                    <dd class="col-4">
                        {{ \App\CentralLogics\Helpers::set_symbol($order['coupon_discount_amount']) }}
                    </dd>
                    <dt class="col-8">{{ translate('VAT/TAX:') }}</dt>
                    <dd class="col-4">
                        {{ \App\CentralLogics\Helpers::set_symbol($GST) }}
                    </dd>
                    <dt class="col-8">
                        <h4>{{ translate('Total') }}:</h4>
                    </dt>
                    <dd class="col-4">
                        <h4>{{\App\CentralLogics\Helpers::set_symbol($subtotal - $order['coupon_discount_amount'] - $order['extra_discount'] + $GST)}}</h4>
                    </dd>
                </dl>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-between border-top">
            <span>{{ translate('Retain this copy for your records') }}</span>
        </div>
        <div class="d-flex flex-row justify-content-between border-top">

            <span>{{ translate('Merchant Copy') }}: {{ translate($order->payment_method) }} {{ translate('Sale') }}</span>
        </div>
        <span>--------------------------------------------------------</span>
        <h5 class="text-center pt-3">
            """{{ translate('THANK YOU FOR VISITING') }} {{ strtoupper(\App\Model\BusinessSetting::where(['key' => 'restaurant_name'])->first()->value) }}"""
        </h5>
        <span>--------------------------------------------------------</span>
    </div>


    <div style="display:none;" class="customer-copy-print">
        <div style="page-break-after: always; display:none;" class="page-break"></div>
        <div class="text-center pt-3 w-100">
            <h2 style="line-height: 1; font-size: 28px;">{{ \App\Model\BusinessSetting::where(['key' => 'restaurant_name'])->first()->value }}</h2>
            <h5 style="font-size: 16px;font-weight: lighter;line-height: 1">
                {{ \App\Model\BusinessSetting::where(['key' => 'address'])->first()->value }}
            </h5>
            <h5 style="font-size: 16px;font-weight: lighter;line-height: 1">
                {{ translate('Phone') }}
                : {{ \App\Model\BusinessSetting::where(['key' => 'phone'])->first()->value }}
            </h5>
            <h5 style="font-size: 16px;font-weight: lighter;line-height: 1">
                {{ translate('Branch: Main Branch') }}
            </h5>
        </div>
        <span>--------------------------------------------------------</span>
        <div class="row mt-3">
            <div class="col-6">
                <h5>{{ translate('Order ID') }} : {{ $order['id'] }}</h5>
            </div>
            <div class="col-6">
                <h5 style="font-weight: lighter">
                    {{ date('M d Y, h:i a', strtotime($order['created_at'])) }}
                </h5>
            </div>

            @if($order->admin)
            <div class="col-6">
                <h5 style="font-weight: lighter">
                    {{ translate('Server Name:') }} {{ $order->admin->f_name }} {{ $order->admin->l_name }}
                </h5>
            </div>
            @endif

            @if ($order->table_id)
            <div class="col-6">

                <h5>{{ translate('Table No :') }} :{{ $order->table->number ?? '' }}</h5>
            </div>
            @endif

            @if ($order->number_of_people)
            <div class="col-6">

                <h5>{{ translate('People :') }} : {{ $order->number_of_people }}</h5>

            </div>
            @endif
            @if ($order->customer)
            <div class="col-12">
                <span>--------------------------------------------------------</span>
                <h5>{{ translate('Customer Name') }} : {{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}
                </h5>
                <h5>{{ translate('Phone') }} : {{ $order->customer['phone'] }}</h5>
            </div>
            @endif
        </div>
        <h5 class="text-uppercase"></h5>
        <span>--------------------------------------------------------</span>
        <table class="table table-bordered mt-3 print-table" style="width: 98%">
            <!-- <thead>
            <tr>
                <th style="width: 10%">{{ translate('QTY') }}</th>
                <th class="">{{ translate('DESC') }}</th>
                <th style="text-align:right; padding-right:4px">{{ translate('Price') }}</th>
            </tr>
            </thead> -->

            <tbody>
                @php($item_price = 0)
                @php($total_tax = 0)
                @php($total_dis_on_pro = 0)
                @php($add_ons_cost = 0)
                @php($add_on_tax = 0)
                @php($add_ons_tax_cost = 0)
                @php($GST = 0)
                @foreach ($order->details as $detail)
                @if ($detail->product)
                @php($add_on_qtys = json_decode($detail['add_on_qtys'], true))
                @php($add_on_prices = json_decode($detail['add_on_prices'], true))
                @php($add_on_taxes = json_decode($detail['add_on_taxes'], true))

                <tr>
                    <td class="">
                        {{ $detail['quantity'] }}
                    </td>
                    <td class="">
                        <span style="word-break: break-all;">
                            {{ Str::limit($detail->product['name'], 200) }}</span><br>
                        @if (count(json_decode($detail['variation'], true)) > 0)
                        <strong><u>{{ translate('variation') }} : </u></strong>
                        @foreach (json_decode($detail['variation'], true) as $variation)
                        @if (isset($variation['name']) && isset($variation['values']))
                        <span class="d-block text-capitalize">
                            <strong>{{ $variation['name'] }} - </strong>
                        </span>
                        @foreach ($variation['values'] as $value)
                        <span class="d-block text-capitalize">
                            {{ $value['label'] }} :
                            <strong>{{ \App\CentralLogics\Helpers::set_symbol($value['optionPrice']) }}</strong>
                        </span>
                        @endforeach
                        @else
                        @if (isset(json_decode($detail['variation'], true)[0]))
                        @foreach (json_decode($detail['variation'], true)[0] as $key1 => $variation)
                        <div class="font-size-sm text-body">
                            <span>{{ $key1 }} : </span>
                            <span class="font-weight-bold">{{ $variation }}</span>
                        </div>
                        @endforeach
                        @endif
                        @break
                        @endif
                        @endforeach
                        @else
                        <div class="font-size-sm text-body">
                            <span>{{ translate('Price') }} : </span>
                            <span class="font-weight-bold">{{ \App\CentralLogics\Helpers::set_symbol($detail->price) }}</span>
                        </div>
                        @endif

                        @foreach (json_decode($detail['add_on_ids'], true) as $key2 => $id)
                        @php($addon = \App\Model\AddOn::find($id))
                        @if ($key2 == 0)
                        <strong><u>{{ translate('Addons') }} : </u></strong>
                        @endif

                        @if ($add_on_qtys == null)
                        @php($add_on_qty = 1)
                        @else
                        @php($add_on_qty = $add_on_qtys[$key2])
                        @endif

                        <div class="font-size-sm text-body">
                            <span>{{ $addon ? $addon['name'] : translate('addon deleted') }} : </span>
                            <span class="font-weight-bold">
                                {{ $add_on_qty }} x
                                {{ \App\CentralLogics\Helpers::set_symbol($add_on_prices[$key2]) }} <br>
                            </span>
                        </div>
                        @php($add_ons_cost += $add_on_prices[$key2] * $add_on_qty)
                        @php($add_ons_tax_cost += $add_on_taxes[$key2] * $add_on_qty)
                        @endforeach
                        <div class="font-size-sm text-body">
                            <span>{{ translate('Discount') }} : </span>
                            <span class="font-weight-bold">{{ \App\CentralLogics\Helpers::set_symbol($detail['discount_on_product'] * $detail['quantity']) }}</span>
                        </div>
                    </td>
                    <td style="width: 28%;padding-right:4px; text-align:right">
                        @php($amount = ($detail['price'] - $detail['discount_on_product']) * $detail['quantity'])
                        {{ \App\CentralLogics\Helpers::set_symbol($amount) }}
                    </td>
                </tr>
                @php($item_price += $amount)
                @php($total_tax += $detail['tax_amount'] * $detail['quantity'])
                @endif
                @endforeach
            </tbody>
        </table>
        <span>--------------------------------------------------------</span>
        <div class="row justify-content-md-end pt-2 pb-2">
            <div class="col-md-9 col-lg-9">
                <dl class="row text-right" style="color: black!important; padding: 0 10px;">
                    {{--<dt class="col-8">{{ translate('Items Price') }}:</dt>
                    <dd class="col-4">{{ \App\CentralLogics\Helpers::set_symbol($item_price) }}</dd>--}}
                    {{-- <dt class="col-8">{{ translate('Tax') }} / {{ translate('VAT') }}:</dt>
                    <dd class="col-4">{{ \App\CentralLogics\Helpers::set_symbol($total_tax + $add_ons_tax_cost) }}</dd> --}}
                    {{--<dt class="col-8">{{ translate('Addon Cost') }}:</dt>
                    <dd class="col-4">{{ \App\CentralLogics\Helpers::set_symbol($add_ons_cost) }}--}}
                        <hr>
                    </dd>

                    <dt class="col-8">
                        <h4>{{ translate('Subtotal') }}:</h4>
                    </dt>
                    @php($subtotal = $add_ons_cost + $item_price + $total_tax + $add_ons_tax_cost)
                    @php($GST = ($subtotal * 5) / 100)
                    <dd class="col-4">
                        <h4>{{ \App\CentralLogics\Helpers::set_symbol($subtotal) }}</h4>
                    </dd>


                    <dt class="col-8">{{ translate('Coupon Discount') }}:</dt>
                    <dd class="col-4">
                        {{ \App\CentralLogics\Helpers::set_symbol($order['coupon_discount_amount']) }}
                    </dd>
                    <dt class="col-8">{{ translate('VAT/TAX:') }}</dt>
                    <dd class="col-4">
                        {{ \App\CentralLogics\Helpers::set_symbol($GST) }}
                    </dd>
                    <dt class="col-8">
                        <h4>{{ translate('Total') }}:</h4>
                    </dt>
                    <dd class="col-4">
                        <h4>{{\App\CentralLogics\Helpers::set_symbol($subtotal - $order['coupon_discount_amount'] - $order['extra_discount'] + $GST)}}</h4>
                    </dd>
                </dl>
            </div>
        </div>
        <div class="d-flex flex-row justify-content-between border-top">
            <span>{{ translate('Retain this copy for your records') }}</span>
        </div>
        <div class="d-flex flex-row justify-content-between border-top">

            <span>{{ translate('Customer Copy') }}: {{ translate($order->payment_method) }} {{ translate('Sale') }}</span>
        </div>
        <span>--------------------------------------------------------</span>
        <h5 class="text-center pt-3">
            """{{ translate('THANK YOU FOR VISITING') }} {{ strtoupper(\App\Model\BusinessSetting::where(['key' => 'restaurant_name'])->first()->value) }}"""
        </h5>
        <span>--------------------------------------------------------</span>
    </div>
</div>