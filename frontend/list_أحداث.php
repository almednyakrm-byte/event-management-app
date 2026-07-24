<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['user'];

// HTML content
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أحداث</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-emerald-600 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <span class="text-lg font-bold">مرحبا، <?php echo $current_user; ?></span>
            <a href="logout.php" class="text-lg font-bold">تسجيل الخروج</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">أحداث</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">
                <a href="create_أحداث.php">إضافة جديد</a>
            </button>
            <input type="search" id="search" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" type="search" placeholder="بحث...">
        </div>
        <table class="table-auto w-full text-right" id="records-table">
            <thead class="bg-teal-500 text-white">
                <tr>
                    <th class="px-4 py-2">الاسم</th>
                    <th class="px-4 py-2">التاريخ</th>
                    <th class="px-4 py-2">الوصف</th>
                    <th class="px-4 py-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records-tbody">
                <!-- Records will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch records from backend
        fetch('../backend/أحداث.php')
            .then(response => response.json())
            .then(data => {
                const recordsTbody = document.getElementById('records-tbody');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${record.name}</td>
                        <td class="px-4 py-2">${record.date}</td>
                        <td class="px-4 py-2">${record.description}</td>
                        <td class="px-4 py-2">
                            <a href="edit_أحداث.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-700">تعديل</a>
                            <button class="text-red-600 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    recordsTbody.appendChild(row);
                });
            });

        // Delete record
        function deleteRecord(id) {
            fetch('../backend/أحداث.php', {
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
                    const recordsTbody = document.getElementById('records-tbody');
                    const rows = recordsTbody.children;
                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const actionsTd = row.children[3];
                        const deleteButton = actionsTd.children[1];
                        if (deleteButton.onclick.toString().includes(`deleteRecord(${id})`)) {
                            recordsTbody.removeChild(row);
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
            const rows = document.getElementById('records-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.children;
                let isVisible = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().includes(searchValue)) {
                        isVisible = true;
                        break;
                    }
                }
                if (isVisible) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>