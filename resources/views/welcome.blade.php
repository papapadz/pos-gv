@extends('layouts.app')

@section('styles')
<style type="text/css">
    #wrapper {
        text-align: center;
    }
    #yourdiv {
        display: inline-block;
        width: 18rem;
        height: auto;
        box-shadow: 1px 1px 1px grey;
    }
</style>
@endsection

@section('content')
<div id="wrapper">
<div id="yourdiv" class="card">
  <img class="card-img-top" src="{{asset('img/logo/beginning-banner.jpg')}}" height="200rem;">
  <div class="card-body">
    <form id="frm_beginning_balance">
        <label><b>Beginning Balance</b></label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon3">PHP </span>
          </div>
          <input class="form-control" type="number" value=0.00 min=0 id="beginning_balance" name="beginning_balance">
        </div>
    </form>
  </div>
  <div class="card-footer">
    <button id="btn_open_store" class="btn btn-primary">Open Registry</button>
</div>
</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {

        $('#btn_open_store').on('click', function() {

            var bb = $('#beginning_balance').val();

            if(bb>=0) {

                var c = confirm('Are you sure you want to enter this amount for today?');

                if(c) {

                    $.ajax ({
                    url : '{{ url("beginning-balance/set/save") }}'
                    ,method : 'GET'
                    ,data: {bb:bb}
                    ,cache : false
                    ,beforeSend:function() {
                    //$('#loadModal').modal({ backdrop: 'static' });
                    }
                  }).done( function(response){

                    location.reload(); 
                    
                  }).fail( function(response) {
                    alert('Sorry, there was an error in saving your record.');
                  });
                }
            } else
                alert('Please enter a valid beginning balance for today!')
        });
    });
</script>
@endsection