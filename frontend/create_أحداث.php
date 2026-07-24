<?php
// create_أحداث.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include_once '../config.php';

$mod_slug = 'أحداث';

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة حدث</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8 mt-10 bg-white rounded-xl shadow-md">
        <h2 class="text-3xl text-emerald-600 font-bold mb-4">إضافة حدث</h2>
        <form id="create-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">العنوان</label>
                <input type="text" id="title" name="title" class="mt-1 focus:ring-emerald-600 focus:border-emerald-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">الوصف</label>
                <textarea id="description" name="description" class="mt-1 focus:ring-emerald-600 focus:border-emerald-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">التاريخ</label>
                <input type="date" id="date" name="date" class="mt-1 focus:ring-emerald-600 focus:border-emerald-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="time" class="block text-sm font-medium text-gray-700">الوقت</label>
                <input type="time" id="time" name="time" class="mt-1 focus:ring-emerald-600 focus:border-emerald-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="location" class="block text-sm font-medium text-gray-700">الموقع</label>
                <input type="text" id="location" name="location" class="mt-1 focus:ring-emerald-600 focus:border-emerald-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <button type="submit" class="py-2 px-4 bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-600 focus:ring-offset-emerald-200 text-white w-full transition ease-in-out duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 rounded-lg">إضافة</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/أحداث.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_أحداث.php';
                    }
                });
            });
        });
    </script>
</body>
</html>