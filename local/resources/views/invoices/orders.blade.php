<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Invoice</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <style type="text/css">
        table { border-collapse: collapse;}
    </style>
</head>
<body>
    <section class="row">
    <table height="300">
                <tr >
                    
                    <td width="110">
                    <img src="http://benson-shoes.com/images/logo.jpg" alt="" width="125">
                    </td>
                    <td width="300">
                        <strong>Benson shoes</strong>
                        <p> www.benson-shoes.com / mail@benson-shoes.com<br/>
                            Phone : +212 522 246 485 / fax : +212 522 248 667<br/>
                        </p>
                    </td>
                    
                  
                </tr>
            </table>
    </section>
    <br/><br/>
    <section class="row">
        <div class="pull-left" >
            <strong>Information de livraison : </strong><br/>
            Nom du client : {{$customer->name}} <br />
            {{ $address->address_1 }} {{ $address->address_2 }} <br />
            {{ $address->city }}  <br />
            {{ $address->country }} {{ $address->zip }}
        </div>
        <div class="pull-right">
            @if(isset($invoice))
              <strong>facture #000{{ $invoice }}</strong>
            @endif
            <p>Date: {{ $order->created_at }} <br/>
                @if($order->payment_method_id == 2)
                    Paiement à la livraison
                @else 
                    Paiement en ligne 
                @endif
            </p>
        </div>
    </section>
    <section class="row">
        <div class="col-md-12">
            <h2>Details</h2>
            <table class="table table-striped" width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Quantité</th>
                        <th>Prix</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{$product->sku}}</td>
                        <td>{{$product->name}}</td>
                        <td style="font-size:10px">{!!$product->description!!}</td>
                         <td>
                             @if(isset($product->pivot))
                                {{$product->pivot->quantity}}
                            @endif
                        </td>
                        <td>{{$product->price}} DH</td>

                        <td> 
                            
                            @if(isset($product->pivot))
                            
                            {{number_format($product->price * $product->pivot->quantity, 2)}} DH</td>
                            @endif
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Montant TTC:</td>
                        <td>{{$order->total_products}} DH</td>
                    </tr>
                    
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Réduction ({{$order->discounts}}%):</td>
                        <td>{{($order->total_products*$order->discounts/100)}} DH</td>
                    </tr>
                    
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                   
                    <tr>
                        <td>Dont TVA (20%):</td>
                        <td>{{number_format(($order->total/1.2)*20/100, 2)}} DH</td>
                        <td></td>
                        <td></td>
                        <td><strong>Total net TTC:</strong></td>
                        <td><strong>{{$order->total_products-($order->total_products*$order->discounts/100)}} DH</strong></td>
                    </tr>
                   
                </tfoot>
            </table>
        </div>
    </section>
    <br/><br/>
    <section class="row" style="text-align:center;font-size:8px;">
        S.AR.L. au Capital de 3.893.670 DHS<br/>22, rue le Catelet – Casablanca 20300 – Morocco<br/>R. C. : 89057 – CNSS : 2684539 - I. F. : 01601753 – Patente : 31211548 ICE:001636527000084
    </section>
</body>
</html>