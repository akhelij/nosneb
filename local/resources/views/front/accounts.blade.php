@extends('layouts.front.app')

@section('content')
<hr>
    <!-- Main content -->
    <section class="container content">
      
        <div class="row">
            <div class="col-md-8 col-md-offset-2" style="background-color:#ffffff;padding:5%">
                <div>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
                        <li role="presentation"><a href="#orders" aria-controls="orders" role="tab" data-toggle="tab">Commandes</a></li>
                        <li role="presentation"><a href="#address" aria-controls="address" role="tab" data-toggle="tab">Adresses</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content customer-order-list">
                        <div role="tabpanel" class="tab-pane active" id="profile">
                            <form class="form-horizontal" role="form" id="subscribe" method="POST" action="{{ route('register') }}">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        <label for="name" class="col-md-4 control-label">Nom</label>

                                        <div class="col-md-6">
                                            <input id="name" type="text" class="form-control" name="name" value="{{ $customer->name }}" autofocus>

                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="col-md-4 control-label">E-Mail </label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control" name="email" value="{{ $customer->email }}">

                                            @if ($errors->has('email'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                   
                                    

                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password" class="col-md-4 control-label">Mot de passe actuel</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control" name="oldpassword">

                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                   <div class="form-group">
                                        <label for="password-confirm" class="col-md-4 control-label">Nouveau mot de passe</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control" name="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password-confirm" class="col-md-4 control-label">Confirmation du mot de passe</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12" style="margin-top:5%">
                                            <button type="submit" id="submit" class="btn btn-primary pull-right" style="width:100%">
                                                Enregistrer les changements
                                            </button>
                                        </div>
                                    </div>
                                </form>
      
                                
                            
                        </div>
                        <div role="tabpanel" class="tab-pane" id="orders">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td class="col-md-3">Date</td>
                                    <td class="col-md-2">Courrier</td>
                                    <td class="col-md-2">Total</td>
                                    <td class="col-md-2">Status</td>
                                </tr>
                                </tbody>
                                <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>
                                            <a data-toggle="modal" data-target="#order_modal_{{$order['id']}}" title="Show order" href="javascript: void(0)">{{ date('M d, Y h:i a', strtotime($order['created_at'])) }}</a>
                                            <!-- Button trigger modal -->
                                            <!-- Modal -->
                                            <div class="modal fade" id="order_modal_{{$order['id']}}" tabindex="-1" role="dialog" aria-labelledby="MyOrders">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">Reference #{{$order['reference']}}</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table">
                                                                <thead>
                                                                    <th>Adresse</th>
                                                                    <th>Payment</th>
                                                                    <th>Total</th>
                                                                    <th>Status</th>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <address>
                                                                                <strong>{{$order['address']->alias}}</strong><br />
                                                                                {{$order['address']->address_1}} {{$order['address']->address_2}}<br>
                                                                            </address>
                                                                        </td>
                                                                        <td>{{$order['payment']->name}}</td>
                                                                        <td>{{$order['total']}}</td>
                                                                        <td>{{$order['status']->name}}</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $order['courier']->name }}</td>
                                        <td><span class="label @if($order['total'] != $order['total_paid']) label-danger @else label-success @endif">Dh {{ $order['total'] }}</span></td>
                                        <td><p class="text-center" style="color: #ffffff; background-color: {{ $order['status']->color }}">{{ $order['status']->name }}</p></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="address">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{route('customer.address.create', auth()->user()->id * 15212)}}" class="btn btn-primary">Ajouter une adresse</a>
                                </div>
                            </div>
                            <table class="table">
                                <thead>
                                    <th>Adresse</th>
                                    <th>Complement d'adresse</th>
                                    <th>Ville</th>
                                    <th>Province</th>
                                    <th>Pays</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @foreach($addresses as $address)
                                        <tr>
                                            <td>{{$address->address_1}}</td>
                                            <td>{{$address->address_2}}</td>
                                            <td>{{$address->city_id}}</td>
                                            <td>{{$address->province_id}}</td>
                                            <td>{{$address->country_id}}</td>
                                            <td><form action="{{route('deleteAddress')}}" method="post">{{ csrf_field() }}
                                                <input type="hidden" name="addressId" value="{{$address->id}}">
                                                
                                            <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></form></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection