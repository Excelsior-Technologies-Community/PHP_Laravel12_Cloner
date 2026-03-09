<!DOCTYPE html>
<html>
<head>

<title>Add Product</title>

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

.back{
    display:inline-block;
    margin-top:10px;
}

</style>

</head>

<body>

<div class="container">

<h2>Add Product</h2>

<form method="POST" action="/products/store">

@csrf

<label>Name</label>
<input type="text" name="name">

<label>Price</label>
<input type="text" name="price">

<label>Description</label>
<textarea name="description"></textarea>

<button type="submit">Save Product</button>

</form>

<a class="back" href="/products">Back</a>

</div>

</body>
</html>