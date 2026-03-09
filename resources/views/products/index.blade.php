<!DOCTYPE html>
<html>
<head>

<title>Product List</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
padding:40px;
}

.container{
width:1100px;
margin:auto;
background:white;
padding:30px;
border-radius:8px;
box-shadow:0px 0px 10px #ccc;
}

h2{
margin-bottom:20px;
}

.add-btn{
background:#28a745;
color:white;
padding:10px 20px;
text-decoration:none;
border-radius:5px;
}

table{
width:100%;
margin-top:20px;
border-collapse:collapse;
}

table th{
background:#343a40;
color:white;
padding:12px;
}

table td{
padding:12px;
border-bottom:1px solid #ddd;
}

.success{
background:#d4edda;
padding:10px;
margin-bottom:15px;
border-radius:5px;
}

/* Action buttons */

.action-buttons{
display:flex;
gap:10px;
}

/* Buttons */

.clone-btn{
background:#007bff;
color:white;
padding:6px 14px;
border-radius:4px;
text-decoration:none;
}

.edit-btn{
background:#ffc107;
color:black;
padding:6px 14px;
border-radius:4px;
text-decoration:none;
}

.delete-btn{
background:#dc3545;
color:white;
padding:6px 14px;
border-radius:4px;
text-decoration:none;
}

.clone-btn:hover{
background:#0069d9;
}

.edit-btn:hover{
background:#e0a800;
}

.delete-btn:hover{
background:#c82333;
}

</style>

</head>

<body>

<div class="container">

<h2>Product List</h2>

@if(session('success'))
<div class="success">
{{ session('success') }}
</div>
@endif

<a class="add-btn" href="/products/create">Add Product</a>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Price</th>
<th>Description</th>
<th>Action</th>
</tr>

@foreach($products as $product)

<tr>

<td>{{ $product->id }}</td>

<td>{{ $product->name }}</td>

<td>{{ $product->price }}</td>

<td>{{ $product->description }}</td>

<td>

<div class="action-buttons">

<a class="clone-btn" href="/products/clone/{{ $product->id }}">
Clone
</a>

<a class="edit-btn" href="/products/edit/{{ $product->id }}">
Edit
</a>

<a class="delete-btn" href="/products/delete/{{ $product->id }}">
Delete
</a>

</div>

</td>

</tr>

@endforeach

</table>

</div>

</body>
</html>