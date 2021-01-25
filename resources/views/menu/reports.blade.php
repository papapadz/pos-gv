@extends('layouts.app')

@section('content')
<div class="card" style="margin-bottom: 1rem;">
  <div class="card-body">
      <div class="row">
        <div class="col">
          <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Report Type </span>
              </div>
              <select class="form-control" id="select_reportType" onchange="getReports();">
                  <option value="0" selected disabled="true">Select Report Type</option>
                  <option value="1">Daily Cash In/Cash Out</option>
                  <option value="2">Expense Report</option>
                  <option value="3">Product Trend</option>
                  <option value="4">Sales Report</option>
                  <option value="5">Expense Summary</option>
              </select>
          </div>
        </div>
        <div class="col-md-2">
          <button class="btn btn-success" id="btnAddReport" onclick="addReports();" disabled="true"><i class='fa fa-plus'></i></button>
        </div>
        </div>
      </div>
</div>

<div id="cardTable1" class="card" hidden="true">
  <div class="card-body">
    <table id="reportsTable" class="table table-striped" style="text-align: center; width: 100%;">
      <thead id="reportsTable_thead">
        <tr id="tr_headings">
          <th width="10%">Date</th>
          <th>Total Cash In</th>
          <th>Total Cash Out</th>
          <th>Cash Balance</th>
          <th>Cash Count</th>
          <th>Deficit/Excess</th>
          <th>Cashier</th>
          <th></th>
        </tr>
        </thead>
    </table>
  </div>
</div>

<div id="cardTable2" hidden="true">
<div class="card" style="margin-bottom: 1rem;">
  <div class="card-body">
    <div class="row">
      <div class="col-md-5">
        <select class="form-control" id="eyear">
                <option selected value=0 disabled>Select Year</option>
                <option @if(\Carbon\Carbon::today()->year == '2018') selected @endif value='2018'>2018</option>
                <option @if(\Carbon\Carbon::today()->year == '2019') selected @endif value='2019'>2019</option>
                <option @if(\Carbon\Carbon::today()->year == '2020') selected @endif value='2020'>2020</option>
        </select>
      </div>
      <div class="col-md-5">
                    <select class="form-control" id="emonth"> 
                        <option @if(\Carbon\Carbon::today()->month == '1') selected @endif value='1' >January</option>
                        <option @if(\Carbon\Carbon::today()->month == '2') selected @endif value='2' >February</option>
                        <option @if(\Carbon\Carbon::today()->month == '3') selected @endif value='3'>March</option>
                        <option @if(\Carbon\Carbon::today()->month == '4') selected @endif value='4'>April</option>
                        <option @if(\Carbon\Carbon::today()->month == '5') selected @endif value='5'>May</option>
                        <option @if(\Carbon\Carbon::today()->month == '6') selected @endif value='6'>June</option>
                        <option @if(\Carbon\Carbon::today()->month == '7') selected @endif value='7'>July</option>
                        <option @if(\Carbon\Carbon::today()->month == '8') selected @endif value='8'>August</option>
                        <option @if(\Carbon\Carbon::today()->month == '9') selected @endif value='9'>September</option>
                        <option @if(\Carbon\Carbon::today()->month == '10') selected @endif value='10'>October</option>
                        <option @if(\Carbon\Carbon::today()->month == '11') selected @endif value='11'>November</option>
                        <option @if(\Carbon\Carbon::today()->month == '12') selected @endif value='12'>December</option>
                    </select>
      </div>
    <div class="col-md-2">
      <button id="generateButton" class="btn btn-primary" onclick="getListExpenses();">Generate</button>
      <button class="btn btn-warning" onclick="setNewExpenseName();"><i class="fa fa-cogs"></i></button>
    </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <table id="reportsTable2" class="table table-striped" style="text-align: center;width: 100%">
      <thead>
        <tr id="tr_headings2">
          <th>Date</th>
          <th>Expense Name</th>
          <th>Amount</th>
          <th>Qty</th>
          <th>Payment Type</th>
          <th>Category</th>
          <th>Action</th>
        </tr>
        </thead>
    </table>
  </div>
</div>
</div>

<div id="cardProductTrends" hidden="true">
  <div class="card" style="margin-bottom: 1rem;">
    <div class="card-body">
      <div class="row">
      <div class="col-md-10">
        <select class="form-control" id="product_year">
      <option selected value=0 disabled>Select Year</option>
                <option @if(\Carbon\Carbon::today()->year == '2018') selected @endif value='2018'>2018</option>
                <option @if(\Carbon\Carbon::today()->year == '2019') selected @endif value='2019'>2019</option>
                <option @if(\Carbon\Carbon::today()->year == '2020') selected @endif value='2020'>2020</option>
              </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary" onclick="setProductTrendReport();" >Generate</button>
      </div>
    </div>
    </div>
  </div>

  <div class="card">
  <div class="card-body">
    <table id="reportsTable3" class="table table-striped" style="text-align: center;width: 100%;">
      <thead id="reportsTable_thead3">
        <tr id="tr_headings3">
          <th width="10%">Product Name</th>
          <th>Category</th>
          <th>Jan</th>
          <th>Feb</th>
          <th>Mar</th>
          <th>Apr</th>
          <th>May</th>
          <th>Jun</th>
          <th>Jul</th>
          <th>Aug</th>
          <th>Sep</th>
          <th>Oct</th>
          <th>Nov</th>
          <th>Dec</th>
        </tr>
        </thead>
    </table>
  </div>
  </div>
</div>

<div id="cardTable3" class="card" hidden="true">
  <div class="card-body">
    <table id="reportsTable4" class="table table-striped" style="text-align: center; width: 100%;">
      <thead>
        <tr id="tr_headings">
          <th width="10%">Date</th>
          <th>Beginning</th>
          <th>Cafe Sales</th>
          <th>Mercato Sales</th>
          <th>Cash In</th>
          <th>Cash Out</th>
          <th>Net Profit</th>
        </tr>
        </thead>
    </table>
  </div>
</div>

<div id="cardTable4" hidden="true">
<div class="card" style="margin-bottom: 1rem;">
  <div class="card-body">
    <div class="row">
      <div class="col-md-5">
        <select class="form-control" id="eeyear">
                <option selected value=0 disabled>Select Year</option>
                <option @if(\Carbon\Carbon::today()->year == '2018') selected @endif value='2018'>2018</option>
                <option @if(\Carbon\Carbon::today()->year == '2019') selected @endif value='2019'>2019</option>
                <option @if(\Carbon\Carbon::today()->year == '2020') selected @endif value='2020'>2020</option>
        </select>
      </div>
      <div class="col-md-5">
                    <select class="form-control" id="eemonth"> 
                        <option @if(\Carbon\Carbon::today()->month == '1') selected @endif value='1' >January</option>
                        <option @if(\Carbon\Carbon::today()->month == '2') selected @endif value='2' >February</option>
                        <option @if(\Carbon\Carbon::today()->month == '3') selected @endif value='3'>March</option>
                        <option @if(\Carbon\Carbon::today()->month == '4') selected @endif value='4'>April</option>
                        <option @if(\Carbon\Carbon::today()->month == '5') selected @endif value='5'>May</option>
                        <option @if(\Carbon\Carbon::today()->month == '6') selected @endif value='6'>June</option>
                        <option @if(\Carbon\Carbon::today()->month == '7') selected @endif value='7'>July</option>
                        <option @if(\Carbon\Carbon::today()->month == '8') selected @endif value='8'>August</option>
                        <option @if(\Carbon\Carbon::today()->month == '9') selected @endif value='9'>September</option>
                        <option @if(\Carbon\Carbon::today()->month == '10') selected @endif value='10'>October</option>
                        <option @if(\Carbon\Carbon::today()->month == '11') selected @endif value='11'>November</option>
                        <option @if(\Carbon\Carbon::today()->month == '12') selected @endif value='12'>December</option>
                    </select>
      </div>
    <div class="col-md-2">
      <button id="generateButton" class="btn btn-primary" onclick="getListExpensesReport();">Generate</button>
    </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <table id="reportsTable5" class="table table-striped" style="text-align: center;width: 100%">
      <thead>
        <tr id="tr_headings2">
          <th>Expense Name</th>
          <th>Amount</th>
          <th>Qty</th>
        </tr>
        </thead>
    </table>
  </div>
</div>
</div>

<div id="addExpensesModal" class="modal fade" role="dialog" state="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
            <form id="frmAddExpense">
              <label>Date</label>
              <input type="date" name="expenseDate" id="expenseDate" required class="form-control">
              <label>Expense Name</label>
              <input class="form-control" type="text" name="input_expenseName" id="input_expenseName">
              <!-- <select class="form-control" name="expense_category" id="expense_category"></select> -->
              <label>Amount</label>
              <input class="form-control" type="number" name="expense_amt" value=0 id="expense_amt">
              <label>Quantity</label>
              <input class="form-control" type="number" name="expense_qty" id="expense_qty">
              <label>Unit</label>
              <input class="form-control" type="text" name="expense_unit" id="expense_unit">
              <label>Payment Type</label>
              <select name="payment_type" class="form-control">
                        <option value="0">Cash</option>
                        <option value="1">N-Cash</option>
                        <option value="2">Credit</option>
                    </select>
            </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" onclick="addExpense();">Add</button>
      </div>
    </div>
  </div>
</div>

<div id="addExpenseNameModal" class="modal fade" role="dialog" state="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
            <form id="frmAddExpenseName">
              <label>Expense Type</label>
              <select class="form-control" id="select_expenseType" name="select_expenseType">
                <option selected disabled="true">Please select an option</option>
                <option value="1">Expense Category</option>
                <option value="2">Expense Name</option>
              </select>
              <div id="div_forExpenseName" hidden="true">
                <label>Expense Name</label>
                <input type="text" class="form-control" name="expense_name" id="expense_name">
                <label>Expense Category</label>
                <select class="form-control expense_cat" name="expense_cat"></select>
              </div>
              <div id="div_forExpenseCat" hidden="true">
                <label>Expense Category</label>
                <select class="form-control expense_cat" id="select_eCat"></select>
              </div>
            </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn_forExpenseName" onclick="addExpenseName();" hidden="true">Save</button>
        <button class="btn btn-danger btn_forExpenseCat" onclick="delExpenseCat();" hidden="true"><i class="fa fa-trash"></i></button>
        <button class="btn btn-success btn_forExpenseCat" onclick="addExpenseCat();" hidden="true"><i class="fa fa-plus"></i></button>
      </div>
    </div>
  </div>
</div>

<div id="ExpenseCatModal" class="modal fade" role="dialog" state="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
          <label>Category Name</label>
          <input class="form-control" type="text" id="input_expenseCat">
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" onclick="btnAddExpenseCat()">Save</button>
      </div>
    </div>
  </div>
</div>

<div id="ExpenseListModal" class="modal fade" role="dialog" state="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="listExpensesTable_print">
            <table class="table">
              <tbody id="listExpensesTable">
                <center><h5>GOOD VIBES</h5>
                <h5>Expenses Report</h5></center>
              </tbody>  
            </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-warning" id="printExpenseReport">Print</button>
      </div>
    </div>
  </div>
</div>

<div id="addSalesModal" class="modal fade" role="dialog" state="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="listExpensesTable_print">
        <form id="frmAddSales">
          <label>Date</label>
          <input class="form-control" type="date" name="inputAddSalesDate" id="inputAddSalesDate">
          <div id="productDiv">
            <label>Products</label>
            <select name="selectProduct" class="form-control">
              @foreach($products as $p)
              <option value="{{$p->product_id}}">{{$p->product_name}} - Php {{$p->unit_price}}</option>
              @endforeach
            </select>
            <label>Qty</label>
            <input class="form-control" type="number" min=1 value=1 name="inputQtyAddSales" id="inputQtyAddSales">
          </div>
          <div id="expenseDiv" hidden="true">
            <label>Expenses</label>
            <select name="selectExpense" class="form-control">
              @foreach($expenses as $e)
              <option value="{{$e->expense_id}}">{{$e->expense_name}}</option>
              @endforeach
            </select>
            <label>Amount (Php)</label>
            <input class="form-control" type="number" name="inputExpenseAmt">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" id="btnAddSales">Save</button>
      </div>
    </div>
  </div>
</div>

<div id="closeStoreModal" class="modal fade" role="dialog" state="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="table_daily_cin_cout">
        <table class="table">
          <form id="closeStoreFrm">
          <tbody >
            <center id="daily-cin-cout-center"><h5>DAILY CASH-IN/CASH OUT FORM</h5></center>
            <tr id="tr_cdate" hidden="true">
              <td><b>DATE</b></td>
              <td>
                <input class="form-control" type="date" name="cinCoutDate" id="cinCoutDate" onchange="closeStore();">
              </td>
            </tr>
            <tr>
              <td colspan="2"><b>I. CASH-IN</b></td>
            </tr>
            <tr>
              <td colspan="2"><table id="closeStoreTbl1" class="table"></table></td>
            </tr>
            <tr>
              <td colspan="2"><b>II. CASH-OUT</b></td>
            </tr>
            <tr>
              <td colspan="2">
                <table id="closeStoreTbl2" class="table">
                  <!--<tr>
                    <td>Cash (PHP)</td>
                    <td><input class="form-control" value=0 type="number" name="cash_out" id="cash_out" /></td>
                  </tr>
                  <tr>
                    <td>N-Cash (PHP)</td>
                    <td><input class="form-control" value=0 type="number" name="n_cash" id="n_cash" /></td>
                  </tr>-->
                </table>
              </td>
            </tr>
            <tr>
              <td colspan="2"><b>III. BALANCE & CASH-COUNT</b></td>
            </tr>
            <tr>
              <td colspan="2">
                <table id="closeStoreTbl3" class="table">
                  <tr id="tr_closeStoreTbl3">
                    <td>Cash Balance</td>
                  </tr>
                  <tr>
                    <td>Cash Count</td>
                    <td id="td_cash_count"><input class="form-control tr_listExpenses" value=0 type="number" name="cash_count" id="cash_count" /></td>
                  </tr>
                </table>
              </td>
            </tr>
          </tbody>  
          </form>
        </table>
      </div>
      <div class="modal-footer">
        <center><button id="btnSubmitClosing" hidden="true" type="button" class="btn btn-primary">Submit</button><button hidden="true" id="printDailyCinCout" type="button" class="btn btn-warning" >Print</button></center>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

@section('script')
<script type="text/javascript">
    var grandTotal = 0;
    var netSales = 0;

    var reportTable = $('#reportsTable').DataTable({
      'order' : [0,'desc']
    });

    var reportTable2 = $('#reportsTable2').DataTable({
      'order' : [0,'desc']
    });
    
    var reportTable3 = $('#reportsTable3').DataTable({
      'order' : [0, 'asc']
    });

    var reportTable4 = $('#reportsTable4').DataTable({
      'order' : [0, 'desc'],
      buttons : [
        'print'
    ]
    });

    var reportTable5 = $('#reportsTable5').DataTable({
      'order' : [0, 'asc']
    });

    $('#addExpensesModal').on('hidden.bs.modal',function() {
      getReports();
    });

    $('#printDailyCinCout').on('click', function() {
        printThis('table_daily_cin_cout','Daily Cash In/Cash Out Report');
    });

    $('#printExpenseReport').on('click', function() {
      printThis('listExpensesTable_print','Monthly Expense Report');
    });

    $('#printSalesReport').on('click', function() {
      printThis('reportsTable4','Sales Report')
    });

    $('#closeStoreModal').on('hidden.bs.modal', function (e) {
        $('#cinCoutDate').val(null);
        $('#btnSubmitClosing').prop('hidden',true);
    });

    $('#addSalesModal').on('hidden.bs.modal', function (e) {
        $('#inputAddSalesDate').val(null);
        $('#inputQtyAddSales').val(1);
    });

    $('#btnAddSales').on('click', function() {

      var sdate = $('#inputAddSalesDate').val();
      var qty = $('#inputQtyAddSales').val();

      if(sdate.length>0 && qty>0) {

        var thisForm = $('#frmAddSales').serialize();

                $.ajax ({
                      url : '{{ url("reports/sales/add") }}'
                      ,method : 'GET'
                      ,data: thisForm
                      ,cache : false
                      ,beforeSend:function() {
                      //$('#loadModal').modal({ backdrop: 'static' });
                      }
                    }).done( function(response){

                      alert('Record has been saved!');
                      $('#addSalesModal').modal('hide');
                      getReports();
                    }).fail( function(response) {
                      alert('Sorry, there was an error in saving your record.');
                    });
        } else
          alert('You have entered an invalid input!'); 
    });

    $('#select_expenseType').on('change', function() {
        var et = $('#select_expenseType').val();
        
        if(et=='1') {
          $('#div_forExpenseName').prop('hidden',true);
          $('#div_forExpenseCat').prop('hidden',false);
          $('button.btn_forExpenseCat').prop('hidden',false);
          $('button.btn_forExpenseName').prop('hidden',true);
        }
        else {
          loadTags(1);
          $('#div_forExpenseName').prop('hidden',false);
          $('#div_forExpenseCat').prop('hidden',true);
          $('button.btn_forExpenseCat').prop('hidden',true);
          $('button.btn_forExpenseName').prop('hidden',false);
        }

    });

    function setTransTypes() {

      var t = $('#selectTransType').val();
      if(t=='1') {
        $('#productDiv').prop('hidden',false);
        $('#expenseDiv').prop('hidden',true);
      } else if(t=='2') {
        $('#productDiv').prop('hidden',true);
        $('#expenseDiv').prop('hidden',false);
      }
    }

    function getReports() {
      var rt = $('#select_reportType').val();
      
      if(rt=='1') {
        reportTable.clear().draw();
        /*
        $('#reportsTable_thead').append(
          '<tr id="tr_headings"><th width="10%">Date</th><th>Beginning</th><th>Cafe</th><th>Mercato</th><th>A/R payment</th><th>Cash Out</th><th>N-Cash</th><th>A/P</th><th>Cash Balance</th><th>Cash Count</th><th>Deficit/Excess</th><th>Cashier</th><tr>');
         */
        $('#cardTable1').prop('hidden',false);
        $('#cardTable2').prop('hidden',true);
        $('#cardProductTrends').prop('hidden', true);
        $('#cardTable3').prop('hidden',true);
        $('#cardTable4').prop('hidden',true);
        getDailyCashinCashout();
        $('#btnAddReport').prop('disabled',false);
        
      }
      else if(rt=='2') {
        reportTable2.clear().draw();
        /*$('#reportsTable_thead2').append(
          '<tr id="tr_headings2"><th width="10%">Date</th><th>Cost of Sales</th><th>Marketing & Ads</th><th>Salaries & Benefits</th><th>Direct Operating Expenses</th><th>General & Administrative Cost</th><th>Occupancy Cost</th><th>Repairs & Maintenance</th><th>Music & Entertainment</th><th>Utilities</th><th>Panziann</th><th>Grand Total</th><th>Net Sales</th><th>Net Profit</th><tr>');
        */
        $('#cardTable1').prop('hidden',true);
        $('#cardTable2').prop('hidden',false);
        $('#cardProductTrends').prop('hidden', true);
        $('#cardTable3').prop('hidden',true);
        $('#cardTable4').prop('hidden',true);
        getExpenseReport();
        $('#btnAddReport').prop('disabled',false);
        
      } else if(rt=='3') {

        reportTable3.clear().draw();
        $('#cardTable1').prop('hidden',true);
        $('#cardTable2').prop('hidden',true);
        $('#cardProductTrends').prop('hidden', false);
        $('#cardTable3').prop('hidden',true);
        $('#cardTable4').prop('hidden',true);
        $('#btnAddReport').prop('disabled',true);

      } else if(rt=='4') {

        reportTable4.clear().draw();
        $('#cardTable1').prop('hidden',true);
        $('#cardTable2').prop('hidden',true);
        $('#cardProductTrends').prop('hidden', true);
        $('#cardTable3').prop('hidden',false);
        $('#cardTable4').prop('hidden',true);
        getSalesReport();
        $('#btnAddReport').prop('disabled',false);
      } else {
        reportTable5.clear().draw();
        $('#cardTable1').prop('hidden',true);
        $('#cardTable2').prop('hidden',true);
        $('#cardProductTrends').prop('hidden', true);
        $('#cardTable3').prop('hidden',true);
        $('#cardTable4').prop('hidden',false);
      }
    }

    function addReports() {
      var rt = $('#select_reportType').val();
      
      if(rt=='1') {
        $('#tr_cdate').prop('hidden',false);
        $('#closeStoreModal').modal('show');
        $('.tr_closing_details').remove();
        //$('#btnSubmitClosing').prop('hidden',false);
        $('#printDailyCinCout').prop('hidden',true);
        $('#daily-cin-cout-h5').remove();
      }else if(rt=='2') {
        loadTags(2);
        addExpenseReport();
      }else if(rt=='4') {
        $('#addSalesModal').modal('show');
      }
    }    

    function findExpenseID(str) {
      
      var eid = 0;
      $.ajax ({
                    url : '{{ url("getters/expense-names/get") }}'
                    ,method : 'GET'
                    ,data: { name:str }
                    ,cache : false
                    ,beforeSend:function() {
                    //$('#loadModal').modal({ backdrop: 'static' });
                    }
                  }).done( function(response){
                    eid = response['expense_id'];
                    var thisForm = $('#frmAddExpense').serialize();

                    $.ajax ({
                          url : '{{ url("expense/new/save?") }}' + thisForm
                          ,method : 'GET'
                          ,data: {expense_category:eid}
                          ,cache : false
                          ,beforeSend:function() {
                          //$('#loadModal').modal({ backdrop: 'static' });
                          }
                        }).done( function(response){

                          alert('Expense saved!');
                          $('#expense_name').val('');
                          $('#expense_amt').val(0);
                          $('#expense_qty').val(0);
                          $('#expense_unit').val('');
                          $('#addExpensesModal').modal('hide');
                        }).fail( function(response) {
                          alert('Sorry, there was an error in saving your record.');
                        });
        
                    }).fail( function(response) {
                      return 0;
                  });
      
    }

    function addExpense() {

      var ddate = $('#expenseDate').val();
      var amt = $('#expense_amt').val();
      var qty = $('#expense_qty').val();
      var unit = $('#expense_unit').val();
      var ename = $('#input_expenseName').val();
      
      if(ddate.length==0)
        alert('Please insert a date!');
      else if (amt<=0)
        alert('Amount must be greater that 0!');
      else {
        findExpenseID(ename);
      }
    }

    function getDailyCashinCashout() {
       
                $.ajax ({
                    url : '{{ url("getters/daily-cashin-cashout/get") }}'
                    ,method : 'GET'
                    ,cache : false
                    ,beforeSend:function() {
                    //$('#loadModal').modal({ backdrop: 'static' });
                    }
                  }).done( function(response){

                    for(var i=0; i<response.length; i++) {

                      var id = response[i]['beginning_id'];
                      var c1 = parseFloat(response[i]['beginning']) + parseFloat(response[i]['cafe']) + parseFloat(response[i]['mercato']) + parseFloat(response[i]['payment']);
                      var c2 = parseFloat(response[i]['cash_out']) + parseFloat(response[i]['n_cash']) + parseFloat(response[i]['credit']);
                      var c3 = parseFloat(c1) - parseFloat(c2);
                      var c4 = parseFloat(response[i]['cash_count']) - parseFloat(c3);

                      @if(Auth::User()->user_level==1)
                        var delbtn = "<button class='btn btn-sm btn-primary btn-view-cin-cout' onclick=viewDailyCinCout('"+id+"')><i class='fa fa-bars'></i></button><button class='btn btn-sm btn-danger foradminonly' onclick=deleteDailyCinCout('"+id+"')><i class='fa fa-trash'></i></button>";
                      @else
                        var delbtn = "<button class='btn btn-sm btn-primary btn-view-cin-cout' onclick=viewDailyCinCout('"+id+"')><i class='fa fa-bars'></i></button>";
                      @endif

                      reportTable.row.add([
                        response[i]['date'],
                        parseFloat(c1).toFixed(2),
                        parseFloat(c2).toFixed(2),
                        parseFloat(c3).toFixed(2),
                        parseFloat(response[i]['cash_count']).toFixed(2),
                        parseFloat(c4),
                        response[i]['cashier'],
                        delbtn
                        ]).draw(false);
                    }
                  }).fail( function(response) {
                    alert('Sorry, there was an error in retrieving your record.');
                  });
    }

    function getExpenseReport() {
              
              $.ajax ({
                    url : '{{ url("getters/expense-reports/get") }}'
                    ,method : 'GET'
                    ,cache : false
                    ,beforeSend:function() {
                    //$('#loadModal').modal({ backdrop: 'static' });
                    }
                  }).done( function(response){

                    var delbtn = "";
                    var type;

                    for(var i=0; i<response.length; i++) {

                        var id = response[i]['expense_report_id'];

                        @if(Auth::User()->user_level==1)
                          delbtn = "<button class='btn btn-sm btn-danger' onclick=deleteThisExpense('"+id+"')><i class='fa fa-trash'></i></>";
                        @endif
                    
                        if(response[i]['payment_type']==0)
                          type = 'Cash';
                        else if(response[i]['payment_type']==1)
                          type = 'N-Cash' ;
                        else
                          type = 'Credit';

                        reportTable2.row.add([
                          response[i]['report_date'],
                          response[i]['expense_name'],
                          response[i]['expense_amount'],
                          response[i]['qty'] + ' ' + response[i]['measurement'],
                          type,
                          response[i]['category_name'],
                          delbtn
                          ]).draw(false);
                    }
                  }).fail( function(response) {
                    alert('Sorry, there was an error in saving your record.');
                  });
    }

    function getSalesReport() {

      $.ajax ({
                    url : '{{ url("getters/daily-cashin-cashout/get") }}'
                    ,method : 'GET'
                    ,data: {flag:1}
                    ,cache : false
                    ,beforeSend:function() {
                    //$('#loadModal').modal({ backdrop: 'static' });
                    }
                  }).done( function(response){

                    for(var i=0; i<response.length; i++) {

                      var c1 = c2 = c3 = 0;  
                      var id = response[i]['beginning_id'];
                      c1 = parseFloat(response[i]['beginning']) + parseFloat(response[i]['cafe']) + parseFloat(response[i]['mercato']) + parseFloat(response[i]['payment']);
                      c2 = parseFloat(response[i]['cash_out']) + parseFloat(response[i]['n_cash']) + parseFloat(response[i]['credit']);
                      c3 = parseFloat(c1) - parseFloat(c2);
                      
                      reportTable4.row.add([
                        response[i]['date'],
                        parseFloat(response[i]['beginning']).toFixed(2),
                        parseFloat(response[i]['cafe']).toFixed(2),
                        parseFloat(response[i]['mercato']).toFixed(2),
                        parseFloat(response[i]['payment']).toFixed(2),
                        "("+parseFloat(c2).toFixed(2)+")",
                        parseFloat(c3).toFixed(2)
                        ]).draw(false);
                    }
                  }).fail( function(response) {
                    alert('Sorry, there was an error in saving your record.');
                  });
    }

    function deleteThisExpense(id) {
      var c = confirm('Are you sure you want to delete this record?');
      if(c) {
        $.ajax ({
                    url : '{{ url("expense/delete/this") }}'
                    ,method : 'GET'
                    ,data: {id:id}
                    ,cache : false
                    ,beforeSend:function() {
                    //$('#loadModal').modal({ backdrop: 'static' });
                    }
                  }).done( function(response){

                    alert('Record has been deleted!')
                    getReports();

                  }).fail( function(response) {
                    alert('Sorry, there was an error.');
                  });
      }
    }

    function addExpenseReport() {

                  /*
              $('option.expense-option').remove()

                $.ajax ({
                    url : '../public/getters/expense-names/get'
                    ,method : 'GET'
                    ,cache : false
                    ,beforeSend:function() {
                    //$('#loadModal').modal({ backdrop: 'static' });
                    }
                  }).done( function(response){

                    for(var i=0; i<response.length; i++) {

                      $('#expense_category').append(
                          '<option class="expense-option" value='+response[i]['expense_id']+'>'+response[i]['expense_name']+'</option>'
                        );
                    }
                  }).fail( function(response) {
                    alert('Sorry, there was an error in saving your record.');
                  });
                    */
                  $('#addExpensesModal').modal('show');
    }

    function setProductTrendReport() {
      reportTable3.clear().draw();
      var y = $('#product_year').val();

      $.ajax ({
                    url :  '{{ url("getters/product_trends/get") }}' 
                    ,method : 'GET'
                    ,data: {year:y}
                    ,cache : false
                    ,beforeSend:function() {
                    //$('#loadModal').modal({ backdrop: 'static' });
                    }
                  }).done( function(response){

                    for(var i=0; i<response.length; i++) {

                      reportTable3.row.add([

                        response[i]['name'],
                        response[i]['cat'],
                        response[i]['m1'],
                        response[i]['m2'],
                        response[i]['m3'],
                        response[i]['m4'],
                        response[i]['m5'],
                        response[i]['m6'],
                        response[i]['m7'],
                        response[i]['m8'],
                        response[i]['m9'],
                        response[i]['m10'],
                        response[i]['m11'],
                        response[i]['m12']
                        ]).draw(false);
                    }
                  }).fail( function(response) {
                    alert('Sorry, there was an error retrieving data.');
                  });
    }

    function viewDailyCinCout(d) {
      //var d = $('.btn-view-cin-cout').closest('tr').find('.cdate').text();
      $('#tr_cdate').prop('hidden',true);
      $('.tr_closing_details').remove();
      $('#btnSubmitClosing').prop('hidden',true);
      $('#printDailyCinCout').prop('hidden',false);
      
      $.ajax ({
                    url : '{{ url("getters/daily-cashin-cashout/get") }}'
                    ,method : 'GET'
                    ,data: {id:d}
                    ,cache : false
                    ,beforeSend:function() {
                    //$('#loadModal').modal({ backdrop: 'static' });
                    }
                  }).done( function(response){

                    $('#daily-cin-cout-h5').remove();
                    $('#daily-cin-cout-center').append('<h5 id="daily-cin-cout-h5">Date:'+response[0]['date']+'</h5>');

                    for(var i=0; i<response.length; i++) {
                      $('#closeStoreTbl1').append(
                    '<tr class="tr_closing_details">'+
                    '<td>Beginning</td>'+
                    '<td>PHP '+parseFloat(response[0]['beginning']).toFixed(2)+ '</td>' +
                    '</tr>'
                  );


                $('#closeStoreTbl1').append(
                    '<tr class="tr_closing_details">'+
                    '<td>Cafe</td>'+
                    '<td>PHP '+parseFloat(response[0]['cafe']).toFixed(2)+ '</td>' +
                    '</tr>'
                  );

                
                $('#closeStoreTbl1').append(
                    '<tr class="tr_closing_details">'+
                    '<td>Mercato</td>'+
                    '<td>PHP '+parseFloat(response[0]['mercato']).toFixed(2)+ '</td>' +
                    '</tr>'
                  );

                 $('#closeStoreTbl1').append(
                    '<tr class="tr_closing_details">'+
                    '<td>A/R Payments</td>'+
                    '<td>PHP '+ parseFloat(response[0]['payment']).toFixed(2) + '</td>' +
                    '</tr>'
                  );

                var cinTotal = parseFloat(response[0]['beginning']) + parseFloat(response[0]['cafe']) + parseFloat(response[0]['mercato']) + parseFloat(response[0]['payment']);

                $('#closeStoreTbl1').append(
                    '<tr class="tr_closing_details">'+
                    '<td align="right" width="60%"><b>TOTAL CASH-IN</b></td>'+
                    '<td>PHP '+parseFloat(cinTotal).toFixed(2)+ '</td>' +
                    '</tr>'
                  );

                $('#closeStoreTbl2').append(
                    '<tr class="tr_closing_details">'+
                    '<td>Cash Out</td>'+
                    '<td>PHP '+ parseFloat(response[0]['cash_out']).toFixed(2) + '</td>' +
                    '</tr>'
                  );

                $('#closeStoreTbl2').append(
                    '<tr class="tr_closing_details">'+
                    '<td>N-Cash</td>'+
                    '<td>PHP '+ parseFloat(response[0]['n_cash']).toFixed(2) + '</td>' +
                    '</tr>'
                  );

                $('#closeStoreTbl2').append(
                    '<tr class="tr_closing_details">'+
                    '<td>Credit</td>'+
                    '<td>PHP '+ parseFloat(response[0]['credit']).toFixed(2) + '</td>' +
                    '</tr>'
                  );

                $('#closeStoreTbl2').append(
                    '<tr class="tr_closing_details">'+
                    '<td>A/P</td>'+
                    '<td>PHP '+ parseFloat(response[0]['ap']).toFixed(2) + '</td>' +
                    '</tr>'
                  );

                var ccOutTotal = parseFloat(response[0]['cash_out']) + parseFloat(response[0]['credit']); 
                var coutTotal =  parseFloat(response[0]['n_cash']) + parseFloat(response[0]['ap']).toFixed(2) + parseFloat(ccOutTotal);

                var bal = parseFloat(cinTotal) - parseFloat(ccOutTotal);

                $('#closeStoreTbl2').append(
                    '<tr id="tr_cashout_total" class="tr_closing_details">'+
                    '<td align="right" width="60%"><b>TOTAL CASH-OUT</b></td>'+
                    '<td class="tr_closing_details">PHP '+ parseFloat(coutTotal).toFixed(2) + '</td>' +
                    '</tr>'
                  );

                  $('#tr_cashount_total').remove();

                  $('#tr_closeStoreTbl3').append(
                    '<td id="tr_cashount_total" class="tr_closing_details">'+
                    'PHP '+ parseFloat(bal).toFixed(2) +'</td>'
                  );

                  $('#cash_count').remove();

                  $('#td_cash_count').append('<span class="tr_closing_details">PHP '+parseFloat(response[0]['cash_count']).toFixed(2))+'</span>';              
                  var def_exc = parseFloat(response[0]['cash_count']) - parseFloat(bal);
                  $('#closeStoreTbl3').append(
                    '<tr id="tr_deficit_excess" class="tr_closing_details">'+
                    '<td width="60%">Deficit/Excess</td>'+
                    '<td>PHP '+parseFloat(def_exc).toFixed(2)+'</td>' +
                    '</tr>'
                  );
                  
                  }

                  $('#closeStoreModal').modal('show');
                  
                  }).fail( function(response) {
                    alert('Sorry, there was an error in saving your record.');
                  });
    }

    function deleteDailyCinCout(d) {

      //var d = $('.btn-view-cin-cout').closest('tr').find('.cdate').text();
      var c = confirm('Are you sure you want to delete this Cash In/Cash Out Report?');
                if(c) {
                
                  $.ajax ({
                    url : '{{ url("beginning/reset") }}'
                    ,method : 'GET'
                    ,data: {id:d}
                    ,cache : false
                    ,beforeSend:function() {
                    //$('#loadModal').modal({ backdrop: 'static' });
                    }
                  }).done( function(response){

                    getReports();

                  }).fail( function(response) {
                    alert('Sorry there was an error in retrieving the data.');
                  });  
                }
    }

    function closeStore() {

          var cinCoutDate = $('#cinCoutDate').val();

          if(cinCoutDate!=null) {
            $('#tr_cdate').prop('hidden',false);
            $('.tr_closing_details').remove();
            $('#btnSubmitClosing').prop('hidden',false);
            $('#printDailyCinCout').prop('hidden',true);
            $('#tr_cashount_total').remove();
            
            var cinCoutDate = $('#cinCoutDate').val();
          
            $.ajax ({
                  url : '{{ url("getters/today/daily-report") }}'
                  ,method : 'GET'
                  ,data: {d:cinCoutDate}
                  ,cache :false
                  ,beforeSend:function() {
                  //$('#loadModal').modal({ backdrop: 'static' });
                  }
                }).done( function(response){
                  //alert(response.beginning);
                  if(response['beginning']==null) {
                    alert('There are no transactions on this date!');
                    $('#cinCoutDate').val(null);
                    $('#btnSubmitClosing').prop('hidden',true);
                  }
                  else if(response['beginning']['is_active']=='1') {

                    var beginning = response.beginning['balance'];
                    cafeTotal = 0;
                    mercatoTotal = 0;
                    arTotal = 0;
                    apTotal = 0;
                    crTotal = 0;
                    cinTotal = 0
                    coutTotal = 0;
                    balTotal = 0;
                    overallTotal = 0;
                    var cashoutTotal = 0;
                    var ncashTotal = 0;

                    $('#closeStoreTbl1').append(
                        '<tr class="tr_closing_details">'+
                        '<td>Beginning</td>'+
                        '<td>PHP '+parseFloat(beginning).toFixed(2)+ '</td>' +
                        '</tr>'
                      );

                    if(response.cafe.length>0) {

                      for(var i=0; i<response.cafe.length; i++) {

                        var up = response.cafe[i]['unit_price'];
                        var qty = response.cafe[i]['qty'];
                        var discount = response.cafe[i]['discount_amount'];
                        var charge = 0;

                        if(response.cafe[i]['charge_amount']!=null)
                          charge = response.cafe[i]['charge_amount'];
                        
                        cafeTotal = ((parseFloat(cafeTotal) + (parseFloat(up)*parseFloat(qty))) + parseFloat(charge))- parseFloat(discount);
                      }

                    }

                    $('#closeStoreTbl1').append(
                        '<tr class="tr_closing_details">'+
                        '<td>Cafe</td>'+
                        '<td>PHP '+parseFloat(cafeTotal).toFixed(2)+ '</td>' +
                        '</tr>'
                      );

                    if(response.mercato.length>0) {

                      for(var i=0; i<response.mercato.length; i++) {

                        var up = response.mercato[i]['unit_price'];
                        var qty = response.mercato[i]['qty'];
                        var discount = response.mercato[i]['discount_amount'];
                        var charge = 0;

                        if(response.mercato[i]['charge_amount']!=null)
                          charge = response.mercato[i]['charge_amount'];
                        
                        mercatoTotal = ((parseFloat(mercatoTotal) + (parseFloat(up)*parseFloat(qty))) + parseFloat(charge))- parseFloat(discount);

                      }

                    }

                    $('#closeStoreTbl1').append(
                        '<tr class="tr_closing_details">'+
                        '<td>Mercato</td>'+
                        '<td>PHP '+parseFloat(mercatoTotal).toFixed(2)+ '</td>' +
                        '</tr>'
                      );


                    if(response.payment.length>0) {

                      for(var i=0; i<response.payment.length; i++) {

                        var up = response.payment[i]['unit_price'];
                        var qty = response.payment[i]['qty'];
                        var discount = response.payment[i]['discount_amount'];
                        var charge = 0;
                        if(response.payment[i]['charge_amount']!=null)
                          charge = response.payment[i]['charge_amount'];
                        
                        arTotal = ((parseFloat(arTotal) + (parseFloat(up)*parseFloat(qty))) + parseFloat(charge))- parseFloat(discount);
                      }

                    }

                     $('#closeStoreTbl1').append(
                        '<tr class="tr_closing_details">'+
                        '<td>A/R Payments</td>'+
                        '<td>PHP '+ parseFloat(arTotal).toFixed(2) + '</td>' +
                        '</tr>'
                      );

                    cinTotal = parseFloat(beginning) + parseFloat(cafeTotal) + parseFloat(mercatoTotal) + parseFloat(arTotal);

                    $('#closeStoreTbl1').append(
                        '<tr class="tr_closing_details">'+
                        '<td align="right" width="60%"><b>TOTAL CASH-IN</b></td>'+
                        '<td>PHP '+parseFloat(cinTotal).toFixed(2)+ '</td>' +
                        '</tr>'
                      );

                    if(response.cashout.length>0) {

                      for(var i=0; i<response.cashout.length; i++) {
                        cashoutTotal = parseFloat(cashoutTotal) + parseFloat(response.cashout[i]['expense_amount']);
                      }
                    }

                    $('#closeStoreTbl2').append(
                        '<tr class="tr_closing_details">'+
                        '<td>Cash Out</td>'+
                        '<td>PHP '+ parseFloat(cashoutTotal).toFixed(2) + '</td>' +
                        '</tr>'
                      );

                    if(response.ncash.length>0) {

                      for(var i=0; i<response.ncash.length; i++) {
                        ncashTotal = parseFloat(ncashTotal) + parseFloat(response.ncash[i]['expense_amount']);
                      }
                    }

                    $('#closeStoreTbl2').append(
                        '<tr class="tr_closing_details">'+
                        '<td>N-Cash</td>'+
                        '<td>PHP '+ parseFloat(ncashTotal).toFixed(2) + '</td>' +
                        '</tr>'
                      );

                    if(response.credit.length>0) {

                      for(var i=0; i<response.credit.length; i++) {

                        var up = response.credit[i]['unit_price'];
                        var qty = response.credit[i]['qty'];
                        var discount = response.credit[i]['discount_amount'];
                        var charge = 0;
                        if(response.credit[i]['charge_amount']!=null)
                          charge = response.credit[i]['charge_amount'];
                        
                        crTotal = ((parseFloat(crTotal) + (parseFloat(up)*parseFloat(qty))) + parseFloat(charge)) - parseFloat(discount);
                      }

                    }

                    if(response.expCredit.length>0) {

                      for(var i=0; i<response.expCredit.length; i++)
                        apTotal = parseFloat(apTotal) + parseFloat(response.expCredit[i]['expense_amount']);
                      
                    }

                    $('#closeStoreTbl2').append(
                        '<tr class="tr_closing_details">'+
                        '<td>A/P</td>'+
                        '<td>PHP '+ parseFloat(apTotal).toFixed(2) + '</td>' +
                        '</tr>'
                      );

                    $('#closeStoreTbl2').append(
                        '<tr class="tr_closing_details">'+
                        '<td>Credit</td>'+
                        '<td>PHP '+ parseFloat(crTotal).toFixed(2) + '</td>' +
                        '</tr>'
                      );

                    ccout = parseFloat(cashoutTotal)+parseFloat(crTotal);
                    coutTotal =  parseFloat(ncashTotal) + parseFloat(apTotal) + ccout;

                    $('#closeStoreTbl2').append(
                        '<tr id="tr_cashout_total" class="tr_closing_details">'+
                        '<td align="right" width="60%"><b>TOTAL CASH-OUT</b></td>'+
                        '<td>PHP '+ parseFloat(coutTotal).toFixed(2) + '</td>' +
                        '</tr>'
                      );

                    calculateCashBalance();

                      $('#closeStoreTbl3').append(
                        '<tr id="tr_deficit_excess" class="tr_closing_details">'+
                        '<td width="60%">Deficit/Excess</td>'+
                        '<td> - </td>' +
                        '</tr>'
                      );
                  //$('#closeStoreModal').modal('show');
                  } else  {
                    alert('There is already an existing Daily Cash In/Cash Out Report for today!');
                    $('#cinCoutDate').val(null);
                    $('#btnSubmitClosing').prop('hidden',true);
                    //viewDailyCinCout();
                    //$('#closeStoreModal').modal('show');
                  }
               
                }).fail( function(response) {
                  alert('Sorry, there was an error retrieving data, try again!');
                  getReports();
                });
            }  
    }

    function addExpenseName() {

      var name = $('#expense_name').val();

      if(name.length>0) {
        var thisForm = $('#frmAddExpenseName').serialize();

        $.ajax ({
                    url : '{{ url("expense-name/new/save") }}'
                    ,method : 'GET'
                    ,data: thisForm
                    ,cache : false
                    ,beforeSend:function() {
                    //$('#loadModal').modal({ backdrop: 'static' });
                    }
                  }).done( function(response){

                    alert('Record has been saved!');
                    $('#addExpenseNameModal').modal('hide');
                  }).fail( function(response) {
                    alert('Sorry, there was an error in saving your record.');
                  });
      } else
        alert('Please enter an expense name!');
      
    }

    function addExpenseCat() {
      $('#ExpenseCatModal').modal('show');
    }

    function btnAddExpenseCat() {
      var v = $('#input_expenseCat').val();
      if(v.length>0) {
        $.ajax ({
                    url : '{{ url("expense-categories/add") }}'
                    ,method : 'GET'
                    ,data: {cat:v}
                    ,cache : false
                    ,beforeSend:function() {
                    //$('#loadModal').modal({ backdrop: 'static' });
                    }
                  }).done( function(response){

                    alert('Success, your record has been saved!');
                    setSelectExpenseCat();
                    $('#ExpenseCatModal').modal('hide');

                  }).fail( function(response) {
                    alert('Sorry, there was an error in saving your record.');
                  });
      }
      else {
        alert('Sorry, please enter an value!');
      }
    }

    function setNewExpenseName() {
      setSelectExpenseCat();
      $('#addExpenseNameModal').modal('show');    
    }

    function setSelectExpenseCat() {
      $('option.expense-name-option').remove()

                $.ajax ({
                    url : '{{ url("getters/expense-categories/get") }}'
                    ,method : 'GET'
                    ,cache : false
                    ,beforeSend:function() {
                    //$('#loadModal').modal({ backdrop: 'static' });
                    }
                  }).done( function(response){

                    for(var i=0; i<response.length; i++) {

                      $('select.expense_cat').append(
                          '<option class="expense-name-option" value='+response[i]['expense_category_id']+'>'+response[i]['category_name']+'</option>'
                        );
                    }
                  }).fail( function(response) {
                    alert('Sorry, there was an error in saving your record.');
                  }); 
                  
    }

    function delExpenseCat() {
      var v = $('#select_eCat').val();

      var c = confirm('Are you sure you want to delete this record?');
      if(c) {
        $.ajax ({
                    url : '{{ url("expense-categories/delete/this") }}' 
                    ,method : 'GET'
                    ,data: {id:v}
                    ,cache : false
                    ,beforeSend:function() {
                    //$('#loadModal').modal({ backdrop: 'static' });
                    }
                  }).done( function(response){

                    alert('Success, your record has been deleted!');
                    setSelectExpenseCat();
                    $('#ExpenseCatModal').modal('hide');
                  }).fail( function(response) {
                    alert('Sorry, there was an error in deleting your record.');
                  });
      }
    }

    function getListExpensesReport() {
      
      reportTable5.clear();      
      var year = $('#eeyear').val();
      var month = $('#eemonth').val();
      var dd = year+'-'+month+'-01';
      
      $.ajax ({
                      url : '{{ url("getters/expense-summary/get") }}'
                      ,method : 'GET'
                      ,data: {dd:dd}
                      ,cache : false
                      ,beforeSend:function() {
                      //$('#loadModal').modal({ backdrop: 'static' });
                      }
                    }).done( function(response){

                      for(var i=0; i<response.length; i++) {

                      reportTable5.row.add([

                        response[i]['expense_name'],
                        response[i]['amt'],
                        response[i]['qty']+' '+response[i]['unit']
                        ]).draw(false);
                      }

                    }).fail( function(response) {
                      alert('Sorry, there was an error in retrieving your record.');
                    });

    }

    function getListExpenses() {
        
        var gtotal = 0;
        var ctotal = 0;
        var nctotal = 0;
        var apptotal = 0;
        var sales = 0;
        var net = 0;
        var year = $('#eyear').val();
        var month = $('#emonth').val();
        var dd = year+'-'+month+'-01';

        $('tr.tr_listExpenses').remove();

                  $.ajax ({
                      url : '{{ url("getters/expense-reports/by-month/get") }}'
                      ,method : 'GET'
                      ,data: { dd:dd }
                      ,cache : false
                      ,beforeSend:function() {
                      //$('#loadModal').modal({ backdrop: 'static' });
                      }
                    }).done( function(response){

                      if(response['items']!=null) {

                      var ddd = response['date']+'-01';
                      var dt = moment(ddd, "YYYY-MM-DD");
                      /*
                      gtotal = parseFloat(response['c1']) + parseFloat(response['c2']) + parseFloat(response['c3']) + parseFloat(response['c4']) + parseFloat(response['c5']) + parseFloat(response['c6']) + parseFloat(response['c7']) + parseFloat(response['c8']) + parseFloat(response['c9']) + parseFloat(response['c10']);
                      */
                      ctotal = parseFloat(response['cash_grand_total']);
                      nctotal = parseFloat(response['ncash_grand_total']);
                      apptotal = parseFloat(response['ap_grand_total']);
                      gtotal = parseFloat(ctotal) + parseFloat(nctotal) + parseFloat(apptotal); 
                      sales = response['sales'];
                      net = parseFloat(response['sales']) - parseFloat(gtotal);
                      
                      $('#listExpensesTable').append(
                            "<tr style='border-top:solid 2px;' class='tr_listExpenses'>"+
                            "<td colspan=3><b>Date: "+dt.format('MMMM')+" "+dt.format('YYYY')+"</b></td>"+
                            "</tr>"
                          );

                      if(response.items) {
                          if(response.items.length>0) {
                          var i = 0;
                          for(i=0;i<response.items.length;i++) {
                            var j = 0;
                            $('#listExpensesTable').append(
                              "<tr class='tr_listExpenses'>"+
                              "<td colspan=3><b>"+response.items[i]['cat'][j]['category_name']+"</b></td>"+
                              "</tr>"
                            );
                            
                            var tempid=0;
                            for(j=0;j<response.items[i]['cat'].length;j++) {
                              
                              if(tempid!=response.items[i]['cat'][j]['expense_name_id']) {
                                $('#listExpensesTable').append(
                                  "<tr class='tr_listExpenses'>"+
                                  "<td colspan=2 align='right'>"+response.items[i]['cat'][j]['expense_name']+"</td>"+
                                  "<td>PHP "+parseFloat(response.items[i]['cat'][j]['grand_expense_total']).toFixed(2)+"</td>"+
                                  "</tr>"
                                );  
                              tempid=response.items[i]['cat'][j]['expense_name_id'];
                              }
                              
                            }
                          }

                        }
                      }

                      $('#listExpensesTable').append(
                            "<tr style='border-top:solid 2px;' class='tr_listExpenses'>"+
                            "<td colspan=2 align='right'><b>Cash Out Total</b></td>"+
                            "<td>PHP "+parseFloat(ctotal).toFixed(2)+"</td>"+
                            "</tr>"
                          );
                      $('#listExpensesTable').append(
                            "<tr class='tr_listExpenses'>"+
                            "<td colspan=2 align='right'><b>N Cash Total</b></td>"+
                            "<td>PHP "+parseFloat(nctotal).toFixed(2)+"</td>"+
                            "</tr>"
                          );
                      $('#listExpensesTable').append(
                            "<tr class='tr_listExpenses'>"+
                            "<td colspan=2 align='right'><b>A/P Total</b></td>"+
                            "<td>PHP "+parseFloat(apptotal).toFixed(2)+"</td>"+
                            "</tr>"
                          );

                    $('#listExpensesTable').append(
                            "<tr class='tr_listExpenses'>"+
                            "<td colspan=2 align='right'><b>Sales</b></td>"+
                            "<td>PHP "+parseFloat(sales).toFixed(2)+"</td>"+
                            "</tr>"
                          );
                    $('#listExpensesTable').append(
                            "<tr class='tr_listExpenses'>"+
                            "<td colspan=2 align='right'><b>Net Profit</b></td>"+
                            "<td>PHP "+ parseFloat(net).toFixed(2) +"</td>"+
                            "</tr>"
                          ); 

                    $('#ExpenseListModal').modal('show');
                  } else
                    alert('There are no expenses incurred on this month!');
                    }).fail(function(response) {
                      alert('Sorry there was an error retrieving data.')
                    });
    }

function loadTags(x) {
  var availableTags=new Array();
  $.ajax ({
                    url : '{{ url("getters/expense-names/get") }}'
                    ,method : 'GET'
                    ,cache : false
                    ,beforeSend:function() {
                    //$('#loadModal').modal({ backdrop: 'static' });
                    }
                  }).done( function(response){

                    if(response.length>0) {
                      for(var i=0; i<response.length; i++)
                        availableTags[i] = response[i]['expense_name'];
                    }

                    if(x==1) {
                      
                        $( "#expense_name").autocomplete({
                          source: availableTags
                        });
                      
                        $( "#expense_name").autocomplete( "option", "appendTo", "#div_forExpenseName" );
                      } else if(x==2) {
                        $( "#input_expenseName").autocomplete({
                          source: availableTags
                        });
                      
                        $( "#input_expenseName").autocomplete( "option", "appendTo", "#frmAddExpense" );
                      }
                    }).fail( function(response) {
                    alert('Sorry, there was an error in deleting your record.');
                  });
}


</script>
@endsection