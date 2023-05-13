<html>
    <head>
        <title>WAC</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ asset('dropify/dist/css/dropify.min.css') }}">
    </head>

    <body>

        
    @extends('layout')


        <div class="container">

       
       
      
             <br>
           

           <div class="row">

         

               <div class="col-md-6 col-md-push-3">

                
                  @if(Session::has('error'))

                    <div class="alert alert-danger">

                        {{ Session::get('error') }}

                    </div>

                    @endif
                     <h1>Edit Product</h1>

                    

                       <a class="btn btn-primary" href="{{ url('/products') }}">Products</a>

                    <form action="{{ route('updateProduct') }}" method="post" enctype="multipart/form-data">

                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                       @csrf

                       <div class="form-group">

                        <label>Category<span style="color:red">*</span></label><br>
                       

                        <select name="category" class="form-control" id="category">

                             <option value="">Choose</option>

                             @foreach($categories as $category)

                                <option value="{{ $category->id }}">{{ $category->name }}</option>

                             @endforeach

                        </select>


                        </div>

                        


                       <div class="form-group">

                          <label>Product Name<span style="color:red">*</span></label><br>
                          <input type="text" name="name" class="form-control" value="{{ $product->name }}" >

                       
                       </div>

                       <div class="form-group">

                            <label> Image<span style="color:red">*</span></label><br>
                            <input type="file" name="image"  class="dropify" data-default-file="{{ asset('/storage/products/'.$product->image) }}">


                        </div>

                        <div class="form-group">

                            <label>Price<span style="color:red">*</span></label><br>
                            <input type="number" name="price" class="form-control" value="{{ $product->price }}">


                        </div>

                        @if(Session::has('success'))

                            <div class="alert alert-success">

                                {{ Session::get('success') }}

                            </div>

                            @endif

                        <div class="form-group col-md-6">

                            <button class="btn btn-success" type="submit">Submit</button>
                            <button class="btn btn-info" type="reset">Cancel</button>


                        </div>

                       
                    </form>

                   

               </div>

              

               


             

           </div>

        </div>

        <!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script src="{{ asset('dropify/dist/js/dropify.js') }}"></script>

<script>

    $(".dropify").dropify();
</script>


<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script>

    $("#category").val("{{ $product->category }}");
</script>
    </body>
</html>