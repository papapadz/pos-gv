@extends('layouts.app')


@section('content')
<div class="card">
    <div class="card-body">
        <button id="addUserButton" class="btn btn-sm btn-success" style="float: right;"><i class="fa fa-plus"></i></button>
        <table id="userTable" class="table table-striped table-bordered" style="width:100%"> 
                <thead>
                    <tr>
                        <th>Perk ID No.</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Birthdate</th>
                        <th>Contact No.</th>
                        <th>Total Points</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $c)
                    <tr>
                        <td>{{$c->card_num}}</td>
                        <td>{{$c->last_name}}</td>
                        <td>{{$c->first_name}}</td>
                        <td>{{$c->birthday}}</td>
                        <td>{{$c->contact_num}}</td>
                        <td>{{$c->total_points}}</td>
                        <td>
                            <a href="javascript:void(0);" class="btn btn-sm btn-primary foradminonly" onclick="editUser('{{$c->perk_id}}',1);"><i class="fa fa-edit"></i></a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-danger foradminonly" onclick="deleteUser('{{$c->perk_id}}',1);"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
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
            <div id="div_pointsInput" hidden="true">
            <label>Points</label>
                <input type="number" min="0" class="form-control" name="pointsInput" id="pointsInput">
            </div>
        </form>
      </div>
      <div class="modal-footer">
                <button class="btn btn-success" id="saveUserButton" >Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section('script')

<script type="text/javascript">
$(document).ready(function() {
    
    var table = $('#userTable').DataTable();
   
    $('#saveUserButton').on('click', function() {
        
        var cn = $('#cardNumInput').val();
        var ln = $('#lastnameInput').val();
        var fn = $('#firstnameInput').val();
        var bd = $('#bdayInput').val();
        var co = $('#contactNumInput').val();

        if(cn.length>0 && ln.length>0 && fn.length>0 && bd.length>0 && co.length>0) {
            var thisform = $('#editUserForm').serialize();

              $.ajax ({
                url : '{{ url("users/new/save?") }}'+thisform
                ,method : 'GET'
                ,data: { flag:1 }
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){
                
                alert('Record has been saved!')
                location.reload();
                
              }).fail( function(response) {
                alert('Sorry, there was an error in saving your record');
              });
          } else
            alert('Please fill up all the fields!');
    });

    $('#addUserButton').on('click', function() {
        $('#useridInput').val('');
        $('#cardNumInput').val('');
        $('#lastnameInput').val('');
        $('#firstnameInput').val('');
        $('#bdayInput').val('');
        $('#contactNumInput').val('');
        $('#pointsInput').val(0);
        $('#div_pointsInput').prop('hidden',true);
        $('#editCustomerModal').modal('show');
    });

    $('#cardNumInput').on('change', function() {

        var cardNum = $('#cardNumInput').val();
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
                    
                    alert('Sorry, card number already exists!');
                    $('#cardNumInput').val('');
                    
                }

              }).fail( function(response) {
                alert('Sorry, there was an error retrieving data.');
              });
    });

});

function editUser(id,flag) {

        $('#div_pointsInput').prop('hidden',false);
        $('#useridInput').val('');
        $.ajax ({
                url : '{{ url("getters/users/find/get") }}'
                ,method : 'GET'
                ,data: { id:id, flag:flag }
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){
                
                $('#useridInput').val(response['perk_id']);
                $('#cardNumInput').val(response['card_num']);
                $('#lastnameInput').val(response['last_name']);
                $('#firstnameInput').val(response['first_name']);
                $('#bdayInput').val(response['birthday']);
                $('#contactNumInput').val(response['contact_num']);
                $('#pointsInput').val(response['total_points']);
                $('#editCustomerModal').modal('show');
                
              }).fail( function(response) {
                alert('Sorry, there was an error in retrieving data!');
              });

        
    }

</script>
@endsection