<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تذاكر Module</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-emerald-600 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <div class="flex items-center">
                <span class="mr-4">Current User: <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Logout</a>
            </div>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">تذاكر Module</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_تذاكر.php'">Add New Item</button>
            <input type="search" id="search" class="py-2 pl-10 text-sm text-gray-700" placeholder="Search...">
        </div>
        <table id="records" class="w-full table-auto border-collapse border border-gray-400">
            <thead class="bg-emerald-600 text-white">
                <tr>
                    <th class="border border-gray-400 p-2">ID</th>
                    <th class="border border-gray-400 p-2">Name</th>
                    <th class="border border-gray-400 p-2">Actions</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Records will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch records from backend
        fetch('../backend/تذاكر.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('table-body');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border border-gray-400 p-2">${record.id}</td>
                        <td class="border border-gray-400 p-2">${record.name}</td>
                        <td class="border border-gray-400 p-2">
                            <a href="edit_تذاكر.php?id=${record.id}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">Delete</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });

        // Delete record
        function deleteRecord(id) {
            fetch('../backend/تذاكر.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the deleted record from the table
                    const tableBody = document.getElementById('table-body');
                    const rows = tableBody.children;
                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const idCell = row.children[0];
                        if (idCell.textContent == id) {
                            tableBody.removeChild(row);
                            break;
                        }
                    }
                }
            });
        }

        // Search functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('table-body').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const nameCell = row.children[1];
                if (nameCell.textContent.toLowerCase().includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>