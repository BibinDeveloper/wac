<html>
    <head>
        <title>WAC</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'></link>  

        <style>

.modal {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url('http://i.stack.imgur.com/FhHRx.gif') 
                50% 50% 
                no-repeat;
}

/* When the body has the loading class, we turn
   the scrollbar off with overflow:hidden */
body.loading .modal {
    overflow: hidden;   
}

/* Anytime the body has the loading class, our
   modal element will be visible */
body.loading .modal {
    display: block;
}
        </style>
    </head>

    <body>

    @extends('layout')

        <div class="container">

      
             <br>
           

           <div class="row">

          
          


               <div class="col-md-6 col-md-push-3">

              
    

                  @if(Session::has('success'))

                     <div class="alert alert-success">

                          {{ Session::get('success') }}

                     </div>

                  @endif

                  @if(Session::has('error'))

                    <div class="alert alert-danger">

                        {{ Session::get('error') }}

                    </div>

                    @endif
                     <h1>Edit Order</h1>

                    

                       <a class="btn btn-primary" href="{{ url('/orders') }}">Orders</a>
                      

                    <form action="{{ route('updateOrder') }}" method="post" enctype="multipart/form-data" id="store_order">

                       <input type="hidden" value="{{ $order->id }}" name="order_id">

                       @csrf

                       <div class="form-group">

                            <label>Customer Name<span style="color:red">*</span></label><br>
                            <input type="text" name="customer_name" class="form-control"  value="{{ $order->customer_name }}" >


                        </div>

                        <div class="form-group">

                                <label>Customer Phone Number<span style="color:red">*</span></label><br>
                                <input type="text" name="customer_phone" class="form-control" value="{{ $order->customer_Phone }}" >


                        </div>

                        <div id="product_area">


                           @foreach($products_array as $key=>$p)


                           <div class="product">

                                <div class="form-group">

                                    <label>Product<span style="color:red">*</span></label><br>
                                    <select name="product[]" class="form-control" required>
                                        <option value="">Choose</option>

                                        @foreach($products as $product)

                                         

                                        <option  @if($product->id==$p)

                                        selected

@endif  
value="{{ $product->id}}">{{ $product->name }}</option>

                                        @endforeach

                                    </select>


                                </div>

                                <div class="form-group">

                                    <label>Quantity<span style="color:red">*</span></label><br>
                                    <select name="qty[]" class="form-control">
                                        <option value="">Choose</option>

                                        @for($i=0;$i<=20;$i++)

                                        <option value="{{ $i }}" @if($i==$qty_array[$key]) selected @endif>{{ $i }}</option>

                                        @endfor

                                    </select>


                                </div>

                               

                                <button type="button" style="float:right" class="btn btn-danger remove-product">Remove</button>
                                   

                               <br>



                            </div>


                           @endforeach

                    

                               


                        </div>

                       
                        <div class="form-group">

                            <button class="btn btn-default" id="add_more" type="button" >Add More</button>

                        </div>


        

                        <div class="form-group col-md-6">

                            <button class="btn btn-success" type="submit">Submit</button>
                            <button class="btn btn-info" type="reset">Cancel</button>


                        </div>

                       
                    </form>

                    @if(Session::has('success'))

                        <div class="alert alert-success">

                            {{ Session::get('success') }}

                        </div>

                    @endif

               </div>

              

              
           </div>

        </div>

        <div class="modal"><!-- Place at bottom of page --></div>

 

        <!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>

<script>

$(document).ready(function()
{

    $(document).on("click","#add_more",function()
    {
        $("#product_area").append("<div class='product'><div class='form-group'><label>Product<span style='color:red'>*</span></label><br><select name='product[]' class='form-control' required><option value=''>Choose</option> @foreach($products as $product)<option value='{{ $product->id}}'>{{ $product->name }}</option>@endforeach</select></div><div class='form-group'><label>Quantity<span style='color:red'>*</span></label><br><select name='qty[]' class='form-control'><option value=''>Choose</option>@for($i=0;$i<=20;$i++)<option value='{{ $i }}'>{{ $i }}</option>@endfor</select></div><button type='button' style='float:right' class='btn btn-danger remove-product'>Remove</button></div><br>");
    });

    $(document).on("click",".remove-product",function()
    {
          $(this).parent().remove();
    });

    $(document).on("submit","#store_order",function(e)
    {
        e.preventDefault();

        var form_data=new FormData($(this)[0]);

        $.ajax({
            headers:{'X-CSRF-TOKEN':"{{ csrf_token() }}"},
            type:"POST",
            url:"{{ route('updateOrder') }}",
            data:form_data,
            dataType:"JSON",
            contentType:false,
            processData:false,
            success:function(response)
            {
                $("body").removeClass("loading");

                if(response.status=="success")
                {
                    window.location.reload();
                }

                else{

                    swal("Error",response.message,"error");
                }
            },
            error:function()
            {
                $("body").removeClass("loading");
                swal("Error","Something went wrong","error");
            },
            beforeSend:function()
            {
                $("body").addClass("loading");
            }

        });


    });
      
});
</script>



<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </body>
</html>