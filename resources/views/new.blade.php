<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <title>Document</title>
</head>
<body>
    <div class="jumbotron">
        <div class="container">
            <form action="{{ route("sale.store") }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="price">Total</label>
                    <input type="number" name="total" class="form-control">
                </div>
                <div class="form-group">
                    <label for="properties">Properties</label>
                    <div class="row">
                        <div class="col-md-2">
                            Product:
                        </div>
                        <div class="col-md-4">
                            Price:
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="product" class="form-control" value="{{ old('product') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="price" class="form-control" value="{{ old('price') }}">
                        </div>
                    </div>
                </div>
                <div>
                    <input class=" btn btn-danger" type="submit">
                </div>
            </form>
        </div>
    </div>

</body>
</html>
