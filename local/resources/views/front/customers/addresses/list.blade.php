@extends('layouts.front.app')

@section('content')
<hr>
    <!-- Main content -->
    <section class="content container">
        <div class="col-md-8 col-md-offset-2" style="background-color:#ffffff;padding:5%">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
        @if($addresses)
            <div class="box">
                <div class="box-body">
                    <h2>Addresses</h2>
                    <table class="table">
                        <tbody>
                        <tr>
                            <td class="col-md-3">Address 1</td>
                            <td class="col-md-1">Country</td>
                            <td class="col-md-2">Province</td>
                            <td class="col-md-1">City</td>
                            <td class="col-md-1">Zip Code</td>
                            <td class="col-md-1">Status</td>
                            <td class="col-md-3">Actions</td>
                        </tr>
                        </tbody>
                        <tbody>
                        @foreach ($addresses as $address)
                            <tr>
                                 <td>{{ $address->address_1 }}</td>
                                <td>{{ $address->country->name }}</td>
                                <td>{{ $address->province->name }}</td>
                                <td>{{ $address->city->name }}</td>
                                <td>{{ $address->zip }}</td>
                                <td>@include('layouts.status', ['status' => $address->status])</td>
                                <td>
                                    <form action="{{ route('admin.addresses.destroy', $address->id) }}" method="post" class="form-horizontal">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="delete">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.addresses.edit', $address->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                            <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Delete</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        @else
            <div class="box">
                <div class="box-body"><p class="alert alert-warning">No addresses found.</p></div>
            </div>
        @endif
        </div>
    </section>
    <!-- /.content -->
@endsection