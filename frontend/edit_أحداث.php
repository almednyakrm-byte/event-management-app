<?php
// edit_أحداث.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_أحداث.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل حدث</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl text-emerald-600 mb-4">تعديل حدث</h2>
        <form id="edit-event-form">
            <div class="mb-4">
                <label for="title" class="block text-sm text-emerald-600 mb-2">العنوان</label>
                <input type="text" id="title" name="title" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-emerald-600 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm text-emerald-600 mb-2">الوصف</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-emerald-600 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600"></textarea>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-sm text-emerald-600 mb-2">التاريخ</label>
                <input type="date" id="date" name="date" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-emerald-600 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-lg">حفظ التعديلات</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-event-form');
        const id = <?php echo $id; ?>;

        // Fetch existing record details
        fetch(`../backend/أحداث.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('title').value = data.title;
                document.getElementById('description').value = data.description;
                document.getElementById('date').value = data.date;
            });

        // Submit form
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch(`../backend/أحداث.php`, {
                method: 'PUT',
                body: JSON.stringify({
                    id: id,
                    title: formData.get('title'),
                    description: formData.get('description'),
                    date: formData.get('date')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_أحداث.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>