<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use App\Model\Order;
use App\Model\Table;
use App\Models\User;
use App\Model\Branch;
use App\Model\Product;
use App\Model\Category;
use App\Model\DeliveryMan;
use App\Model\OrderDetail;
use App\Model\Notification;
use Illuminate\Http\Request;
use App\Model\ProductByBranch;
use App\Http\Controllers\Controller;

class RefundController extends Controller
{
    public function __construct(
        private User            $user,
        private Table           $table,
        private Admin           $admin,
        private Branch          $branch,
        private Product         $product,
        private Category        $category,
        private ProductByBranch $product_by_branch,
        private Order           $order,
        private OrderDetail     $order_detail,
        private Notification    $notification,
        private DeliveryMan     $delivery_man
    ){}

    public function refund_view(Request $request): JsonResponse
    {
        $orders = $this->order->where('order_status','hold')->get();
dd(1);
        return response()->json([
            'success' => 1,
            'view' => view('admin-views.pos._quick-view-data-hold', compact('orders'))->render(),
        ]);
    }

    public function refundAddToCart(Request $request): JsonResponse
    {

   $order = $this->order->find($request->id);

  foreach($order->details as $product){
   $data = array();
   $data['id'] = $product->product_id;
   $str = '';
   $variations = [];
   $price = 0;
   $addon_price = 0;
   $addon_total_tax = 0;
   $variation_price = 0;

   $branch_product = $this->product_by_branch->where(['product_id' => $product->product_id, 'branch_id' => session()->get('branch_id')])->first();

   $branch_product_price = 0;
   $discount_data = [];

   if (isset($branch_product)) {
       $branch_product_variations = $branch_product->variations;

       if ($request->variations && count($branch_product_variations)) {
           foreach ($request->variations as $key => $value) {

               if ($value['required'] == 'on' && !isset($value['values'])) {
                   return response()->json([
                       'data' => 'variation_error',
                       'message' => translate('Please select items from') . ' ' . $value['name'],
                   ]);
               }
               if (isset($value['values']) && $value['min'] != 0 && $value['min'] > count($value['values']['label'])) {
                   return response()->json([
                       'data' => 'variation_error',
                       'message' => translate('Please select minimum ') . $value['min'] . translate(' For ') . $value['name'] . '.',
                   ]);
               }
               if (isset($value['values']) && $value['max'] != 0 && $value['max'] < count($value['values']['label'])) {
                   return response()->json([
                       'data' => 'variation_error',
                       'message' => translate('Please select maximum ') . $value['max'] . translate(' For ') . $value['name'] . '.',
                   ]);
               }
           }
           $variation_data = Helpers::get_varient($branch_product_variations, $request->variations);
           $variation_price = $variation_data['price'];
           $variations = $request->variations;

       }

       $branch_product_price = $branch_product['price'];
       $discount_data = [
           'discount_type' => $branch_product['discount_type'],
           'discount' => $branch_product['discount']
       ];
   }

   $price = $branch_product_price + $variation_price;
   $data['variation_price'] = $variation_price;

   $discount_on_product = Helpers::discount_calculate($discount_data, $price);

   $data['variations'] = $variations;
   $data['variant'] = $str;
   $data['quantity'] = $product->quantity;
   $data['price'] = $price;
   $data['name'] = $product->product->name;
   $data['discount'] = $discount_on_product;
   $data['image'] = $product->product->image;
   $data['add_ons'] = [];
   $data['add_on_qtys'] = [];
   $data['add_on_prices'] = [];
   $data['add_on_tax'] = [];

   if ($request['addon_id']) {
       foreach ($request['addon_id'] as $id) {
           $addon_price += $request['addon-price' . $id] * $request['addon-quantity' . $id];
           $data['add_on_qtys'][] = $request['addon-quantity' . $id];

           $add_on = AddOn::find($id);
           $data['add_on_prices'][] = $add_on['price'];
           $add_on_tax = ($add_on['price'] * $add_on['tax']/100);
           $addon_total_tax += (($add_on['price'] * $add_on['tax']/100) * $request['addon-quantity' . $id]);
           $data['add_on_tax'][] = $add_on_tax;
       }
       $data['add_ons'] = $request['addon_id'];
   }

   $data['addon_price'] = $addon_price;
   $data['addon_total_tax'] = $addon_total_tax;

   if ($request->session()->has('cart')) {
       $cart = $request->session()->get('cart', collect([]));
       $cart->push($data);
   } else {
       $cart = collect([$data]);
       $request->session()->put('cart', $cart);
   }
}
    }
}
