<!DOCTYPE html>
<html>
<head>
    <title>Order</title>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css">
        table.center {
            margin-left:auto;
            margin-right:auto;
        }
        .content{
            margin-bottom: 3px;
        }
    </style>
</head>
<body class="text-center">
<img src="{!! asset('images/logo.png') !!}" style="width: 100px; height: 100px" alt="logo">
<h1 class="content">My Cafe Order</h1>
<h4 class="content">Address: 2/12A Tan Thuan Tay District 7 HCM city</h4>
<h4 class="content">Contact: 0936221326</h4>
<h5 class="content">Date: {{now()->format('H:i d-m-Y')}}</h5>
<hr class="w-50">
<h3>Table: {{$table['name']}}    -    ID: {{$receipt['id']}}</h3>
<table class="table w-75 center">
    <thead>
    <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Type</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $product)
        @if($product['type'] == 'Drink')
            <tr>
                <td>{{$product['product_name']}}</td>
                <td>{{$product['quantity']}}</td>
                <td>{{$product['type']}}</td>
            </tr>
            @if($product['note'] != '')
            <tr>
                <td colspan="3" style="text-align: left !important;">Note: <b>{{$product['note']}}</b></td>
            </tr>
            @endif
        @endif
    @endforeach
        <tr>
            <td colspan="3">
                - - - - - - - - - - - - - - - - - - - - * - - - - - - - - - - - - - - - - - - - -
            </td>
        </tr>
    @foreach($data as $product)
        @if($product['type'] == 'Food')
            <tr>
                <td>{{$product['product_name']}}</td>
                <td>{{$product['quantity']}}</td>
                <td>{{$product['type']}}</td>
            </tr>
            @if($product['note'] != '')
                <tr>
                    <td colspan="3" style="text-align: left !important;">Note: <b>{{$product['note']}}</b></td>
                </tr>
            @endif
        @endif
    @endforeach
        <tr>
            <td colspan="3">
                - - - - - - - - - - - - - - - - - - - - * - - - - - - - - - - - - - - - - - - - -
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>
