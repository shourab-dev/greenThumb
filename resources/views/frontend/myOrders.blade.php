@extends('layout.UserProfileLayout')
@section('userProfile')
<h2>My Orders</h2>

<div class="table-responsive" style="height: 55vh;overflow:auto;">
    <table class="table ">
        <tr>
            <th>#</th>
            <th>Order Details</th>
            <th>Total Items</th>
            <th>Status</th>
        </tr>
        @foreach ($orders as $key=>$order)
        <tr>
            <td>{{ $orders->firstItem() + $key}}</td>
            <td width="60%">
                Customer Name: <b> {{ $order->name }}</b>
                <br>
                Phone: <b>{{ $order->phone }}</b> <br>
                Email: <b>{{ $order->email }}</b> <br>
                @if ($order->state)
                <p>State: <b>{{ $order->state }}</b></p>
                @endif
                <p class="m-0">Address: {{ $order->address }}</p>
                @if ($order->address2)

                <p>Address2: {{ $order->address2 }}</p>
                @endif
                <b>Total Order Price: {{ $order->total_price }}tk</b>

                @foreach ($order->orderItems as $item)
                <div class="row my-2 align-items-center">
                    <div class="col-lg-2">
                        <img src="{{ asset('storage/'.$item->products?->image) }}" alt="" class="img-fluid">
                    </div>
                    <div class="col-lg-10">
                        <p>{{ str($item->products->name)->headline() }} * ({{ $item->qty }} * {{ $item->price }} tk)</p>
                        <p><b>Item price: {{ $item->price * $item->qty }}tk</b></p>
                    </div>
                </div>
                @endforeach


            </td>
            <td>{{ $order->qty }}</td>
            <td>
                <span
                    class="btn btn-{{ $order->status == 'Processing' || $order->status == 'Complete' ? 'primary' : ($order->status == 'Pending' ? 'danger':'success') }} btn-sm">
                    @if ($order->status == 'Processing' || $order->status == 'Complete')
                    Payment Complete
                    @elseif ($order->status == 'Pending')
                    Payment Pending
                    @else
                    {{ $order->status }}
                    @endif
                </span>
            </td>

        </tr>
        @endforeach
    </table>
</div>
@endsection