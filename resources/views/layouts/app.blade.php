<!doctype html>
<html class="no-js" lang="en">

<head>
    <link rel="icon" href="{{ url('img/logo/logo.jpg') }}">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Point of Sale System</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    @include('includes.styles')

    @yield('styles')
    
    <style type="text/css">

        body {
          background: #00e6e6; /* fallback for old browsers */
  background: -webkit-linear-gradient(right, #00e6e6, #00cccc);
  background: -moz-linear-gradient(right, #00e6e6, #00cccc);
  background: -o-linear-gradient(right, #00e6e6, #00cccc);
  background: linear-gradient(to left, #00e6e6, #00cccc);
  font-family: "Roboto", sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;    
        }

        .modal {
          overflow-y:auto;
        }
        
        #pos-nav {
            box-shadow: 1px 1px 1px grey;
            background: white;
        }

        .card {
          box-shadow: 0px 1px 2px grey;
        }

        #main-container {
          padding-top: 1rem;
        }
        
    </style>

</head>

<body id="main-body">
<nav id="pos-nav" class="navbar navbar-expand-lg">
  <a class="navbar-brand" href="#">
    <img src="{{ url('img/logo/logo.jpg') }}" width="30" height="30" alt="logo">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-list-4" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <i class="fa fa-bars" style="color: #0078ce;"></i>
  </button>
  <div class="collapse navbar-collapse justify-content-end" id="navbar-list-4">
    <ul class="navbar-nav" >
        <li class="nav-item active">
          <a class="nav-link" href="{{url('menu')}}"><i class="fa fa-home"></i> Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{url('sales')}}"><i class="fa fa-money-bill"></i> Sales @if($countUnpaid>0) <span class="badge badge-danger"> {{$countUnpaid}} </span>@endif<span class="sr-only"></span></a>
        </li>
        <li class="nav-item foradminonly" >
          <div class="dropdown ">
            <a class="nav-link dropdown-toggle" href="#" id="productDropDown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-shopping-cart"></i> Products <span class="sr-only"></span></a>
            <div class="dropdown-menu" aria-labelledby="productDropDown">
              <a class="dropdown-item" href="{{url('products')}}">Product List</a>
              <a class="dropdown-item" href="{{url('products/categories')}}">Product Categories</a>
            </div>
          </div>
        </li>
        <li class="nav-item foradminonly">
          <a class="nav-link" href="{{url('users')}}"><i class="fa fa-user"></i> Users <span class="sr-only"></span></a>
        </li>
        <li class="nav-item">
          <div class="dropdown ">
            <a class="nav-link dropdown-toggle" href="#" id="customerDropDown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-address-book"></i> Customers @if($countCredit>0) <span class="badge badge-danger"> {{$countCredit}} </span>@endif<span class="sr-only"></span></a>
            <div class="dropdown-menu" aria-labelledby="customerDropDown">
              <a class="dropdown-item" href="{{url('customers')}}">Green Perks</a>
              <a class="dropdown-item" href="{{url('customers/credits')}}">Credits @if($countCredit>0) <span class="badge badge-danger"> {{$countCredit}} </span>@endif</a>
            </div>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{url('reports')}}"><i class="fa fa-poll"></i> Reports <span class="sr-only"></span></a>
        </li>
        <li class="nav-item dropdown" style="margin-right: 5rem;">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img @if(Auth::User()->user_level==1)src="{{asset('img/logo/admin.png')}}"@else src="{{asset('img/logo/user.png')}}" @endif width="40" height="40" class="rounded-circle">
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="{{url('logout')}}" onclick="return confirm('Are you sure you want to exit?')">Log Out</a>
        </div>
      </li>   
    </ul>
  </div>
</nav>

<div id='main-container' class="container-fluid" >
@yield('content')

</div>
</body>
<nav class="navbar-nav fixed-bottom bg-light px-3 text-right">
  <?php
  use Carbon\Carbon;

  $exp = Carbon::create(2019, 3, 3);
  $res = Carbon::now()->gt($exp);
  if($res)
    echo '<a style="padding:1px" class="text-muted" href="https://fb.me/binarybee.solutions" target="_blank">&copy; <b>Binary Bee IT Solutions and Services 2019</b>. <i>All rights reserved.</i></a>';
  
  ?>
    @include('includes.scripts')

    <script type="text/javascript">

        @if(Auth::User()->user_level == 0)
            $('#itemNotifs').hide();
            $('#itemMessages').hide();
            $('.foradminonly').hide();
            //$('#itemTasks').hide();
        @endif
        var ccout = 0;
        var cafeTotal = 0;
        var mercatoTotal = 0;
        var arTotal = 0;
        var apTotal = 0;
        var crTotal = 0;
        var cinTotal = 0
        var coutTotal = 0;
        var balTotal = 0;
        var overallTotal = 0;

        $('#cash_out').change(function() {
          calculateCashOut();
        });

        $('#n_cash').change(function() {
          calculateCashOut();
        });

        $('#cash_count').change(function() {
          calculateCashCount();
        });

        $('#btnSubmitClosing').on('click', function() {

            var thisFrm = $('#closeStoreFrm').serialize();

              $.ajax ({
                url : '{{ url("store/closing-details/save") }}'
                ,method : 'GET'
                ,data: thisFrm
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){
                
               alert('Report has been saved!');
               location.reload();

              }).fail( function(response) {
                alert('Sorry, Green Card Perk Number is not valid.');
                b.innerHTML = 'Member Info: ';
                $('#displayCardNum').val('');
              });
        })

        function calculateCashOut() {

          $('.tr_listExpenses').remove();

          var cash_out = $('#cash_out').val();
          var n_cash = $('#n_cash').val();

          ccout = parseFloat(crTotal) + parseFloat(cash_out);
          coutTotal = parseFloat(apTotal) + parseFloat(ccout) + parseFloat(n_cash);

          $('#tr_cashout_total').remove();

          $('#closeStoreTbl2').append(
            '<tr id="tr_cashout_total" class="tr_closing_details">'+
            '<td align="right" width="60%"><b>TOTAL CASH-OUT</b></td>'+
            '<td>PHP '+ parseFloat(coutTotal).toFixed(2) + '</td>' +
            '</tr>'
          );

          calculateCashBalance();
        }

        function calculateCashBalance() {

          balTotal = parseFloat(cinTotal) - parseFloat(ccout);

          $('#tr_cashount_total').remove();

                  $('#tr_closeStoreTbl3').append(
                    '<td id="tr_cashount_total" class="tr_closing_details">'+
                    'PHP '+ parseFloat(balTotal).toFixed(2) +'</td>'
                  );

        }

        function calculateCashCount() {
          
          var cash_count = $('#cash_count').val();
          
          overallTotal = parseFloat(cash_count) - parseFloat(balTotal);
          $('#tr_deficit_excess').remove();

          $('#closeStoreTbl3').append(
                    '<tr id="tr_deficit_excess" class="tr_closing_details">'+
                    '<td width="60%">Deficit/Excess</td>'+
                    '<td>PHP '+ parseFloat(overallTotal).toFixed(2) + '</td>' +
                    '</tr>'
                  );
        }

        function printThis(id, title) {

          var content = document.getElementById(id).innerHTML;
          var mywindow = window.open('', title, 'width=8.5');
          var contentStyle = '<style>.table{width:100%;margin-bottom:1rem;background-color:transparent}.table td,.table th{padding:.75rem;vertical-align:top;border-top:1px solid #dee2e6}.table thead th{vertical-align:bottom;border-bottom:2px solid #dee2e6}.table tbody+tbody{border-top:2px solid #dee2e6}.table .table{background-color:#fff}.table-sm td,.table-sm th{padding:.3rem}.table-bordered{border:1px solid #dee2e6}.table-bordered td,.table-bordered th{border:1px solid #dee2e6}.table-bordered thead td,.table-bordered thead th{border-bottom-width:2px}</style>';
          mywindow.document.write('<html><head><title>'+title+'</title>');
          mywindow.document.write(contentStyle);
          mywindow.document.write('</head><body >');
          mywindow.document.write(content);
          mywindow.document.write('</body></html>');

          mywindow.document.close();
          mywindow.focus()
          mywindow.print();
          mywindow.close();
        }

        function deleteUser(id,flag) {

            var c = confirm('Are you sure you want to delete this record?')

            if(c) {
              $.ajax ({
                url : '{{ url("users/delete/this") }}'
                ,method : 'GET'
                ,data: {
                  id:id,flag:flag
                }
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){
                
                    alert('Record has been deleted!');
                    location.reload();
                
              }).fail( function(response) {
                alert('Sorry, there was an error retrieving data.');
              });
            }
        }
 
    function toProperCase(str) {
        return str.replace(
            /\w\S*/g,
            function(txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            }
        );
    }
    </script>
@yield('script')
</html>