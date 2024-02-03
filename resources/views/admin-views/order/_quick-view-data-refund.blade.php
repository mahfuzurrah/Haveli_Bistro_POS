<div class="modal-header p-2">
    <h4 class="modal-title product-title"></h4>
    <button class="close call-when-done" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form action="{{route('admin.pos.customer-store')}}" method="post" id="customer-form">
        @csrf
        <div class="row pl-2">
            <div class="col-12 col-lg-12">
                <div class="form-group">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Product Qty</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>demo</td>
                                <td>
                                    <div class="container">
                                        <input type="button" onclick="decrementValue()" value="-" />
                                        <input type="text" name="quantity" value="1" maxlength="2" max="10" size="1" id="number" />
                                        <input type="button" onclick="incrementValue()" value="+" />
                                        </div>
                            </td>
                                <td>

                                    <button class="btn-danger"><i class="tio-delete"></i></button>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                    <label class="input-label">
                        {{translate('Total Amout')}}
                        <span class="input-label-secondary text-danger">*</span>
                    </label>
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-end">
            <button type="reset" class="btn btn-secondary mr-1">{{translate('reset')}}</button>
            <button type="submit" id="" class="btn btn-primary">{{translate('Refund')}}</button>
        </div>
    </form>
</div>
