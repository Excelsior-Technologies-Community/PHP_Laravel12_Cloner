<!DOCTYPE html>
<html>
<head>

<title>Edit Product</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
padding:40px;
}

.container{
width:500px;
margin:auto;
background:white;
padding:30px;
border-radius:8px;
box-shadow:0px 0px 10px #ccc;
}

input,textarea{
width:100%;
padding:10px;
margin-bottom:15px;
border:1px solid #ccc;
border-radius:4px;
}

button{
background:#007bff;
color:white;
padding:10px 20px;
border:none;
border-radius:4px;
}

</style>

</head>

<body>

<div class="container">

<h2>Edit Product</h2>

<form method="POST" action="/products/update/{{ $product->id }}">

@csrf

<label>Name</label>
<input type="text" name="name" value="{{ $product->name }}">

<label>Price</label>
<input type="text" name="price" value="{{ $product->price }}">

<label>Description</label>
<textarea name="description">{{ $product->description }}</textarea>

<button type="submit">Update Product</button>

</form>

</div>

</body>
</html>