@extends('layouts.app')


@section('content')
<div class="card">
    <div class="card-body">
        <button id="addUserButton" class="btn btn-sm btn-success" style="float: right;"><i class="fa fa-plus"></i></button>
        <table id="userTable" class="table table-striped table-bordered" style="width:100%"> 
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Birthdate</th>
                        <th>Username</th>
                        <th>User Level</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                    <tr>
                        <td>{{$u->id}}</td>
                        <td>{{$u->last_name}}</td>
                        <td>{{$u->first_name}}</td>
                        <td>{{$u->birthdate}}</td>
                        <td>{{$u->username}}</td>
                        <td>
                            @if($u->user_level==1)
                                Administrator
                            @else
                                User
                            @endif
                        </td>
                        <td>
                            <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="editUser('{{$u->id}}',0);"><i class="fa fa-edit"></i></a>
                            <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="deleteUser('{{$u->id}}',0);"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
            </table>
    </div>
</div>

<div id="editUserModal" class="modal fade" role="dialog" state="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="editUserForm" >
            <input type="text" id="useridInput" name="useridInput" hidden />
            <label>Last Name</label>
            <input type="text" class="form-control" name="lastnameInput" id="lastnameInput">
            <label>First Name</label>
            <input type="text" class="form-control" name="firstnameInput" id="firstnameInput">
            <label>Birthdate</label>
            <input type="date" class="form-control" name="bdayInput" id="bdayInput">
            <hr>
            <label>Username</label>
            <input type="text" class="form-control" name="usernameInput" id="usernameInput">
            <div class="form-group">
              <label for="product_category">User Level</label>
              <select class="form-control" name="userlevelSelect" id="userlevelSelect">
                <option value="1">Admin</option>
                <option value="0">User</option>
              </select>
            </div>
            <a href="javascript:void(0);" class="btn btn-primary" id="changePasswordButton">Change Password</a>
            <a href="javascript:void(0);" class="btn btn-danger" id="cancelPasswordButton" hidden="true">Cancel</a>
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
        
        var thisform = $('#editUserForm').serialize();

              $.ajax ({
                url : '{{ url("users/new/save?") }}' +thisform
                ,method : 'GET'
                ,data: {flag:0}
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){
                
                alert('Changes has been saved!')
                location.reload();
                
              }).fail( function(response) {
                alert('Sorry, there was an error in saving your record');
              });
    });

    $('#addUserButton').on('click', function() {
        $('#useridInput').val('');
        $('#changePasswordDiv').remove();
        $('#lastnameInput').val('');
        $('#firstnameInput').val('');
        $('#bdayInput').val('');
        $('#userlevelSelect').val('');
        $('#usernameInput').val('');
        $('#changePasswordButton').prop('hidden', true);
        $('#cancelPasswordButton').prop('hidden', true);
        
        $('#editUserForm').append('<div id="changePasswordDiv"><label>Password</label><input type="password" class="form-control" name="passwordInput" id="passwordInput"></div>');
        $('#editUserModal').modal('show');
    });

    $('#changePasswordButton').on('click', function() {
        $('#changePasswordButton').prop('hidden', true);
        $('#cancelPasswordButton').prop('hidden', false);
        
        $('#editUserForm').append('<div id="changePasswordDiv"><label>New Password</label><input type="password" class="form-control" name="passwordInput" id="passwordInput"></div>');
    });

    $('#cancelPasswordButton').on('click', function() {
        $('#changePasswordButton').prop('hidden', false);
        $('#cancelPasswordButton').prop('hidden', true);
        $('#changePasswordDiv').remove();
    });

});

function editUser(id,flag) {

        $('#changePasswordButton').prop('hidden', false);
        $('#cancelPasswordButton').prop('hidden', true);
        $('#changePasswordDiv').remove();
        $('#useridInput').val('');

        $.ajax ({
                url : '{{ url("getters/users/find/get") }}'
                ,method : 'GET'
                ,data: {id:id,flag:flag}
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){
                
                $('#useridInput').val(response['id']);
                $('#lastnameInput').val(response['last_name']);
                $('#firstnameInput').val(response['first_name']);
                $('#bdayInput').val(response['birthdate']);
                $('#userlevelSelect').val(response['user_level']);
                $('#usernameInput').val(response['username']);
                $('#editUserModal').modal('show');
                
              }).fail( function(response) {
                alert('Sorry, there was an error in retrieving data!');
              });

        
    }



</script>
@endsection