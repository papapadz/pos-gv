@extends('layouts.app')

@section('styles')
<style type="text/css">
    img.prod_pic_menu {
        height: 8rem;
        width: 8rem;
    }

    .frm_sales_td_rm {
        width: 10%;
        padding-bottom: 10px;
    }

    .frm_sales_td_name {
        text-align: left;
        padding-right: 10px;
    }

    td.frm_sales_td_name, td.frm_sales_td_price, td.frm_sales_td_qty, td.frm_sales_td_total {
        font-size: 16px;
    }

    .frm_sales_td_price {
        text-align: left;
    }

    .frm_sales_td_qty {
        width: 5%;
        text-align: center;
    }

    td.frm_sales_td_qty_btn {
        width: 5%;
        text-align: center;
    }

    th.frm_sales_td_total, td.frm_sales_td_total {
        text-align: right;
    }

    #displayTotal, #displayChange {
        float: right;
    }
</style>
@endsection

@section('content')

@if(session('transaction_id'))
<div class="alert alert-primary" role="alert">
 <input type="number" id="inputTransID" value="{{session('transaction_id')}}" hidden="true">
 <i class="fa fa-cart-plus"></i> Adding Items on <b>Transaction No. {{session('transaction_id')}}</b>
</div>
@endif
<div class="row">
    <!-- SALES MENU -->
    <div class="col-md-5">
        <div class="card">
          <div class="card-header">
            <div class="btn-group" role="group" aria-label="...">
                <button type="button" id="btnDineIn" class="btn btn-success"><i class="fa fa-utensils"></i> Dine In</button>
                @if(!session('transaction_id'))
                <button type="button" id="btnTakeOut" class="btn btn-default"><i class="fa fa-box"></i> Take Out</button>
                @endif
            </div>
            <button style="float: right;" type="button" id="btnDiscount" class="btn btn-warning"><i class="fa fa-tags"></i> Discount</button>
          </div>
          
          <div class="card-body" style="border-bottom: 1px solid;">
            
            <form id="frm_sales">
                <table class="table-striped" id="div_frm_sales" width="100%"></table>
                <div id="discountDiv"></div>
            </form>

          </div>
            <table style="margin: 10px; width: 80%;">
                <tr id="level1">
                    <td width="30%">Subtotal</td>
                    <td width="30%" id="subtotal">0.00</td>
                    <td>0 item</td>
                </tr>   
                <tr id="level3">
                    <td>Discount</td>
                    <td>(0.00)</td>
                    <td></td>
                </tr>
                <tr id="level4">
                    <td>Total</td>
                    <td colspan="2">0.00</td>
                </tr>
            </table>
        <div class="card-footer" style="border-top: 1px solid;">
            <button id="btnCancel" class="btn btn-lg btn-danger">Cancel</button>
            @if(session('transaction_id'))
              <button data-toggle="modal" class="btn btn-lg btn-success" id="btnSaveAddSales">Add Items</button>
            @else
              <button data-toggle="modal" class="btn btn-lg btn-success" id="btnSaveSales">Submit</button>
            @endif
        </div>  
        </div>
    </div>
    <!-- END SALES MENU -->

    <!-- PRODUCT MENU -->
    <div class="col-md-7">
        <div class="card">
          <div class="card-body">

            <nav>
              <div class="nav nav-tabs" id="nav-tab" role="tablist" style="margin-bottom: 1rem;">
                @foreach($cat as $c)

                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#cat{{$c->category_id}}" role="tab" aria-controls="nav-contact" aria-selected="false">{{$c->category}}</a>
                @endforeach
                <!--
                <a class="nav-item nav-link active" id="nav-profile-tab" data-toggle="tab" href="#pasta" role="tab" aria-controls="nav-profile" aria-selected="false">Pasta</a>
                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#drinks" role="tab" aria-controls="nav-contact" aria-selected="false">Drinks</a>
                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#sandwiches" role="tab" aria-controls="nav-contact" aria-selected="false">Sandwiches</a>
                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#desserts" role="tab" aria-controls="nav-contact" aria-selected="false">Desserts</a>
                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#fooditinerary" role="tab" aria-controls="nav-contact" aria-selected="false">Food Itinerary</a>
                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#mercato" role="tab" aria-controls="nav-contact" aria-selected="false">Mercato</a>
                -->
              </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
              
              @foreach($cat as $k => $c)
                
                @if($k==0)
                <div class="tab-pane fade show active" id="cat{{$c->category_id}}" role="tabpanel" aria-labelledby="nav-profile-tab">
                @else
                <div class="tab-pane fade" id="cat{{$c->category_id}}" role="tabpanel" aria-labelledby="nav-profile-tab">
                @endif

                @forelse($arrCat[$k] as $p)
                  <a href="javascript:void(0)">
                            <img class="rounded img-fluid mx-auto prod_pic_menu" id="{{$p->product_id}}" data-toggle="tooltip" data-placement="bottom" title="{{$p->product_name}} - PHP {{$p->unit_price}}" src="{{$p->img_file}}">
                  </a>
                @empty
                There are no Items here right now
                @endforelse
                
              </div>
                
              @endforeach
            </div>
          </div>
        </div>
    </div>
    <!-- END PRODUCT MENU -->
</div>

<div id="extraChargeModal" class="modal fade" role="dialog" state="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="frmExtraCharge">
            <label>Additional Charge: </label>
            <input type="text" class="form-control" id="extraChargeName" placeholder="What do you call this additional Charge?">
            <label>Amount (PHP): </label>
            <input type="number" class="form-control" id="extraChargeAmt" placeholder="How much?">
        </form>
      </div>
      <div class="modal-footer">
        <center><button id="btnAddExtraCharge" type="button" class="btn btn-primary" >Add</button></center>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="discountModal" class="modal fade" tabindex="-1" state="display:none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <table width="100%">
          <thead>
            <tr>
              <th>Item</th>
              <th>Qty</th>
              <th>Total Price</th>
              <th>Discount Type</th>
              <th>Amount (PHP)</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="tblListDiscount"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <center><button id="btnAddDiscount" type="button" class="btn btn-primary" >Close</button></center>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="setDiscountModal" class="modal fade" role="dialog" state="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div id="name" class="modal-body">
        
      </div>
      <div class="modal-footer">
        <center><button type="button" class="btn btn-primary" >Save</button></center>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section('script')
<script type="text/javascript">
    
    var items = new Array();
    var discountedItems = new Array();
    var items_subtotal = 0.00;
    var items_num = 0;
    //var items_tax = 0.00;
    var items_total = 0.00;
    var isDine = 1;
    var discountPrice = 0;

    $(document).ready(function() {

        $('#div_frm_sales').append(
            "<thead><tr>"+
            "<th></th>"+
            "<th class='frm_sales_td_name'>Product Name</th>"+               
            "<th class='frm_sales_td_price'>Unit Price</th>"+
            "<th colspan='3' class='frm_sales_td_qty'>Qty</th>"+
            "<th class='frm_sales_td_total'>Total</th></tr></thead>"
        );

        $('img.prod_pic_menu').on('click', function() {
            
            var id = $(this).attr('id');

            if(checkItem(id)==0) {
                
                items.push(id);

                $.ajax ({
                  url : '{{ url("getters/product/get") }}'
                  ,method : 'GET'
                  ,data: { id:id }
                  ,cache : false
                  ,beforeSend:function() {
                  //$('#loadModal').modal({ backdrop: 'static' });
                  }
                }).done( function(response){
                  //alert(response);
                  //$('#frm_dt').append(response);
                  var jsondata = JSON.parse(response);
                  var pid = jsondata['product_id'];
                  var up = parseFloat(jsondata['unit_price']).toFixed(2);
                  
                  setSubtotal(up,1);

                  $('#div_frm_sales').append(
                    "<tr id='tr_"+pid+"'>"+
                    "<td>"+
                    //"<a class='btn btn-sm btn-danger btn_remove' href='javascript:void(0)' onClick=removeItem('"+pid+"','"+up+"')><i class='fa fa-times'></i></a>
                    "</td>"+
                    "<td class='frm_sales_td_name'>"+jsondata['product_name']+"</td>"+
                    "<td class='frm_sales_td_price' align='right'>"+up+"<span id='discount_span_"+pid+"'><span></td>"+
                    "<td class='frm_sales_td_qty_btn'><a class='btn btn-sm btn-danger' href='javascript:void(0)' onClick=subQty('"+pid+"','"+up+"')><i class='fa fa-minus'></a></td>"+
                    "<td class='frm_sales_td_qty'>1</td>"+
                    "<td class='frm_sales_td_qty_btn'><a class='btn btn-sm btn-success' href='javascript:void(0)' onClick=addQty('"+pid+"','"+up+"')><i class='fa fa-plus'></a></td>"+
                    "<td class='frm_sales_td_total'>"+up+"</td>"+
                    "<td hidden='true'><input class='form-control' type='number' name='item[id][]' value='"+pid+"' / hidden='true'></td>"+
                    "<td hidden='true'><input class='form-control' type='number' name='item[price][]' value='"+jsondata['unit_price_id']+"' / hidden='true'></td>"+
                    "<td hidden='true'>"+
                    "<input class='form-control' id='qty_"+pid+"' type='number' name='item[qty][]' value='1' hidden='true'/>"+
                    "<input type='number' name='discount[did][]' id='frm_discount_id_"+pid+"' value=0 hidden='true'/>"+
                    "<input type='number' name='discount[amt][]' id='frm_discount_amt_"+pid+"' value=0 hidden='true'/>"+
                    "</td>"+
                    "</tr>"
                  );

                  $('#tblListDiscount').append(
                    "<tr id='discount_tr_"+pid+"'>"+
                    "<td>"+jsondata['product_name']+"</td>"+
                    "<td id='discount_qty_"+id+"'>1</td>"+
                    "<td id='discount_total_price_"+id+"'>"+up+"</td>"+
                    "<td><select class='custom-select' id='select_discount_"+pid+"' onchange='getVal(this,"+pid+")'>"+
                    "<option selected value='0'>None</option>"+
                    @foreach($promoList as $promo)
                    '<option class="optionPromos" value="{{$promo->promo_id}}">{{$promo->promo_name}}</option>'+
                    @endforeach
                    "</select></td>"+
                    "<td><input id='discount_input_"+pid+"' disabled='true' class='form-control' type='number' value='0'/></td>"+
                    "<td><button onClick='saveDiscount("+pid+")' id='discount_btn_save_"+id+"' hidden='true' class='btn btn-sm btn-success'><i class='fa fa-check'></i></button>"+
                    "<button onClick='deleteDiscount("+pid+")' id='discount_btn_clear_"+id+"' hidden='true' class='btn btn-sm btn-danger'><i class='fa fa-trash'></i></button></td>"+
                    "</tr>"
                    );

                }).fail( function(response) {
                  alert('Sorry, there was an error in your request.');
                });
            } else {
                alert('item is already in the cart!');
            }            
        });


        $('#btnSaveSales').on('click', function() {

          var thisform = $('#frm_sales').serialize();


              $.ajax ({
                url : '{{ url("sales/new/save") }}'
                ,method : 'GET'
                ,data: thisform
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){

                alert('Order has been saved!');
                location.reload(); 
                //printReceipt(response);
                
              }).fail( function(response) {
                alert('Sorry, there was an error in saving your record');
              });
        });


        $('#btnSaveAddSales').on('click', function() {

          var thisform = $('#frm_sales').serialize();

          var tid = $('#inputTransID').val();
              $.ajax ({
                url : '{{ url("sales/add/items/save?") }}'+thisform
                ,method : 'GET'
                ,data: { tid:tid }
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){

                alert('Order has been saved!');
                location.reload(); 
                //printReceipt(response);
                
              }).fail( function(response) {
                alert('Sorry, there was an error in saving your record');
              });
        });

    $('#btnDineIn').on('click', function(){
        setDineIn();
    });

    $('#btnTakeOut').on('click', function(){
        var e = document.getElementById('tr_extraCharge');
        if(e==null) {
            $('#btnDineIn').prop('class','btn btn-default');
            $('#btnTakeOut').prop('class','btn btn-success');
            $isDine = 0;
            $('#extraChargeModal').modal('show');
        }
    });

    $('#extraChargeModal').on('hidden.bs.modal', function (e) {
        var e = document.getElementById('tr_extraCharge');
        if(e==null)
            setDineIn();
    });

    $('#btnAddExtraCharge').on('click', function() {

        var name = $('#extraChargeName').val();
        var amt = parseFloat($('#extraChargeAmt').val()).toFixed(2);
                $('#div_frm_sales').append(
                    "<tr id='tr_extraCharge'>"+
                    "<td></td>"+
                    "<td class='frm_sales_td_name'>"+name+"</td>"+
                    "<td class='frm_sales_td_price'>PHP "+amt+"</td>"+
                    "<td class='frm_sales_td_qty_btn'></td>"+
                    "<td class='frm_sales_td_qty'>1</td>"+
                    "<td class='frm_sales_td_qty_btn'></td>"+
                    "<td class='frm_sales_td_total'>"+amt+"</td>"+
                    "<td hidden='true'><input class='form-control' type='text' name='extraChargeName' value='"+name+"' / hidden='true'></td>"+
                    "<td hidden='true'><input class='form-control' type='number' name='extraChargeAmt' value='"+amt+"' hidden='true'/></td>"+
                    "</tr>"
                  );
            
            $('#extraChargeModal').modal('hide');
            setSubtotal(amt,1);
        });

    

    $('#btnDiscount').on('click', function() {
        
        /*
        if(discountPrice<=0) {
            $('#discountModal').modal('show');
            
        } else {
            var result = confirm("Want to change/remove the discount?");
            if (result) {
                getDiscountPrice(0);
                $('#discountModal').modal('show');
            }   
        }*/
        
        $('#discountModal').modal('show');
    });

    $('#btnAddDiscount').on('click', function() {
        
        $('#discountModal').modal('hide');
        
    });

    $('a.bg-warning').on('click', function() {

      alert('adasd');
    });

    $('#btnCancel').on('click', function() {

      var c = confirm('Are you sure you want to cancel?');
      
      if(c) {
        location.reload();  
      }
      
    });

});

    function checkItem(x) {
        if(items.indexOf(x)<0)
            return 0;
        else
            return 1;
    }

    function removeItem(x, y) {
        
        var i = '#tr_'+x;
        var j = '#qty_'+x;
        var k = '#discount_tr_'+x;
        var qty = $(j).val();
        var price = y*qty;

        var d = $('#discount_input_'+x).val();
        discountPrice = discountPrice - d;
        
        items.pop(items.indexOf(x));
        $(i).remove();
        $(k).remove();
    
        items_num = items_num - parseInt(qty);
        items_subtotal = items_subtotal - parseFloat(price);
        items_total = items_subtotal - parseFloat(discountPrice);
        display();
    }

    function addQty(x, y) {

        setSubtotal(y,1);

        var i = '#qty_'+x;

        var j = parseFloat($(i).val()) + 1;
        $(i).val(''+j);

        var tr = document.getElementById('tr_'+x);
        var qty = tr.getElementsByTagName('td')[4];
        qty.innerHTML = j;

        var a = '#tot_'+x;
        var b = parseFloat(j*y).toFixed(2);
        $(a).val(''+b);

        var tot = tr.getElementsByTagName('td')[6];
        tot.innerHTML = b;

        //var tr = document.getElementById('discount_tr_'+x);
        //var qty = tr.getElementsByTagName('td')[1];
        //qty.innerHTML = j;

        //var amt = tr.getElementsByTagName('td')[2];
        //amt.innerHTML = b;
        
        var tr = document.getElementById('discount_tr_'+x);
        var qty = tr.getElementsByTagName('td')[1];
        qty.innerHTML = j;

        var tr = document.getElementById('discount_tr_'+x);
        var tot = tr.getElementsByTagName('td')[2];
        tot.innerHTML = b;
    }

    function subQty(x, y) {

        var i = '#qty_'+x;
        var j = parseInt($(i).val()) - 1;
        
        if(j>0) {
          
          setSubtotal(-y,-1);

          $(i).val(''+j);

          var tr = document.getElementById('tr_'+x);
          var qty = tr.getElementsByTagName('td')[4];
          qty.innerHTML = j;

          var a = '#tot_'+x;
          var b = parseFloat(j*y).toFixed(2);
          $(a).val(''+b);

          var tot = tr.getElementsByTagName('td')[6];
          tot.innerHTML = b;

          //var tr = document.getElementById('discount_tr_'+x);
          //var qty = tr.getElementsByTagName('td')[1];
          //qty.innerHTML = j;

          //var amt = tr.getElementsByTagName('td')[2];
          //amt.innerHTML = b;
        } else 
          alert('Quantity cannot be zero!');

    }

    function setSubtotal(p,q) {

        items_num = items_num + parseInt(q);
        items_subtotal = items_subtotal + parseFloat(p);
        items_total = items_subtotal - parseFloat(discountPrice);
        
        display();
    }

    function display() {

        /***display NO. OF ITEMS***/
        var tr = document.getElementById('level1');
        var st = tr.getElementsByTagName('td')[1];
        var ni = tr.getElementsByTagName('td')[2];

        st.innerHTML = items_subtotal.toFixed(2);
        ni.innerHTML = items_num + " item/s";

        //var tr = document.getElementById('salesNumItems');
        //var r_ni = tr.getElementsByTagName('td')[1];
        //r_ni.innerHTML = items_num;

        //var tr = document.getElementById('salesVatSales');
        //var r_st = tr.getElementsByTagName('td')[1];
        //r_st.innerHTML = items_subtotal.toFixed(2);
        /************/

        /***display TAX***/
        //var tr = document.getElementById('level2');
        //var tx = tr.getElementsByTagName('td')[1];

        //var tr = document.getElementById('salesVAT');
        //var r_tx = tr.getElementsByTagName('td')[1];

        //tx.innerHTML = parseFloat(items_tax).toFixed(2);
        //r_tx.innerHTML = parseFloat(items_tax).toFixed(2);
        /*********/

        /***display DISCOUNT***/
        var tr = document.getElementById('level3');
        var dc = tr.getElementsByTagName('td')[1];

        dc.innerHTML = "(" + parseFloat(discountPrice).toFixed(2) + ")";
        /*********/

        /***display GRAND TOTAL***/
        var tr = document.getElementById('level4');
        var gt = tr.getElementsByTagName('td')[1];

        gt.innerHTML = parseFloat(items_total).toFixed(2);        

        //var md = document.getElementById('displayTotal');
        //var md_total = md.getElementsByTagName('h3')[0];

        //md_total.innerHTML = "PHP " + parseFloat(items_total).toFixed(2);

        //var tr = document.getElementById('salesTotal');
        //var r_gt = tr.getElementsByTagName('td')[1];
        //r_gt.innerHTML = parseFloat(items_total).toFixed(2);
        /***********/
    }

    function setDineIn() {

        var e = document.getElementById('tr_extraCharge');
        if(e!=null) {
            var amt = parseFloat($('#extraChargeAmt').val()).toFixed(2);

            setSubtotal(amt, 1);

            //items_num = items_num - 1;
            //items_total = (items_total - amt) - parseFloat(discountPrice);
            //items_tax = items_total*0.12;
            //items_subtotal = items_total;
            
            $('#tr_extraCharge').remove();
           
        }
        $('#btnTakeOut').prop('class','btn btn-default');
        $('#btnDineIn').prop('class','btn btn-success');
        $isDine = 1;
    }

    /*
    function getDiscountPrice(x, id) {

        var dType = $('select_discount_'+id).val();
        var dAmount = x.value;

        if(dType!=='0')
          discountPrice = parseFloat(discountPrice) + parseFloat(dAmount)

        setSubtotal(0,0);
    }

    
    function functionSetDiscount(x) {

      var tr = document.getElementById('discount_tr_'+x);
      var qty = tr.getElementsByTagName('td')[2];
        
      alert(qty.innerHTML);
    }*/

    function getVal(sel, id) {

      var v = sel.value;
      var inputId = '#discount_input_'+id;
      
      if(v ==='0') {
        
        deleteDiscount(id);
      } else {

        $('#discount_btn_save_'+id).prop('hidden',false);
        $(inputId).prop('disabled', false);
        
      }
      
    }

    function saveDiscount(id) {

      var dType = $('#select_discount_'+id).val();
      var dAmount = $('#discount_input_'+id).val();

      discountPrice = discountPrice + parseFloat(dAmount);
      items_subtotal = items_total;
      items_total = items_subtotal - parseFloat(discountPrice);
      display();
      $('#discount_span_'+id).append(' <i id="icon_span_'+id+'" style="color:orange;" class="fa fa-tag"></i>');
      $('#discount_btn_save_'+id).prop('hidden', true);
      $('#discount_btn_clear_'+id).prop('hidden', false);
      $('#select_discount_'+id).prop('disabled', true);
      $('#discount_input_'+id).prop('disabled', true);

      $('#frm_discount_id_'+id).val(dType);
      $('#frm_discount_amt_'+id).val(dAmount);

    }

    function deleteDiscount(id) {

      var dtype = $('#select_discount_'+id).val();
      var dAmount = $('#discount_input_'+id).val();

      discountPrice = discountPrice - parseFloat(dAmount);
      items_subtotal = items_total + parseFloat(dAmount);
      items_total = items_subtotal - parseFloat(discountPrice);

      display();
      $('#icon_span_'+id).remove();
      $('#discount_btn_save_'+id).prop('hidden',false);
      $('#discount_btn_clear_'+id).prop('hidden',true);
      $('#select_discount_'+id).prop('disabled', false);
      $('#discount_input_'+id).prop('disabled', true);
      $('#discount_input_'+id).val(0);
      $('#select_discount_'+id).val(0); 

      $('#frm_discount_id_'+id).remove();
      $('#frm_discount_amt_'+id).remove();
    }

</script>
@endsection