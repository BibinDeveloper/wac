<html>
    <head>
        <title>Invoice</title>
    </head>

    <body>

       <center>

        <table style="margin-top:200px;border-style:solid;" border="10px solid" >

            <tr >

                <td style="height:50px;min-width:100px;">Order Id</td>
                <td style="min-width:600px;">#{{ $order->order_id }}</td>
            </tr>

            <tr>

                <td style="height:50px;min-width:100px;">Products</td>
                <td style="min-width:600px;">

                    @foreach($order->Products as $key=>$product)
                        {{ ($key+1) }}. {{$product->Product->name}} x {{$product->qty}}={{$product->amount}}<br>
                    @endforeach
                </td>
            </tr>

            <tr >
                <td style="height:50px;min-width:100px;">Total</td>
                <td style="min-width:600px;">{{ $order->net_amount }}</td>
            </tr>


        </table>

      </center>
    </body>
</html>