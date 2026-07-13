<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Chatbox</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        body {
            background-color: #ffffff;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }

        .login-card {
            background-color: #DDF5D8; /* Soft mint green */
            border-radius: 50px;
            width: 100%;
            max-width: 700px;
            padding: 60px 80px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .login-title {
            text-align: center;
            font-size: 2.2rem;
            font-weight: 400;
            margin-bottom: 40px;
            color: #000;
        }

        .custom-input-group {
            margin-bottom: 30px;
        }

        .custom-input {
            background-color: transparent;
            border: 6px solid #ffffff;
            border-radius: 50px;
            padding: 16px 30px;
            font-size: 1.1rem;
            color: #000;
            width: 100%;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.05), 0 2px 10px rgba(255,255,255,0.5);
            transition: all 0.3s ease;
        }

        .custom-input:focus {
            outline: none;
            border-color: #f8fcf8;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.1), 0 0 15px rgba(255,255,255,0.8);
            background-color: rgba(255, 255, 255, 0.2);
        }

        .custom-input::placeholder {
            color: #111;
            font-weight: 400;
        }

        .forgot-password-container {
            padding-left: 20px;
            margin-top: -15px;
            margin-bottom: 35px;
        }

        .forgot-password {
            color: #000;
            text-decoration: none;
            font-size: 1.15rem;
            font-weight: 400;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .btn-register {
            background-color: #d1d1d1; /* Light gray to simulate the textured background */
            border: none;
            border-radius: 2px;
            padding: 12px 40px;
            font-size: 1.25rem;
            font-weight: 400;
            color: #000;
            display: block;
            margin: 0 auto;
            width: fit-content;
            transition: background-color 0.3s, transform 0.1s;
        }

        .btn-register:hover {
            background-color: #c0c0c0;
            transform: translateY(-2px);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .continue-with {
            font-size: 1.3rem;
            color: #000;
            margin-bottom: 25px;
            font-weight: 400;
        }

        .social-icons {
            display: flex;
            gap: 25px;
            justify-content: center;
            align-items: center;
        }

        .social-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            text-decoration: none;
            font-size: 35px;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .social-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .icon-facebook {
            background-color: #1877F2;
            color: white;
        }

        .icon-instagram {
            background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
            color: white;
        }

        .icon-discord {
            background-color: #5865F2;
            color: white;
        }

        .icon-tiktok {
            background-color: #000000;
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-card {
                padding: 40px 30px;
                border-radius: 40px;
            }
            .custom-input {
                padding: 14px 25px;
                border-width: 4px;
            }
            .social-icon {
                width: 55px;
                height: 55px;
                font-size: 28px;
            }
            .social-icons {
                gap: 15px;
            }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2 class="login-title">LOGIN</h2>
        
        <!-- Standard Laravel login route/action -->
        <form action="{{ url('/login') }}" method="POST">
            @csrf
            
            <div class="custom-input-group">
                <input type="text" name="username" class="custom-input" placeholder="USERNAME" required autofocus>
            </div>
            
            <div class="custom-input-group">
                <input type="password" name="password" class="custom-input" placeholder="PASSWORD" required>
            </div>
            
            <div class="forgot-password-container">
                <a href="{{ url('/forgot-password') }}" class="forgot-password">Forgot password?</a>
            </div>
            
            <button type="submit" class="btn-register">REGISTER ACCOUNT!</button>
        </form>
    </div>

    <div class="continue-with">Continue with:</div>
    
    <div class="social-icons">
        <a href="#" class="social-icon icon-facebook" aria-label="Continue with Facebook">
            <i class="bi bi-facebook"></i>
        </a>
        <a href="#" class="social-icon icon-instagram" aria-label="Continue with Instagram">
            <i class="bi bi-instagram"></i>
        </a>
        <a href="#" class="social-icon icon-discord" aria-label="Continue with Discord">
            <i class="bi bi-discord"></i>
        </a>
        <a href="#" class="social-icon icon-tiktok" aria-label="Continue with TikTok">
            <i class="bi bi-tiktok"></i>
        </a>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
