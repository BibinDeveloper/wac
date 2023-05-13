<html>
    <head>
        <title>WAC</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'></link>  
    </head>

    <body>

        
    @extends('layout')


        <div class="container">

       
      

           <div class="row">

              @if(Session::has('error'))

                 <div class="alert alert-danger">

                        {{  Session::get('error') }}
                 </div>

              @endif

              @if(Session::has('success'))

                 <div class="alert alert-success">

                        {{  Session::get('success') }}
                 </div>

              @endif


              <div class="col-md-11">

                <h3>Products</h3>

               

                   <a href="{{ route('addProduct') }}" class="btn btn-primary">Add Product</a><br><br>

                    <table class="table table-responsive" id="productTable">

                    <thead>
                        <tr>

                            <th>ID</th>
                            <th>
                                Product Name
                            </th>
                            <th>
                                Category
                            </th>
                            <th>
                                Price
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($products as $key=>$product)

                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>{{ $product->name}}</td>
                            <td>{{ $product->Category->name }}</td>
                            <td>{{ $product->price }}</td>
                            <td><a href="{{ route('editProduct',['id'=>base64_encode($product->id) ]) }}" class="btn btn-primary">Edit</a>
                            <button  class="btn btn-danger delete-product" data_delete_url="{{ route('deleteProduct',['id'=>base64_encode($product->id)])  }}">Delete</button></td>
                        </tr>

                        @endforeach
                    </tbody>


                    </table>

              </div>

              

           </div>

        </div>

        <!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>  

<script>

    $(document).ready(function()
    {
          $(document).on("click",".delete-product",function()
          {

            var url=$(this).attr('data_delete_url');
            swal({
            title: "Are you sure?",
            text: "You will not be able to recover this product!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel please!",
            closeOnConfirm: false,
            closeOnCancel: false
        }).then(result => {
        if (result.value) {
             
            window.location.href=url;
        }
        else {
        swal("Cancelled", "Your product is safe :)", "error");
        }
    });
          });
    });

</script>

<script>

    $("#productTable").DataTable();
</script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


    </body>
</html>