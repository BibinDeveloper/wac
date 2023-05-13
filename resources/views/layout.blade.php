
   

   

   <div class="container">

        <div class="row">

        <center><a class="btn btn-info"  href="{{ route('products') }}">Products</a>&nbsp;<a class="btn btn-info"  href="{{ route('orders') }}">Orders</a></center>

                <form action="{{ url('/logout') }}" method="post">

                            @csrf

                            <button class="btn btn-warning" id="logout" >Log Out</button>
                </form>&nbsp;&nbsp;

        </div>

  

  
   
   
  

   </div>


