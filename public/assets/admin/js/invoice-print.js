function print_invoice(order_id) {
    $.get({
        url: '/admin/pos/invoice/' + order_id,
        dataType: 'json',
        beforeSend: function () {
            $('#loading').show();
        },
        success: function (data) {
            console.log("success...")
            $('#print-invoice').modal('show');
            $('#printableArea').empty().html(data.view);
        },
        complete: function () {
            $('#loading').hide();
        },
    });
}

function printDiv(divName, copy = 'marchant') {
    document.querySelector('.page-break').style.display = 'none';

    if (copy == 'both') {
        $('.customer-copy-print').show();
        document.querySelector('.customer-copy-print').style.display = 'block';
        document.querySelector('.page-break').style.display = 'block';
    } else if (copy == 'customer') {
        document.querySelector('.customer-copy-print').style.display = 'block';
        document.querySelector('.marchant-copy-print').style.display = 'none';
    } else {
        document.querySelector('.customer-copy-print').style.display = 'none';
        document.querySelector('.marchant-copy-print').style.display = 'block';
    }

    if ($('html').attr('dir') === 'rtl') {
        $('html').attr('dir', 'ltr')
        var printContents = document.getElementById(divName).innerHTML;
        document.body.innerHTML = printContents;
        $('#printableAreaContent').attr('dir', 'rtl')
        window.print();
        $('html').attr('dir', 'rtl')
        location.reload();
    } else {
        var printContents = document.getElementById(divName).innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        location.reload();
    }
}