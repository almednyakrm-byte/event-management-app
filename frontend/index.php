<?php
// Session check
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تطبيق إدارة أحداث</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <div class="flex justify-end mb-4">
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="logout()">تسجيل الخروج</button>
        </div>
        <h1 class="text-3xl text-emerald-600 mb-4">مرحباً!</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-lg text-emerald-600 mb-2">إحصائيات عامة</h2>
                <div id="events-count" class="text-3xl text-teal-500 mb-2">0</div>
                <div class="text-gray-600">عدد الأحداث</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-lg text-emerald-600 mb-2">إحصائيات التذاكر</h2>
                <div id="tickets-count" class="text-3xl text-teal-500 mb-2">0</div>
                <div class="text-gray-600">عدد التذاكر</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-lg text-emerald-600 mb-2">إحصائيات الحضور</h2>
                <div id="attendance-count" class="text-3xl text-teal-500 mb-2">0</div>
                <div class="text-gray-600">عدد الحضور</div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
            <a href="events.php" class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-lg text-emerald-600 mb-2">إدارة الأحداث</h2>
                <div class="text-gray-600">إضافة، تعديل، حذف الأحداث</div>
            </a>
            <a href="tickets.php" class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-lg text-emerald-600 mb-2">إدارة التذاكر</h2>
                <div class="text-gray-600">إضافة، تعديل، حذف التذاكر</div>
            </a>
            <a href="attendance.php" class="bg-white rounded-lg shadow-md p-4 glassmorphism">
                <h2 class="text-lg text-emerald-600 mb-2">إدارة الحضور</h2>
                <div class="text-gray-600">إضافة، تعديل، حذف سجلات الحضور</div>
            </a>
        </div>
    </div>

    <script>
        // Fetch stats dynamically via Javascript API calls
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('events-count').innerHTML = data.eventsCount;
                document.getElementById('tickets-count').innerHTML = data.ticketsCount;
                document.getElementById('attendance-count').innerHTML = data.attendanceCount;
            });

        // Logout function
        function logout() {
            fetch('api/logout.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'login.php';
                    }
                });
        }
    </script>

    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
    </style>
</body>
</html>