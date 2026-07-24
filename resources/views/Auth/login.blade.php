<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Login Page</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            background-image: url('assets/img/bagus.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            overflow: hidden;
            width: 768px;
            max-width: 100%;
            min-height: 480px;
            position: relative;
        }

        .container form {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            height: 100%;
        }

        .container input {
            background-color: #eee;
            border: none;
            margin: 8px 0;
            padding: 10px 15px;
            font-size: 13px;
            border-radius: 8px;
            width: 100%;
            outline: none;
        }

        .container button {
            background-color: #5ee9ff;
            color: black;
            font-size: 12px;
            padding: 10px 45px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 10px;
            cursor: pointer;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            width: 50%;
            left: 0;
            z-index: 2;
        }

        .toggle-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: all 0.6s ease-in-out;
            border-radius: 150px 0 0 100px;
            z-index: 1000;
        }

        .toggle {
            background: linear-gradient(to right, #512da8, #5ee9ff);
            height: 100%;
            color: #fff;
            position: relative;
            left: -100%;
            width: 200%;
            transition: all 0.6s ease-in-out;
        }

        .toggle-panel {
            position: absolute;
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 30px;
            text-align: center;
            top: 0;
        }

        .toggle-panel h1,
        .toggle-panel p {
            color: black;
        }

        .toggle-panel .logo {
            margin-bottom: 20px;
        }

        .toggle-right{
          right: 0;
          transform: translateX(0);
        }

        .container.active .toggle-right{
          transform: translateX(200%);
        }
    </style>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-in">
          <form action="{{ route('loginProcess') }}" method="post" class="row g-3 needs-validation" novalidate>
            @csrf
                <h1>Masuk</h1>
                <span>Gunakan email password kamu</span>
                <input name="email" type="email" required placeholder="Masukkan alamat email" value="{{ old('email') }}">
                    @error('email')
                        <div>{{ $message }}</div>
                    @enderror
                <input name="password" type="password" required placeholder="Masukkan password" value="{{ old('password') }}">
                    @error('password')
                        <div>{{ $message }}</div>
                    @enderror
                <button>Masuk</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <img src="assets/img/android-chrome-512x512.png" alt="logo" width="270" height="250" class="logo">
                    <h1>Selamat Datang di Server Printer</h1>
                    <p>Dokumenmu dibawah kendali kami.</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
