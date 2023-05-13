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

                <h3>Orders</h3>

               

                   <a href="{{ route('addOrder') }}" class="btn btn-primary">Add Order</a><br><br>

                    <table class="table table-responsive" id="productTable">

                    <thead>
                        <tr>

                         <th>ID</th>

                        <th>
                                Order Id
                            </th>
                            <th>
                                Customer Name
                            </th>
                            <th>
                                Customer Phone
                            </th>
                            <th>
                                Net Amount
                            </th>
                            <th>
                                Order Date
                            </th>
                            <th>
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($orders as $key=>$order)

                        <tr>
                            <td>{{ ($key+1) }}</td>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->customer_Phone }}</td>
                            <td>{{ $order->net_amount }}</td>
                            <td>{{ date('d M Y',strtotime($order->order_date)) }}</td>
                            <td><a class="btn btn-primary" href="{{ route('editOrder',['id'=>base64_encode($order->id)]) }}">Edit</a>
                                <button class="btn btn-danger delete-order" type="button" data_delete_url="{{ route('deleteOrder',['id'=>base64_encode($order->id)]) }}">Delete Order</button>
                                <a class="btn btn-default" href="{{ route('invoice',['id'=>base64_encode($order->id)]) }}" target="_blank">Invoice</a>
                        </td>
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
          $(document).on("click",".delete-order",function()
          {

            var url=$(this).attr('data_delete_url');
            swal({
            title: "Are you sure?",
            text: "You will not be able to recover this order!",
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
        swal("Cancelled", "Your order is safe :)", "error");
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