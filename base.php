<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Mothers Union'; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-100">
<!-- Header -->
<header class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="text-2xl font-bold">Mothers Union</div>
        <nav class="space-x-4">
            <a href="index.php" class="text-gray-700 hover:text-gray-900">Home</a>
            <a href="#" class="text-gray-700 hover:text-gray-900">About</a>
            <a href="admin.php" class="text-gray-700 hover:text-gray-900">Admin</a>
        </nav>
        <div class="relative">
            <img src="<?php echo !empty($_SESSION['user']['image']) ? $_SESSION['user']['image'] : 'npp.png'; ?>"
                 class="w-10 h-10 rounded-full cursor-pointer" id="userMenuButton" alt="<?php echo $_SESSION['user']['firstname']; ?>">
            <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg hidden">
                <div class="p-4 border-b">
                    <p class="font-semibold"><?php echo $_SESSION['user']['firstname'] . ' ' . $_SESSION['user']['lastname']; ?></p>
                    <p class="text-sm text-gray-600"><?php echo $_SESSION['user']['email']; ?></p>
                </div>
                <ul>
                    <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a></li>
                    <li><a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Settings</a></li>
                    <li><a href="#" id="logoutLink" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<div id="toast-container" class="fixed top-4 right-4 z-50"></div>
<div class="container mx-auto px-4 py-8">
    <?php echo $content; ?>
</div>

<!-- Footer -->
<footer class="bg-white shadow-md mt-8">
    <div class="container mx-auto px-4 py-4 text-center">
        <p class="text-gray-700">&copy; 2023 Mothers Union. All rights reserved.</p>
    </div>
</footer>

<script>
    // Close user menu when clicking outside
    document.addEventListener('click', (e) => {
        const userMenu = document.getElementById('userMenu');
        if (!userMenu.contains(e.target) && e.target.id !== 'userMenuButton') {
            userMenu.classList.add('hidden');
        }
    });

    // User menu toggle
    document.getElementById('userMenuButton').addEventListener('click', () => {
        const userMenu = document.getElementById('userMenu');
        userMenu.classList.toggle('hidden');
    });

    // Logout functionality
    document.getElementById('logoutLink').addEventListener('click', (e) => {
        e.preventDefault();
        fetch('api/logout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.message === "Logout successful.") {
                    window.location.href = 'login.php';
                } else {
                    alert('Logout failed. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Logout failed. Please try again.');
            });
    });

    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toast-container');
        const toast = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';

        toast.className = `toast ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg mb-3`;
        toast.textContent = message;

        toastContainer.appendChild(toast);

        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                toastContainer.removeChild(toast);
            }, 300);
        }, 3000);
    }
</script>
</body>
</html>