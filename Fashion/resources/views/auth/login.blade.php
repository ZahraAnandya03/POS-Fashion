<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SB Admin Light Mode</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Background */
        body {
            background: linear-gradient(to right, #ffffff, #f2f2f2);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        /* Card Styling */
        .card {
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            padding: 20px;
            width: 400px;
            color: #333;
        }

        /* Card Header */
        .card-header {
            font-size: 22px;
            font-weight: bold;
            background: linear-gradient(to right, #3b4550, #3b4550);
            color: white;
            border-radius: 12px 12px 0 0;
            text-align: center;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-header i {
            margin-right: 8px;
        }

        /* Button Styling */
        .btn-primary {
            background: linear-gradient(to right, #3b4550, #3b4550);
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
            border-radius: 8px;
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(to right, #3b4550, #3b4550);
            transform: scale(1.05);
        }

        /* Input Field Styling */
        .input-group-text {
            background: #3b4550;
            color: white;
            border: none;
            border-radius: 8px 0 0 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
        }

        .form-control {
            border-radius: 0 8px 8px 0;
            background: #f9f9f9;
            color: #333;
            border: 1px solid #ccc;
            padding: 12px;
        }

        .form-control:focus {
            box-shadow: 0px 0px 10px rgba(0, 123, 255, 0.3);
            border-color: #3b4550;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .card {
                width: 90%;
                padding: 15px;
            }
            .btn-primary {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-user-lock"></i> Login
                    </div>
                    <div class="card-body">

                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert -->
    <script>
        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                title: 'Login Gagal!',
                text: "{{ $errors->first() }}",
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif
    </script>

</body>
</html>
