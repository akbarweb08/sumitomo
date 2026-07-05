<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sketch Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-form {
            width: 340px;
            margin: 50px auto;
            font-size: 15px;
        }
        .login-form form {
            margin-bottom: 15px;
            background: #fff;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding: 30px;
            border-radius: 5px;
        }
        .login-form h4 {
            margin: 0 0 15px;
        }
        .form-control, .btn {
            min-height: 38px;
            border-radius: 2px;
        }
        .btn {        
            font-size: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <form action="{{ route('login') }}" method="post">
            @csrf
            <b><h4 class="text-center">Sketch Warehouse System</h4></b>
            
            @if(session('error'))
                <div class="alert alert-danger p-2 text-center" style="font-size: 13px;">
                    {{ session('error') }}
                </div>
            @endif

            <div class="form-group mb-3">
                <input type="text" class="form-control" placeholder="Nama Karyawan" name="nama" required="required" value="{{ old('nama') }}">
            </div>
            <div class="form-group mb-3">
                <input type="password" class="form-control" placeholder="Password" name="password" required="required">
            </div>
            <div class="form-group mb-3">
                <button type="submit" class="btn btn-primary w-100">Log in</button>
            </div>
            
            @if(isset($count) && $count < 4)
            <p class="text-center" style="color: red; font-size: 13px"><i>*proses login kali ini akan memakan waktu yang lebih lama</i></p>
            @endif
        </form>
    </div>
</body>
</html>
