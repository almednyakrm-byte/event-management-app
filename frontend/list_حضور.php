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
    <title>حضور Module</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-emerald-600 text-white p-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <span class="text-lg font-bold">Current User: <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="container mx-auto p-4 mt-4">
        <h1 class="text-3xl font-bold mb-4">حضور Module</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">
                <a href="create_حضور.php">Add New Item</a>
            </button>
            <input type="text" id="search" class="py-2 px-4 rounded" placeholder="Search...">
        </div>
        <table id="records" class="w-full table-auto border-collapse border border-gray-400">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-400 p-2">ID</th>
                    <th class="border border-gray-400 p-2">Name</th>
                    <th class="border border-gray-400 p-2">Actions</th>
                </tr>
            </thead>
            <tbody id="records-body">
                <!-- Records will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch records from backend
        fetch('../backend/حضور.php')
            .then(response => response.json())
            .then(data => {
                const recordsBody = document.getElementById('records-body');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border border-gray-400 p-2">${record.id}</td>
                        <td class="border border-gray-400 p-2">${record.name}</td>
                        <td class="border border-gray-400 p-2">
                            <a href="edit_حضور.php?id=${record.id}" class="text-teal-500 hover:text-teal-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">Delete</button>
                        </td>
                    `;
                    recordsBody.appendChild(row);
                });
            });

        // Delete record
        function deleteRecord(id) {
            fetch('../backend/حضور.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove record from table
                        const rows = document.getElementById('records-body').children;
                        for (let i = 0; i < rows.length; i++) {
                            if (rows[i].children[0].textContent == id) {
                                rows[i].remove();
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
            const rows = document.getElementById('records-body').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>