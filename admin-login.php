<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mothers Union</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h1 class="text-2xl font-bold mb-6 text-center">Login as Admin</h1>
        <form id="loginForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" required class="w-full p-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" required class="w-full p-2 border rounded">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
                Login
            </button>
        </form>
        <p class="mt-4 text-center text-sm">
            Don't have an account?
            <a href="register.php" class="text-blue-500 hover:text-blue-700">Register</a>
        </p>
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = {};
        formData.forEach((value, key) => data[key] = value);

        fetch('api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => {
                if (data.jwt) {
                    localStorage.setItem('jwt', data.jwt);
                    localStorage.setItem('user', JSON.stringify(data.user));
                    window.location.href = 'admin.php';
                } else {
                    alert('Login failed. Please check your credentials.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Login failed. Please try again.');
            });
    });
</script>
</body>
</html>