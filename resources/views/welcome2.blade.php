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
  <img class="card-img-top" src="{{asset('img/logo/close.png')}}" style="padding: .5rem;" height="200rem;">
  <div class="card-body">
    <b>Register Closed</b>
  </div>
  <div class="modal-footer">
    <button style="width: 100%" id="btn-admin-open" class="btn btn-primary foradminonly">Open Registry</button>
  </div>
</div>
</div>
@endsection

@section('script')
<script type="text/javascript">

$('#btn-admin-open').on('click', function() {

  var c = confirm('Are you sure you want to reopen the registry? This will delete the Cash In/Cash Out Report for today.');
                if(c) {
                
                  $.ajax ({
                    url : '{{ url("beginning/reset") }}'
                    ,method : 'GET'
                    ,cache : false
                    ,beforeSend:function() {
                    //$('#loadModal').modal({ backdrop: 'static' });
                    }
                  }).done( function(response){

                    location.reload();

                  }).fail( function(response) {
                    alert('Sorry there was an error in retrieving the data.');
                  });  
                }
});
  
</script>
@endsection