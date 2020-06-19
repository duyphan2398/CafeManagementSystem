<!DOCTYPE html>
<html>
<head>
    <title>{{($diff > 0) ? ('Import Receipt') : ('Export Receipt')}} </title>
    <meta charset="utf-8">
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
<h1 class="content">My Cafe</h1>
<h4 class="content">Address: 2/12A Tan Thuan Tay District 7 HCM city</h4>
<h4 class="content">Contact: 0936221326</h4>
<h5 class="content">Date: {{$material['updated_at']}}</h5>
<hr class="w-50">
<h3>{{($diff > 0) ? ('Import Receipt') : ('Export Receipt')}}</h3>
<table class="table w-75 center">
    <thead>
    <tr>
        <th>Material ID</th>
        <th>Name</th>
        <th>Unit</th>
        @if($diff > 0)
            <th>Import Amount</th>
        @else
            <th>Export Receipt</th>
        @endif
        <th>Note</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$material['id']}}</td>
            <td>{{$material['name']}}</td>
            <td>{{$material['unit']}}</td>
            <td>{{$diff}}</td>
            <td>{{$material['note']}}</td>
        </tr>
    </tbody>
</table>
<hr>
<h4>
    By {{$user_name}}
</h4>
</body>
</html>
