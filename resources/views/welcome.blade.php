<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Server Printer</title>
    <!-- Favicons -->
    <link href="assets/img/favicon.ico" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway';
            font-weight: 100;
            height: 100vh;
            margin: 0;
            /* Tambahkan background-image */
            background-image: url('assets/img/bagus.jpg');
            /* Atur background-size dan background-position */
            background-size: cover;
            background-position: center;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.9); /* Latar belakang putih dengan transparansi */
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Memberikan bayangan pada latar belakang putih */
        }

        .title {
            font-size: 50px;
            color: black;
            font-weight: bold;
        }

        .links > a {
            color: black;
            padding: 15px 25px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
            background-color: #5ee9ff;
            display: inline-block;
            margin: 5px;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .logo {
            margin-bottom: 20px; /* Menambahkan jarak antara logo dan judul */
        }
    </style>
</head>
<body>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <img src="assets/img/android-chrome-512x512.png" alt="logo" width="270" height="250" class="logo">
            <div class="title m-b-md">
                Server Printer gacor
            </div>
            <div class="links">
                <a href="{{ route('loginPage') }}">Login</a>
                <a href="{{ route('loginRFIDPage') }}">Login With RFID</a>
            </div>
        </div>
    </div>
</body>
</html>