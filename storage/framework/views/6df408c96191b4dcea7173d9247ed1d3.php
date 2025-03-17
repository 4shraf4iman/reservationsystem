<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to KahwinApp.online</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
    <style>
        /* Basic styling */
        body {
            background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
            color: #fff;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #28a745;
            color: #fff;
            padding: 15px 30px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            opacity: 0;
            animation: fadeInAlert 1s forwards;
        }
        @keyframes fadeInAlert {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        .content {
            text-align: center;
            animation: fadeIn 2s ease-in-out;
            max-width: 800px;
            padding: 20px;
        }
        .title {
            font-size: 64px;
            font-weight: 600;
            margin-bottom: 20px;
            animation: slideDown 1s ease-out forwards;
            opacity: 0;
        }
        .description {
            font-size: 24px;
            font-weight: 300;
            animation: fadeInUp 1.5s ease-out forwards;
            opacity: 0;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .features {
            text-align: left;
            margin-top: 30px;
            animation: fadeInUp 2s ease-out forwards;
            opacity: 0;
        }
        .features h3 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .features p {
            font-size: 18px;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            margin-top: 30px;
            padding: 15px 30px;
            background-color: #ff6f61;
            color: #fff;
            font-size: 18px;
            text-decoration: none;
            border-radius: 25px;
            transition: background-color 0.3s;
            animation: fadeInUp 2.5s ease-out forwards;
            opacity: 0;
        }
        .button:hover {
            background-color: #ff4a3d;
        }
        .background-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: url('https://source.unsplash.com/1600x900/?wedding,flowers') no-repeat center center/cover;
            filter: blur(8px) brightness(0.7);
            animation: zoomIn 10s ease-in-out infinite alternate;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideDown {
            0% { transform: translateY(-50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        @keyframes fadeInUp {
            0% { transform: translateY(50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        @keyframes zoomIn {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }
    </style>
</head>
<body>
    <div class="background-animation"></div>
    <div class="content">
        <div class="title">
            Welcome to KahwinApp.online!
        </div>
        <div class="description">
            Your go-to platform for creating beautiful and personalized digital RSVP cards for marriage events. 
            KahwinApp.online helps you simplify event planning and make a lasting impression on your guests.
        </div>
        <div class="features">
            <h3>Why Choose KahwinApp.online?</h3>
            <p>
                - **Personalized Digital Cards:** Easily create and customize RSVP cards with personalized messages, 
                themes, and designs that match your wedding style.
            </p>
            <p>
                - **Effortless Event Management:** Keep track of your guest list and RSVP responses in real-time, 
                all in one place.
            </p>
            <p>
                - **Share Instantly:** Send out your digital cards via email, social media, or messaging apps with just a few clicks.
            </p>
            <p>
                - **Eco-Friendly Solution:** Save paper and reduce waste by choosing digital cards over traditional paper invitations.
            </p>
        </div>
        <a href="#more-info" class="button">Start Creating Your Card</a>
    </div>

    <?php if(session('verified')): ?>
        <div class="alert">
            Your email has been successfully verified. Redirecting to home...
        </div>
        <script>
            setTimeout(function() {
                window.location.href = "/";
            }, 5000);
        </script>
    <?php endif; ?>
</body>
</html>
<?php /**PATH C:\laragon\www\Profolio.App\resources\views/welcome.blade.php ENDPATH**/ ?>