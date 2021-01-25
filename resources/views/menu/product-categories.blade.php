@extends('layouts.app')


@section('content')
<div class="card">
    <div class="card-body">
      <button class="btn btn-sm btn-success" style="float: right;" onclick="editCat(0,0);"><i class="fa fa-plus"></i></button>
      <table id="catTable" class="table table-striped table-bordered" style="width:100%"> 
              <thead>
                  <tr>
                      <th>Category</th>
                      <th>Description</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($productCat as $pc)
                  <tr>
                      <td>{{$pc->category}}</td>
                      <td>{{$pc->description}}</td>
                      <td>
                          <a href="javascript:void(0);" class="btn btn-sm btn-primary" onclick="editCat('{{$pc->category_id}}',1);"><i class="fa fa-edit"></i></a>
                          <a href="javascript:void(0);" class="btn btn-sm btn-danger" onclick="deleteCat('{{$pc->category_id}}');"><i class="fa fa-trash"></i></a>
                      </td>
                  </tr>
                  @endforeach
              </tbody>
          </table>
    </div>
</div>

<div id="editCatModal" class="modal fade" role="dialog" state="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="editCatForm" >
            <input type="text" id="catId" name="catId" hidden />
            <label>Category Name</label>
            <input type="text" class="form-control" name="catName" id="catName">
            <label>Description</label>
            <textarea class="form-control" id="catDesc" name="catDesc"></textarea>
        </form>
      </div>
      <div class="modal-footer">
                <button class="btn btn-success" id="saveCatButton" onclick="saveCat();" >Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section('script')

<script type="text/javascript">
$(document).ready(function() {
    
    var table = $('#catTable').DataTable();

});

function saveCat() {
    var thisForm = $('#editCatForm').serialize();

     $.ajax ({
                url : '{{ url("product/categories/new/save") }}'
                ,method : 'GET'
                ,data: thisForm
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){
                
                alert('Record has been saved!');
                location.reload();

              }).fail( function(response) {
                alert('Sorry, there was an error saving the record.');
              });    
}

function editCat(id,flag) {

    $('.form-control').val('');

    if(flag==1) {

        $.ajax ({
                url : '{{ url("getters/product/category/get") }}'
                ,method : 'GET'
                ,data: {id:id}
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){
                
                $('#catId').val(response['category_id']);
                $('#catName').val(response['category']);
                $('#catDesc').val(response['description']);

              }).fail( function(response) {
                alert('Sorry, there was an error retrieving data.');
              });
    }

        $('#editCatModal').modal('show');
    
}

function deleteCat(id) {

    var c = confirm('Are you sure you want to delete this record?');

    if(c) {
        $.ajax ({
                url : '{{ url("products/categories/delete/this") }}'
                ,method : 'GET'
                ,data: {id:id}
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){
                
                alert('Record has been deleted!');
                location.reload();

              }).fail( function(response) {
                alert('Sorry, there was an error deleting the record.');
              });  
    }  
}

</script>
@endsection