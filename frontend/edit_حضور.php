<?php
// edit_حضور.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_حضور.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل حضور</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-emerald-600 mb-4">تعديل حضور</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm text-gray-700 mb-2">اسم</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div class="mb-4">
                <label for="date" class="block text-sm text-gray-700 mb-2">تاريخ</label>
                <input type="date" id="date" name="date" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm text-gray-700 mb-2">حالة</label>
                <select id="status" name="status" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
                    <option value="حاضر">حاضر</option>
                    <option value="غائب">غائب</option>
                </select>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm text-white bg-emerald-600 rounded-lg hover:bg-teal-500 focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">حفظ</button>
        </form>
    </div>

    <script>
        const id = <?= $id ?>;
        const form = document.getElementById('edit-form');

        // Fetch existing record details
        fetch(`../backend/حضور.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('date').value = data.date;
                document.getElementById('status').value = data.status;
            });

        // Submit form
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/حضور.php', {
                method: 'PUT',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_حضور.php';
                } else {
                    console.error(data.error);
                }
            });
        });
    </script>
</body>
</html>