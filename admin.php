<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: admin-login.php");
    exit();
}

$title = 'Mothers Union Admin';
ob_start();

include_once 'config/database.php';
include_once 'models/User.php';
include_once 'models/Member.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$member = new Member($db);

$users = $user->read()->fetchAll(PDO::FETCH_ASSOC);
$members = $member->read()->fetchAll(PDO::FETCH_ASSOC);
?>


    <div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Admin Page</h1>

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-2xl font-semibold mb-4">Users</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">First Name</th>
                    <th class="px-4 py-2 text-left">Last Name</th>
                    <th class="px-4 py-2 text-left">Role</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr class="border-b">
                        <td class="px-4 py-2"><?php echo $user['id']; ?></td>
                        <td class="px-4 py-2"><?php echo $user['email']; ?></td>
                        <td class="px-4 py-2"><?php echo $user['firstname']; ?></td>
                        <td class="px-4 py-2"><?php echo $user['lastname']; ?></td>
                        <td class="px-4 py-2">
                            <select onchange="updateUserRole(<?php echo $user['id']; ?>, this.value)"
                                    class="border rounded px-2 py-1">
                                <option value="viewer" <?php echo $user['role'] === 'viewer' ? 'selected' : ''; ?>>
                                    Viewer
                                </option>
                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin
                                </option>
                                <option value="editor" <?php echo $user['role'] === 'editor' ? 'selected' : ''; ?>>
                                    Editor
                                </option>
                            </select>
                        </td>
                        <td class="px-4 py-2">
                            <button onclick="editUser(<?php echo $user['id']; ?>)"
                                    class="text-blue-500 hover:text-blue-700 mr-2">Edit
                            </button>
                            <button onclick="deleteUser(<?php echo $user['id']; ?>)"
                                    class="text-red-500 hover:text-red-700">Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="toast-container" class="fixed top-4 right-4 z-50"></div>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Members</h1>
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
                    <div>
                        <label class="block text-sm font-medium mb-1">Church</label>
                        <select name="church_id" id="church" class="w-full p-2 border rounded"></select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Group</label>
                        <select name="cgroup_id" id="group" class="w-full p-2 border rounded"></select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Next of Kin</label>
                        <input type="text" name="next_of_kin" id="nextOfKin" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Member Number</label>
                        <input type="text" name="member_number" id="memberNumber" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Number of Kids</label>
                        <input type="number" name="number_of_kids" id="numberOfKids" class="w-full p-2 border rounded">
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

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Members List</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Phone</th>
                        <th class="px-4 py-2 text-left">Church</th>
                        <th class="px-4 py-2 text-left">Group</th>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="membersList">
                    <!-- Members will be loaded here dynamically -->
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-between items-center">
                <button id="prevPageBtn" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Previous
                </button>
                <span id="paginationInfo" class="text-gray-700"></span>
                <button id="nextPageBtn" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Next
                </button>
            </div>
        </div>
    </div>

    <script>
        let isEditing = false;

        function openModal() {
            document.getElementById('memberModal').classList.remove('hidden');
            document.getElementById('memberModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('memberModal').classList.remove('flex');
            document.getElementById('memberModal').classList.add('hidden');
            resetForm();
        }

        function editUser(id) {
            // Implement edit user functionality
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch('api/delete_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({id: id})
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Failed to delete user.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        // Load members
        let currentPage = 1;
        const limit = 5;

        function loadMembers(page = 1) {
            fetch(`api/read.php?page=${page}&limit=${limit}`)
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
                            <td class="px-4 py-2">${member.church}</td>
                            <td class="px-4 py-2">${member.group}</td>
                            <td class="px-4 py-2">${member.member_number}</td>
                            <td class="px-4 py-2">
                                <button onclick="editMember(${member.id})" class="text-blue-500 hover:text-blue-700 mr-2">Edit</button>
                                <button onclick="deleteMember(${member.id})" class="text-red-500 hover:text-red-700">Delete</button>
                            </td>
                        </tr>
                    `;
                    });

                    document.getElementById('paginationInfo').textContent = `Page ${data.pagination.current_page} of ${data.pagination.total_pages}`;
                    document.getElementById('prevPageBtn').disabled = data.pagination.current_page === 1;
                    document.getElementById('nextPageBtn').disabled = data.pagination.current_page === data.pagination.total_pages;
                })
                .catch(error => console.error('Error:', error));
        }

        document.getElementById('prevPageBtn').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                loadMembers(currentPage);
            }
        });

        document.getElementById('nextPageBtn').addEventListener('click', () => {
            currentPage++;
            loadMembers(currentPage);
        });

        document.addEventListener('DOMContentLoaded', () => {
            loadMembers(currentPage)
            loadChurches();
            loadGroups();
        });

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

        function updateUserRole(id, role) {
            fetch('api/update_user_role.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({id: id, role: role})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('User role updated successfully');
                    } else {
                        showToast('Failed to update user role', 'error');
                    }
                })
                .catch(error => console.error('Error:', error));
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


        function loadChurches() {
            fetch('api/read_churches.php')
                .then(response => response.json())
                .then(data => {
                    const churchSelect = document.getElementById('church');
                    data.forEach(church => {
                        const option = document.createElement('option');
                        option.value = church.id;
                        option.textContent = church.name;
                        churchSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        function loadGroups() {
            fetch('api/read_groups.php')
                .then(response => response.json())
                .then(data => {
                    const groupSelect = document.getElementById('group');
                    data.forEach(group => {
                        const option = document.createElement('option');
                        option.value = group.id;
                        option.textContent = group.name;
                        groupSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        // Load members on page load
        // document.addEventListener('DOMContentLoaded', loadMembers);

    </script>

<?php
$content = ob_get_clean();
include 'base.php';
?>