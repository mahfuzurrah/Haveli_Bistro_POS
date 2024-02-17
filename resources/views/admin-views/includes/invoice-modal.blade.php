<div class="modal fade" id="print-invoice" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{translate('print')}} {{translate('invoice')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row" style="font-family: emoji;">
                <div class="col-md-12">
                    <center>
                        <button type="button" class="btn btn-primary non-printable" onclick="printDiv('printableArea', 'marchant')"> {{translate('Marchant Copy')}} </button>
                        <button type="button" class="btn btn-primary non-printable" onclick="printDiv('printableArea', 'customer')"> {{translate('Customer Copy')}} </button>
                        <button type="button" class="btn btn-primary non-printable" onclick="printDiv('printableArea', 'both')"> {{translate('Both Copy')}} </button>
                    </center>
                    <hr class="non-printable">
                </div>
                <div class="row" id="printableArea" style="margin: auto;">
                </div>
            </div>
        </div>
    </div>
</div>