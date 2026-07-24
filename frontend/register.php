<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen bg-emerald-600 flex justify-center items-center">
    <div class="bg-teal-500 p-10 rounded-lg shadow-lg">
        <h2 class="text-3xl text-white font-bold mb-4">Register</h2>
        <form id="register-form">
            <div class="mb-4">
                <label for="username" class="block text-white text-sm font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg shadow-sm focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
                <span class="text-red-500 text-xs" id="username-error"></span>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-white text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" required class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg shadow-sm focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
                <span class="text-red-500 text-xs" id="email-error"></span>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-white text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" required class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg shadow-sm focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
                <span class="text-red-500 text-xs" id="password-error"></span>
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">Register</button>
        </form>
        <div id="register-response" class="mt-4"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();

                if (username.length < 3) {
                    $('#username-error').text('Username must be at least 3 characters long');
                    return;
                } else {
                    $('#username-error').text('');
                }

                if (!validateEmail(email)) {
                    $('#email-error').text('Invalid email address');
                    return;
                } else {
                    $('#email-error').text('');
                }

                if (password.length < 8) {
                    $('#password-error').text('Password must be at least 8 characters long');
                    return;
                } else {
                    $('#password-error').text('');
                }

                $.ajax({
                    type: 'POST',
                    url: '../backend/auth.php?action=register',
                    data: {
                        username: username,
                        email: email,
                        password: password
                    },
                    success: function(response) {
                        $('#register-response').html(response);
                    }
                });
            });

            function validateEmail(email) {
                var re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                return re.test(email);
            }
        });
    </script>
</body>
</html>