<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$title = 'Mothers Union Home';
ob_start();

?>


    <!-- Main Content -->


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
                    <th class="px-4 py-2 text-left">Church</th>
                    <th class="px-4 py-2 text-left">Group</th>
                    <th class="px-4 py-2 text-left">ID</th>
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
            <button id="nextPageBtn" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Next</button>
        </div>
    </div>
    </div>


    <script>


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

        document.addEventListener('DOMContentLoaded', () => loadMembers(currentPage));


    </script>

<?php
$content = ob_get_clean();
include 'base.php';
?>