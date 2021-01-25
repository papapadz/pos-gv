@extends('layouts.app')


@section('content')
<div class="card">
    <div class="card-body">
        <button id="btnAddProduct" class="btn btn-sm btn-success" style="float: right;"><i class="fa fa-plus"></i></button>
        <table id="example" class="table table-striped table-bordered" style="width:100%"> 
                <thead>
                    <tr>
                        <th>Product ID No.</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Unit Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                    <tr>
                        <td class="pid">{{$p->product_id}}</td>
                        <td>{{$p->product_name}}</td>
                        <td>{{$p->category}}</td>
                        <td><?php echo number_format((float)$p->unit_price, 2, '.', ''); ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" onclick="editProduct('{{$p->product_id}}')"><i class="fa fa-edit"></i></button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteProduct('{{$p->product_id}}');"><i class="fa fa-trash"></i></button>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tblSalesItems"></tbody>
            </table>
        </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="addProductModal" class="modal fade" role="dialog" state="display:none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <img class="card-img-top" height="300rem" id="pModalimage" src="#">
      <div id="pModalHeader" class="modal-header">
        Product Details
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="frmAddProduct" method="POST" action="{{url('products/new/save')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="text" id="inputpid" name="inputpid" hidden />
            <label>Product Name</label>
            <input type="text" class="form-control" name="product_name" id="product_name">
            <div class="form-group">
              <label for="product_category">Product Category</label>
              <select class="form-control" name="product_category" id="product_category">
                @foreach($prodCat as $cat)
                <option value="{{$cat->category_id}}">{{$cat->category}}</option>
                <!-- 
                <option value="1">Pasta</option>
                <option value="2">Drinks</option>
                <option value="3">Sandwiches</option>
                <option value="4">Desserts</option>
                <option value="6">Food Itinerary</option>
                <option value="5">Mercato</option>
                -->
                @endforeach
              </select>
            </div>
            <label>Unit Price (PHP)</label>
            <input type="number" class="form-control" name="unit_price" id="unit_price"><hr>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="image" name="image">
                <label class="custom-file-label" id="custom-file-label">Choose Image File</label>
            </div>
        
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" >Save</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section('script')

<script type="text/javascript">
$(document).ready(function() {
    
    var table = $('#example').DataTable({
        "order": [
        [ 2, "asc" ],
        [ 1, "asc" ]
        ]
    });

    $('#btnAddProduct').on('click', function() {
        var element = document.getElementById('custom-file-label');
        element.innerHTML = "Choose Image File";
        $('#product_name').val('');
        $('#product_category').val(1);
        $('#unit_price').val(0);
        $('#inputpid').val(0);

        $('#pModalHeader').prop('hidden',false);
        $('#pModalimage').prop('hidden',true);
        $('#addProductModal').modal('show');
    });

    $('#btnSubmitNewProduct').on('click', function() {

        var thisform = $('#frmAddProduct').serialize();

              $.ajax ({
                url : '{{ url("products/new/save") }}'
                ,method : 'POST'
                ,data: thisform
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){
                
                alert('New product details saved!')
                location.reload();
                
              }).fail( function(response) {
                alert('Sorry, there was an error in saving your record');
              });
    });

$('input[type="file"]').change(function(e){
            var fileName = e.target.files[0].name;
            var element = document.getElementById('custom-file-label');
            element.innerHTML = fileName;
        });
});

function editProduct(id) {
  
        $('#inputpid').val(id);
        $('#pModalimage').prop('hidden',false);
        $('#pModalHeader').prop('hidden',true);
        
        $.ajax ({
                url : "{{ url('getters/product/find/get') }}"
                ,method : 'GET'
                ,data: {id:id}
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){
                
                $('#pModalimage').prop('src',response["img_file"]);
                $('#addProductModal').modal('show');
                var element = document.getElementById('custom-file-label');
                element.innerHTML = response["img_file"];

                $('#product_name').val(response['product_name']);
                $('#product_category').val(response['product_category']);
                $('#unit_price').val(response['unit_price']);

              }).fail( function(response) {
                alert('Sorry, there was an error in retrieving data!');
              });
}

function deleteProduct(id) {

    var c = confirm('Are you sure you want to delete this product?');

    if(c) {
            
            $.ajax ({
                url : '{{ url("products/delete/this") }}'
                ,method : 'GET'
                ,data: { id:id }
                ,cache : false
                ,beforeSend:function() {
                //$('#loadModal').modal({ backdrop: 'static' });
                }
              }).done( function(response){
                
                alert('Product has been deleted!')
                location.reload();
                
              }).fail( function(response) {
                alert('Sorry, there was an error in saving your record');
              });
    }

}

</script>
@endsection