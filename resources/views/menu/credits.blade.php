@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <table id="userTable" class="table table-striped table-bordered" style="width:100%"> 
        <thead>
            <tr>
                <th>Date</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Address</th>
                <th>Contact No.</th>
                <th>Total Credit</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if($arrCredits)
                @foreach($arrCredits['customers'] as $k => $c)
                <tr>
                    <td>{{$c->credit_date}}</td>
                    <td>{{$c->last_name}}</td>
                    <td>{{$c->first_name}}</td>
                    <td>{{$c->address}}</td>
                    <td>{{$c->contact_no}}</td>
                    <td>{{$arrCredits['sales'][$k]}}</td>
                    @if($c->date_paid==null)
                        <td style="background: #ffdf80">
                            <b style="color: white">Credit</b>
                        </td>
                    @else
                        <td style="background: #00e639">
                            <b style="color: white">Paid {{$c->date_paid}}</b>
                        </td>
                    @endif
                    <td>
                        <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="displaySales('{{$c->transaction_id}}');"><i class="fa fa-list-ul"></i></a>
                        @if($c->date_paid==null)
                        <button class="btn btn-sm btn-danger btnDeleteSales foradminonly" onclick="deleteTransaction('{{$c->transaction_id}}');"><i class="fa fa-ban"></i></button>
                        @endif
                    </td>
                </tr>
                @endforeach
            @endif
</table>

    </div>
</div>

<div id="editCustomerModal" class="modal fade" role="dialog" state="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="editUserForm" >
            <input type="text" id="useridInput" name="useridInput" hidden />
            <label>Card Number</label>
            <input type="number" class="form-control" name="cardNumInput" id="cardNumInput">
            <label>Last Name</label>
            <input type="text" class="form-control" name="lastnameInput" id="lastnameInput">
            <label>First Name</label>
            <input type="text" class="form-control" name="firstnameInput" id="firstnameInput">
            <label>Birthdate</label>
            <input type="date" class="form-control" name="bdayInput" id="bdayInput">
            <label>Contact Number</label>
            <input type="text" class="form-control" name="contactNumInput" id="contactNumInput">
        </form>
      </div>
      <div class="modal-footer">
                <button class="btn btn-success" id="saveUserButton" >Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
        @if($arrCredits)
            @if($c->date_paid==null)
                <button id="btnCheckout" class="btn btn-success">Check Out</button>
            @else
                <button id="btnPrintSales" class="btn btn-warning">Print</button>
            @endif
        @endif
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
                <input type="text" class="form-control" name="credit_ln" placeholder="Last Name"><br>
                <label for="credit_fn">First Name</label>
                <input type="text" class="form-control" name="credit_fn" placeholder="First Name"><br>
                <label for="credit_co">Mobile Number</label>
                <input type="text" class="form-control" name="credit_co" placeholder="Phone Number"><br>
                <label for="credit_ad">Address/Agency</label>
                <input type="text" class="form-control" name="credit_ad" placeholder="Address/Agency/Company">
            </form>
        </div>
      </div>
      <div class="modal-footer">
        <center>
            <button id="btnPaySales" type="button" class="btn btn-success" hidden="true">Submit</button>
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
        <center><button id="btnPrintReceipt" type="button" class="btn btn-warning" >Print</button></center>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section('script')

<script type="text/javascript">
$(document).ready(function() {
    var totalSales = 0;
    var totalItems = 0;
    var totalDiscount = 0;
    var tid = 0;
    var cardNum = 0;
    var cash = 0;

    var table = $('#userTable').DataTable({
        "order": [
        [ 6, "asc" ],
        [ 0, "desc" ]
        ]
    });

    $('#btnCheckout').on('click', function() {
        $('#salesItemsModal').modal('hide');
        $('#paymentModal').modal('show');
    });

    $('#btnPrintSales').on('click', function() {

        var a = document.getElementById('salesItemsModalHeader');
        var b = a.getElementsByTagName('span')[0];
        var tid = b.innerHTML.substring(16);
        printReceipt(tid);
    });

    $('#btnPaySales').on('click', function() {
            
        cash = $('#displayPayment').val();
        var paymentModalDivTotal = document.getElementById('displayTotal');
        var paymentModalH3Total = paymentModalDivTotal.getElementsByTagName('h3')[0];
        totalSales = paymentModalH3Total.innerHTML.substring(4);

        var a = document.getElementById('salesItemsModalHeader');
        var b = a.getElementsByTagName('span')[0];
        var tid = b.innerHTML.substring(16);

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
                
                printReceipt(tid);
                
              }).fail( function(response) {
                alert('Sorry, there was an error in saving your record');
              });
    });

     $('#displayCardNum').on('change',function() {
        
        var a = document.getElementById('paymentModalHeader');
        var b = a.getElementsByTagName('span')[0];

        cardNum = $('#displayCardNum').val();

            if(cardNum!=null) {
                $.ajax ({
                url :  '{{ url("getters/green-perks/get") }}' 
                ,method : 'GET'
                ,data: {card_num:cardNum}
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

     $('#displayPayment').change(function() {

            cash = $('#displayPayment').val();
            cc = parseFloat(cash)-parseFloat(totalSales);

            if(cc>=0) {

                $('#btnPaySales').prop('hidden',false);
                var paymentModalDivTotal = document.getElementById('displayTotal');
                var paymentModalH3Total = paymentModalDivTotal.getElementsByTagName('h3')[0];
                totalSales = paymentModalH3Total.innerHTML.substring(4);
                
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

     $('#receiptModal').on('hidden.bs.modal', function (e) {
        location.reload();  
    });

     $('#paymentModal').on('hidden.bs.modal', function (e) {
        $('#btnPaySales').prop('hidden',true);
    });
});

function displaySales(id) {

    $('.tr_SalesItems').remove();
    
        var a = document.getElementById('salesItemsModalHeader');
        var b = a.getElementsByTagName('span')[0];
        b.innerHTML = 'Transaction No. '+ id;

        $.ajax ({
                url : '{{ url("getters/transactions/sales/items/get") }}'
                ,method : 'GET'
                ,data: {transaction_id:id}
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){

                totalSales = 0;
                totalItems = 0;
                totalDiscount = 0;
                
                $('#salesItemsModal').modal('show');
              
                $('#p_creditor').remove();
                
                    if(response[0]['is_paid']==2) {
                        $('#opt_credit').prop('hidden',true);
                        $('#div_creditor').append(
                            '<p id="p_creditor">Name: <b>'+ response[0]['credit_last_name'] + ', ' + response[0]['credit_first_name']+
                            ' </b><br>Contact No: <b>' + response[0]['contact_no'] +
                            ' </b><br>Address/Agency/Company: <b>' + response[0]['address'] + '</b></p>'
                        );
                    } 


                for (var i = 0; i < response.length; i++) {

                    totalItems = totalItems + response[i]['qty'];   
                    var totalPrice = response[i]['unit_price'] * response[i]['qty'];

                    var discountAmt = parseFloat(response[i]['discount_amount']).toFixed(2);

                    totalDiscount = totalDiscount + discountAmt;

                    totalSales = (totalSales + totalPrice) - discountAmt;

                    var discountStatement = "";
                    if(discountAmt>0)
                        discountStatement = '<i>(Php'+parseFloat(discountAmt).toFixed(2)+' off)</i>';

                    var thisSalesTotal = totalPrice- discountAmt;

                    $('#tblSalesItems').append(
                    '<tr class="tr_SalesItems">'+
                    '<td>'+response[i]['product_name']+discountStatement+'</td>'+
                    '<td>'+parseFloat(response[i]['unit_price']).toFixed(2)+'</td>'+
                    '<td>'+response[i]['qty']+'</td>'+
                    '<td align="right">'+parseFloat(thisSalesTotal).toFixed(2)+'</td>'+
                    '</tr>'
                    );

                }

                if(response[0]['extra_charge_id']) {
                    
                    totalSales = totalSales + parseFloat(response[0]['charge_amount']);
                    totalItems = totalItems + 1;

                    $('#tblSalesItems').append(
                    '<tr class="tr_SalesItems">'+
                    '<td>'+response[0]['charge_name']+'</td>'+
                    '<td>'+parseFloat(response[0]['charge_amount']).toFixed(2)+'</td>'+
                    '<td>1</td>'+
                    '<td align="right">'+parseFloat(response[0]['charge_amount']).toFixed(2)+'</td>'+
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
                
              }).fail( function(response) {
                alert('Sorry there was an error in retrieving the data.');
              });
}

function printReceipt(id) {

        $.ajax ({
                url : '{{ url("getters/transactions/sales/items/get") }}'
                ,method : 'GET'
                ,data: { transaction_id:id }
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){

                jArray = response;

                var tid = jArray[0]['transaction_id'];
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
                        
              }).fail( function(response) {
                alert('Sorry there was an error in retrieving the data.');
              });
   
    }
    function deleteTransaction(id) {
        
        var c = confirm('Are you sure you want to delete this transactions?');
        if(c) {
            $.ajax ({
                url : '{{ url("sales/delete/this") }}'
                ,method : 'GET'
                ,data: { id:id }
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