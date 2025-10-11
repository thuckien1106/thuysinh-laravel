@extends('layouts.header')
@section('title', 'Quản lý sản phẩm')
@section('content')
<h2>Danh sách sản phẩm</h2>
<table class="table table-striped">
  <thead><tr><th>Tên</th><th>Giá</th><th>Số lượng</th></tr></thead>
  <tbody>
    <tr><td>Rêu Java</td><td>35.000 đ</td><td>120</td></tr>
    <tr><td>Cá Neon</td><td>15.000 đ</td><td>300</td></tr>
  </tbody>
</table>
@include('layouts.footer')
@endsection
