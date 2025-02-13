<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mothers Union Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-100">
<!-- Header -->
<header class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="text-2xl font-bold">Mothers Union</div>
        <nav class="space-x-4">
            <a href="#" class="text-gray-700 hover:text-gray-900">Home</a>
            <a href="#" class="text-gray-700 hover:text-gray-900">About</a>
            <a href="#" class="text-gray-700 hover:text-gray-900">Contact</a>
        </nav>
        <div class="relative">
            <img src="path/to/user-picture.jpg" alt="User Picture" class="w-10 h-10 rounded-full cursor-pointer"
                 id="userMenuButton">
            <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg hidden">
                <div class="p-4 border-b">
                    <p class="font-semibold"><?php echo $_SESSION['user']['name']; ?></p>
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
    <h1 class="text-3xl font-bold mb-8">Mothers Members</h1>
    <div class="mb-8">
        <button id="addMemberBtn" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            <span class="text-xl">+</span> Add New Member
        </button>
    </div>

    <!-- Add/Edit Member Form -->
    <div id="memberModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-2xl mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 id="formTitle" class="text-xl font-semibold">Add New Member</h2>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="memberForm" class="space-y-4">
                <input type="hidden" name="id" id="memberId">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">First Name</label>
                        <input type="text" name="first_name" id="firstName" class="w-full p-2 border rounded"
                               required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Last Name</label>
                        <input type="text" name="last_name" id="lastName" class="w-full p-2 border rounded"
                               required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" id="email" class="w-full p-2 border rounded" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Phone</label>
                    <input type="tel" name="phone" id="phone" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Address</label>
                    <textarea name="address" id="address" class="w-full p-2 border rounded"></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="submit" id="submitBtn"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Submit
                    </button>
                    <button type="button" id="cancelBtn"
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 hidden">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Members List -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Members List</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Phone</th>
                    <th class="px-4 py-2 text-left">Join Date</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
                </thead>
                <tbody id="membersList">
                <!-- Members will be loaded here dynamically -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-white shadow-md mt-8">
    <div class="container mx-auto px-4 py-4 text-center">
        <p class="text-gray-700">&copy; 2023 Mothers Union. All rights reserved.</p>
    </div>
</footer>

<script>
    let isEditing = false;

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

    function openModal() {
        document.getElementById('memberModal').classList.remove('hidden');
        document.getElementById('memberModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('memberModal').classList.remove('flex');
        document.getElementById('memberModal').classList.add('hidden');
        resetForm();
    }

    // Load members
    function loadMembers() {
        fetch('api/read.php')
            .then(response => response.json())
            .then(data => {
                const membersList = document.getElementById('membersList');
                membersList.innerHTML = '';

                data.records.forEach(member => {
                    membersList.innerHTML += `
                                        <tr>
                                            <td class="px-4 py-2">${member.first_name} ${member.last_name}</td>
                                            <td class="px-4 py-2">${member.email}</td>
                                            <td class="px-4 py-2">${member.phone}</td>
                                            <td class="px-4 py-2">${member.join_date}</td>
                                            <td class="px-4 py-2">
                                                <button onclick="editMember(${member.id})" class="text-blue-500 hover:text-blue-700 mr-2">Edit</button>
                                                <button onclick="deleteMember(${member.id})" class="text-red-500 hover:text-red-700">Delete</button>
                                            </td>
                                        </tr>
                                    `;
                });
            })
            .catch(error => console.error('Error:', error));
    }

    // Edit member
    function editMember(id) {
        fetch(`api/read_one.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('memberId').value = data.id;
                document.getElementById('firstName').value = data.first_name;
                document.getElementById('lastName').value = data.last_name;
                document.getElementById('email').value = data.email;
                document.getElementById('phone').value = data.phone;
                document.getElementById('address').value = data.address;

                document.getElementById('formTitle').textContent = 'Edit Member';
                document.getElementById('submitBtn').textContent = 'Update Member';
                document.getElementById('cancelBtn').classList.remove('hidden');
                isEditing = true;
                openModal();
            })
            .catch(error => console.error('Error:', error));
    }

    // Delete member
    function deleteMember(id) {
        if (confirm('Are you sure you want to delete this member?')) {
            fetch('api/delete.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({id: id})
            })
                .then(response => response.json())
                .then(data => {
                    showToast('Member deleted successfully');
                    loadMembers();
                })
                .catch(error => console.error('Error:', error));
        }
    }

    // Reset form
    function resetForm() {
        document.getElementById('memberForm').reset();
        document.getElementById('memberId').value = '';
        document.getElementById('formTitle').textContent = 'Add New Member';
        document.getElementById('submitBtn').textContent = 'Add Member';
        document.getElementById('cancelBtn').classList.add('hidden');
        isEditing = false;
    }

    document.getElementById('addMemberBtn').addEventListener('click', () => {
        resetForm();
        openModal();
    });

    document.getElementById('closeModal').addEventListener('click', closeModal);

    document.getElementById('memberModal').addEventListener('click', (e) => {
        if (e.target === e.currentTarget) {
            closeModal();
        }
    });

    // Form submission handler
    document.getElementById('memberForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = {};
        formData.forEach((value, key) => data[key] = value);

        const url = isEditing ? 'api/update.php' : 'api/create.php';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(data => {
                showToast(isEditing ? 'Member updated successfully' : 'Member added successfully');
                resetForm();
                closeModal();
                loadMembers();
            })
            .catch(error => console.error('Error:', error));
    });

    // Cancel button handler
    document.getElementById('cancelBtn').addEventListener('click', resetForm);

    // Load members on page load
    document.addEventListener('DOMContentLoaded', loadMembers);

    // User menu toggle
    document.getElementById('userMenuButton').addEventListener('click', () => {
        const userMenu = document.getElementById('userMenu');
        userMenu.classList.toggle('hidden');
    });

    // Close user menu when clicking outside
    document.addEventListener('click', (e) => {
        const userMenu = document.getElementById('userMenu');
        if (!userMenu.contains(e.target) && e.target.id !== 'userMenuButton') {
            userMenu.classList.add('hidden');
        }
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
</script>
</body>
</html>