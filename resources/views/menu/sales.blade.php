@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body  table-responsive">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Transaction No.</th>
                <th>No. of Items</th>
                <th>Total</th>
                <th>Cashier</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $k => $t)
            <tr>
                <td>{{$t->transaction_id}}</td>
                <td>{{$totals['items'][$k]}}</td>
                <td>PHP {{$totals['sum'][$k]}}</td>
                <td>{{$t->last_name}}, {{$t->first_name}}</td>
                <td>{{$t->created_at}}</td>
                    @if($t->is_paid==0)
                    <td style="background: #ff4d4d">
                        <b style="color: white">Not yet Paid</b>
                    </td>
                    @elseif($t->is_paid==1)
                    <td style="background: #00e639">
                        <b style="color: white">Payment Received</b>
                    </td>
                    @endif
                <td>
                    <a href="javascript:void(0);" class="btn btn-sm btn-primary btnViewSales"><i class="fa fa-list-ul"></i></a>
                    @if($totals['is_active'][$k]==1)
                    <button class="btn btn-sm btn-danger btnDeleteSales foradminonly" onclick="deleteTransaction('{{$t->transaction_id}}');"><i class="fa fa-ban"></i></button>
                    @endif
                </td>
            </tr>
            @endforeach
</table>

    </div>
</div>

<div id="salesItemsModal" class="modal fade" role="dialog" state="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div id="salesItemsModalHeader" class="modal-header">
        <span></span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="frmSalesItems">
            <table width="100%">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Unit Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="tblSalesItems"></tbody>
            <div id="div_creditor"></div>    
            </table>
        </form>
      </div>
      <div class="modal-footer">
        <button id="btnAddItems" class="btn btn-primary">Add Items</button>
        <button id="btnCheckout" class="btn btn-success">Check Out</button>
        <button id="btnPrintSales" class="btn btn-warning">Print</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="paymentModal" class="modal fade" role="dialog" state="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div id="paymentModalHeader" class="modal-header">
        <span>Member Info:</span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <label>Total Amount</label>
        <div id="displayTotal"><h3>PHP 0.00</h3></div>
        <hr>
        <div class="form-group">
              <label for="pt">Select Payment Type</label>
              <select class="form-control" id="pt">
                <option value="1">Cash</option>
                <option id="opt_credit" value="2">Credit</option>
              </select>
            </div>
        <div id="divForCash">
            <label>Green Card Perk No.</label>
            <input type="number" class="form-control" id="displayCardNum" placeholder="XX"><br>
            <label>Amount (PHP)</label>
            <input type="number" class="form-control" id="displayPayment" placeholder="Amount Tendered">
            <hr>
            <label>Change</label>
            <div id="displayChange"><h3>PHP 0.00</h3></div>
        </div>
        <div id="divForCredit" hidden="true">
            <form id="frmCredit">
                <label for="credit_ln">Last Name</label>
                <input type="text" class="form-control" name="credit_ln" id="credit_ln" placeholder="Last Name"><br>
                <label for="credit_fn">First Name</label>
                <input type="text" class="form-control" name="credit_fn" id="credit_fn" placeholder="First Name"><br>
                <label for="credit_co">Mobile Number</label>
                <input type="text" class="form-control" name="credit_co" id="credit_co" placeholder="Phone Number"><br>
                <label for="credit_ad">Address/Agency</label>
                <input type="text" class="form-control" name="credit_ad" id="credit_ad" placeholder="Address/Agency/Company">
            </form>
        </div>
      </div>
      <div class="modal-footer">
        <center>
            <button id="btnPaySales" type="button" class="btn btn-success" hidden="true" >Submit</button>
            <button id="btnSetCredit" type="button" class="btn btn-primary" hidden="true">Save</button>
        </center>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="receiptModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="divModalReceipt">
        <table id="salesReceipt" width="100%">
            <tr>
                <td colspan="2"><center><b>GOOD VIBES CAFE BY GERONIMO</b></center></td>
            </tr>
            <tr>
                <td colspan="2"><center>Brgy. 10 Llanes St., Laoag City,</center></td>
            </tr>
            <tr>
                <td colspan="2"><center>Ilocos Norte, Philippines, 2900</center></td>
            </tr>
            <tr id="salesOrderNum">
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="2">Date: {{Carbon\Carbon::now()->toDateString()}} Time: {{Carbon\Carbon::now()->toTimeString()}}</td>
            </tr>
            <tr id="salesCashier">
                <td>Cashier</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%">
                        <thead>
                            <tr style="border-top: 1px solid;">
                                <td width="20%"><b>QTY</b></td>
                                <td width="50%"><b>ITEM</b></td>
                                <td width="30%"><b>TOTAL PRICE</b></td>
                            </tr>
                        </thead>
                        <tbody id="receiptSalesTable"></tbody>
                    </table>
                </td>
            </tr>
            <tr style="border-top: 1px solid;" id="salesNumItems">
                <td>Total Items: </td>
                <td></td>
            </tr>
            <tr id="salesTotal">
                <td>Grand Total: </td>
                <td></td>
            </tr>
            <tr id="salesDiscount">
                <td>Total Discount: (PHP 0.00)</td>
                <td></td>
            </tr>
            <tr id="salesCash">
                <td>Cash Tendered: </td>
                <td></td>
            </tr>
            <tr id="salesChange">
                <td>Change: </td>
                <td></td>
            </tr>
            <tr style="border-top: 1px solid;" id="salesCardNum">
                <td>Card Number:</td>
                <td></td>
            </tr>
            <tr id="salesCustomer">
                <td>Customer Name:</td>
                <td></td>
            </tr>
            <tr id="salesCustomerPoints">
                <td>Total Points:</td>
                <td></td>
            </tr>
            <tr style="border-top: 1px solid;">
                <td colspan="2"><center>Thank you and</center></td>
            </tr>
            <tr>
                <td colspan="2"><center>have a Good Vibes everyday!</center></td>
            </tr>
        </table>
      </div><!-- /.modal-body -->
      <div class="modal-footer">
        {{-- <center><button id="btnPrintReceipt" type="button" class="btn btn-warning" >Print</button></center> --}}
        <center><a id="btnprint" type="button" class="btn btn-warning" href="#">Print</a></center>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

@section('script')

<script type="text/javascript">
$(document).ready(function() {
    
    @if(!$b_isActive==1 && Auth::User()->user_level==1)
        $('.btnDeleteSales').prop('disabled',true);
    @endif

    var table = $('#example').DataTable( {
        "order": [
        [ 5, "asc" ],
        [ 4, "desc" ]
        ]
    });

    var totalSales = 0;
    var totalItems = 0;
    var totalDiscount = 0;
    var cash = 0;
    var cc = 0;
    var tid = 0;
    var salesObject = null;
    var cardNum = 0;

    $('#btnCheckout').on('click', function() {
        $('#salesItemsModal').modal('hide');
        $('#paymentModal').modal('show');
    });

    $('#btnAddItems').on('click', function() {
       document.location.href = '{{ url("sales/add/transaction") }}/'+tid;
    });

    $('.btnViewSales').on('click', function() {

        var data = table.row( $(this).closest('tr')).data();

        $('.tr_SalesItems').remove();

        tid = data[0];

        var a = document.getElementById('salesItemsModalHeader');
        var b = a.getElementsByTagName('span')[0];
        b.innerHTML = 'Transaction No. '+ tid;

        $.ajax ({
                url : '{{ url("getters/transactions/sales/items/get") }}'
                ,method : 'GET'
                ,data: { transaction_id:tid }
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){

                salesObject = response;
                totalSales = 0;
                totalItems = 0;
                $('#p_creditor').remove();
                
                if(salesObject[0]['is_paid']==1) {

                    $('#btnCheckout').prop('hidden',true);
                    $('#btnPrintSales').prop('hidden',false);  
                    $('#btnAddItems').prop('hidden',true);  
                    $('#opt_credit').prop('hidden',false);
                    
                } else {

                    if(salesObject[0]['is_paid']==2) {
                        $('#opt_credit').prop('hidden',true);
                        $('#div_creditor').append(
                            '<p id="p_creditor">Name: <b>'+ salesObject[0]['credit_last_name'] + ', ' + salesObject[0]['credit_first_name']+
                            ' </b><br>Contact No: <b>' + salesObject[0]['contact_no'] +
                            ' </b><br>Address/Agency/Company: <b>' + salesObject[0]['address'] + '</b></p>'
                        );
                    } else {
                    
                        $('#opt_credit').prop('hidden',false);
                        
                    }

                    $('#btnPrintSales').prop('hidden',true);
                    $('#btnCheckout').prop('hidden',false);
                    $('#btnAddItems').prop('hidden',false);  
                    
                }

                for (var i = 0; i < salesObject.length; i++) {

                    totalItems = totalItems + salesObject[i]['qty'];   
                    var totalPrice = salesObject[i]['unit_price'] * salesObject[i]['qty'];

                    var discountAmt = parseFloat(salesObject[i]['discount_amount']).toFixed(2);

                    totalDiscount = totalDiscount + discountAmt;

                    totalSales = (totalSales + totalPrice) - discountAmt;

                    var discountStatement = "";
                    if(discountAmt>0)
                        discountStatement = '<i>(Php'+parseFloat(discountAmt).toFixed(2)+' off)</i>';

                    var thisSalesTotal = totalPrice- discountAmt;

                    $('#tblSalesItems').append(
                    '<tr class="tr_SalesItems">'+
                    '<td>'+salesObject[i]['product_name']+discountStatement+'</td>'+
                    '<td>'+parseFloat(salesObject[i]['unit_price']).toFixed(2)+'</td>'+
                    '<td>'+salesObject[i]['qty']+'</td>'+
                    '<td align="right">'+parseFloat(thisSalesTotal).toFixed(2)+'</td>'+
                    '</tr>'
                    );

                }

                if(salesObject[0]['extra_charge_id']) {
                    
                    totalSales = totalSales + parseFloat(salesObject[0]['charge_amount']);
                    totalItems = totalItems + 1;

                    $('#tblSalesItems').append(
                    '<tr class="tr_SalesItems">'+
                    '<td>'+salesObject[0]['charge_name']+'</td>'+
                    '<td>'+parseFloat(salesObject[0]['charge_amount']).toFixed(2)+'</td>'+
                    '<td>1</td>'+
                    '<td align="right">'+parseFloat(salesObject[0]['charge_amount']).toFixed(2)+'</td>'+
                    '</tr>'
                    );
                } 

                $('#tblSalesItems').append(
                    '<tr class="tr_SalesItems">'+
                    '<td colspan="4" align="right" style="font-size:20px; border-top:1px solid"><b>Total: PHP '+parseFloat(totalSales).toFixed(2)+'</b></td>'+
                    '</tr>'
                );
                
                var paymentModalDivTotal = document.getElementById('displayTotal');
                var paymentModalH3Total = paymentModalDivTotal.getElementsByTagName('h3')[0];
                paymentModalH3Total.innerHTML = 'PHP ' + parseFloat(totalSales).toFixed(2);
                $('#salesItemsModal').modal('show');

              }).fail( function(response) {
                alert('Sorry there was an error in retrieving the data.');
              });
    });

    $('#displayPayment').change(function() {

            cash = $('#displayPayment').val();
            cc = cash-totalSales;

            if(cc>=0) {
                $('#btnPaySales').prop('hidden',false);
                
                var md = document.getElementById('displayChange');
                var md_change = md.getElementsByTagName('h3')[0];

                md_change.innerHTML = "PHP " + parseFloat(cc).toFixed(2);
            } else {
                alert('You have entered an invalid amount, try again!');
                $('#btnPaySales').prop('hidden',true);
            }


            /*
            var tr = document.getElementById('salesCash');
            var r_c = tr.getElementsByTagName('td')[1];
            r_c.innerHTML = parseFloat(c).toFixed(2);

            var tr = document.getElementById('salesChange');
            var r_cc = tr.getElementsByTagName('td')[1];
            r_cc.innerHTML = parseFloat(cc).toFixed(2);
            */
        });

    $('#displayCardNum').on('change',function() {
        
        var a = document.getElementById('paymentModalHeader');
        var b = a.getElementsByTagName('span')[0];

        cardNum = $('#displayCardNum').val();

            if(cardNum!=null) {
                $.ajax ({
                url : '{{ url("getters/green-perks/get") }}'
                ,method : 'GET'
                ,data: { card_num:cardNum }
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){
                
                if(response['status']==1) {
                    
                    var name = response['last_name'] + ", " + response['first_name'];

                    if(response['birthday']==1) {
                        alert('HAPPY BIRTHDAY '+name);
                        b.innerHTML = 'Member Info: ' + name +' <i class="fa fa-birthday-cake"></i>';
                    } else
                        b.innerHTML = 'Member Info: ' + name;

                    
                }
                else {
                    alert('Sorry, Green Card Perk Number is not valid.');
                    b.innerHTML = 'Member Info: ';
                    $('#displayCardNum').val('');
                    cardNum = 0;
                }


              }).fail( function(response) {
                alert('Sorry, Green Card Perk Number is not valid.');
                b.innerHTML = 'Member Info: ';
                $('#displayCardNum').val('');
              });
            }
        });

    $('#btnPaySales').on('click', function() {
            
              $.ajax ({
                url : '{{ url("sales/pay/save") }}'
                ,method : 'GET'
                ,data: {
                    transaction_id:tid,
                    c:cash,
                    card_num:cardNum,
                    total:totalSales
                }
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){
                
                printReceipt(response);
                
              }).fail( function(response) {
                alert('Sorry, there was an error in saving your record');
              });
    });

    $('#btnPrintSales').on('click', function() {
        window.open('{{ url("print/receipt") }}/'+tid,'_blank')
        //printReceipt(salesObject);
    });

    $('#receiptModal').on('hidden.bs.modal', function (e) {
        location.reload();  
    });

    $('#paymentModal').on('hidden.bs.modal', function (e) {
        $('#btnPaySales').prop('hidden',true);
    });

    $('#btnPrintReceipt').on('click', function() {
        printThis('divModalReceipt','Official Receipt');
    });

    $('#pt').change(function() {

        var v = $('#pt').val();

        if(v==1) {
            $('#divForCash').prop('hidden', false);
            $('#btnPaySales').prop('hidden', false);
            $('#divForCredit').prop('hidden', true);
            $('#btnSetCredit').prop('hidden', true);
        } else {

            $('#divForCash').prop('hidden', true);
            $('#btnPaySales').prop('hidden', true);
            $('#divForCredit').prop('hidden', false);
            $('#btnSetCredit').prop('hidden', false);
        }
    });

    $('#btnSetCredit').on('click', function() {

        var ln = $('#credit_ln').val();
        var fn = $('#credit_fn').val();
        var phone = $('#credit_co').val();
        var addr = $('#credit_ad').val();

        if(ln.length>0 && fn.length>0 && phone.length>0 && addr.length>0) {
            var frmCredit = $('#frmCredit').serialize();
            console.log(frmCredit)
            $.ajax ({
                url : '{{ url("credits/new/save?") }}' + frmCredit
                ,method : 'GET'
                ,data: { transaction_id:tid }
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){
                
                alert('New credit record has been saved!');
                location.reload();

              }).fail( function(response) {
                
                alert('Sorry, there was error, please try again!');
              });
        } else 
            alert('Please fill up all the fields!');

    });

    function printReceipt(jArray) {

        $('#btnprint').prop('href','{{ url("print/receipt") }}'+'/'+tid)
        var thisTotalItems = 0;
        var thisSalesTotal = parseFloat(totalSales) + parseFloat(totalDiscount);

        for (var i = 0; i < jArray.length; i++) {
            
            thisTotalItems = thisTotalItems + parseInt(jArray[i]['qty']);
            
            $('#receiptSalesTable').append(
                '<tr>'+
                '<td>'+jArray[i]['qty']+'</td>'+
                '<td>'+jArray[i]['product_name']+'</td>'+
                '<td>'+parseFloat((jArray[i]['unit_price']*jArray[i]['qty'])).toFixed(2)+'</td></tr>'
                );
        }

        if(jArray[0]['charge_name']!=null) {

            thisTotalItems = thisTotalItems + 1;

            $('#receiptSalesTable').append(
                '<tr>'+
                '<td>1</td>'+
                '<td>'+jArray[0]['charge_name']+'</td>'+
                '<td>'+parseFloat(jArray[0]['charge_amount']).toFixed(2)+'</td></tr>'
                );
        }
        
        var tr = document.getElementById('salesOrderNum');
        var on = tr.getElementsByTagName('td')[0];
        on.innerHTML = 'Transaction #'+tid;

        var tr = document.getElementById('salesCashier');
        var on = tr.getElementsByTagName('td')[0];
        on.innerHTML = 'Cashier: ' + toProperCase(jArray[0]['last_name'] + ', ' + jArray[0]['first_name']);

        var tr = document.getElementById('salesNumItems');
        var on = tr.getElementsByTagName('td')[0];
        on.innerHTML = 'Total Items: ' + thisTotalItems + ' item/s';

        var tr = document.getElementById('salesTotal');
        var on = tr.getElementsByTagName('td')[0];
        on.innerHTML = 'Grand Total: PHP '+ parseFloat(thisSalesTotal).toFixed(2);

        var tr = document.getElementById('salesDiscount');
        var on = tr.getElementsByTagName('td')[0];
        on.innerHTML = 'Total Discount: (PHP '+ parseFloat(totalDiscount).toFixed(2) + ')';

        var tr = document.getElementById('salesCash');
        var on = tr.getElementsByTagName('td')[0];
        on.innerHTML = 'Cash Tendered: PHP '+ parseFloat(jArray[0]['cash_tendered']).toFixed(2);

        var tr = document.getElementById('salesChange');
        var on = tr.getElementsByTagName('td')[0];
        on.innerHTML = 'Change: PHP '+ parseFloat(jArray[0]['cash_tendered'] - totalSales).toFixed(2);
        
        if(jArray[0]['perk_id']!=0) {

            var tr = document.getElementById('salesCardNum');
            var on = tr.getElementsByTagName('td')[0];
            on.innerHTML = 'Card Number: '+ jArray[0]['perk_id'];

            var tr = document.getElementById('salesCustomer');
            var on = tr.getElementsByTagName('td')[0];
            on.innerHTML = 'Customer Name: '+ toProperCase(jArray[0]['customer_last_name'] + ', ' + jArray[0]['customer_first_name']);

            var tr = document.getElementById('salesCustomerPoints');
            var on = tr.getElementsByTagName('td')[0];
            on.innerHTML = 'Total Points: '+ jArray[0]['total_points'];
        }
        
        $('#salesItemsModal').modal('hide');
        $('#paymentModal').modal('hide');
        $('#receiptModal').modal('show');
    }

});
 function deleteTransaction(id) {
        
        var c = confirm('Are you sure you want to delete this transactions?');
        if(c) {
            $.ajax ({
                url : '{{ url("sales/delete/this") }}' 
                ,method : 'GET'
                ,data: {id:id}
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){
                
                alert('Transaction has been deleted!');
                location.reload();

              }).fail( function(response) {
                alert('Sorry, there was an error deleting the data.');
                
              });
        }
    }
</script>
@endsection