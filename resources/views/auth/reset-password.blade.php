<!-- resources/views/auth/reset-password.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
    <style>
        /* Basic styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }
        .container {
            background: #fff;
            padding: 2em;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .container h2 {
            margin-bottom: 1em;
        }
        .form-group {
            margin-bottom: 1em;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5em;
        }
        .form-group input {
            width: 100%;
            padding: 0.8em;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .form-group button {
            width: 100%;
            padding: 0.8em;
            background: #ff6f61;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            background: #ff4a3d;
        }
        .alert {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4caf50;
            color: white;
            padding: 1em;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="alert" id="success-alert">Password reset successful! Redirecting to the home page...</div>

    <div class="container">
        <h2>Reset Password</h2>
        <form method="POST" action="{{ url('/reset-password') }}" onsubmit="handleSubmit(event)">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email', $email) }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
            </div>
            <div class="form-group">
                <button type="submit">Reset Password</button>
            </div>
        </form>
    </div>

    <script>
        // Function to handle form submission
        async function handleSubmit(event) {
            event.preventDefault(); // Prevent default form submission

            const form = event.target;
            const formData = new FormData(form);
            const url = form.action;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                });

                const data = await response.json();

                if (response.ok) {
                    // Show success alert
                    const alert = document.getElementById('success-alert');
                    alert.style.display = 'block';

                    // Redirect to the home page after 5 seconds
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 5000);
                } else {
                    alert(data.message || 'Failed to reset password. Please try again.');
                }
            } catch (error) {
                alert('An error occurred while resetting the password. Please try again.');
            }
        }
    </script>
</body>
</html>
