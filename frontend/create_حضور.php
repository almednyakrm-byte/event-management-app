<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Module slug
$mod_slug = 'حضور';

// Page title
$page_title = 'Create ' . $mod_slug;

// Include header
require_once 'header.php';
?>

<!-- Content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
    <h1 class="text-3xl text-emerald-600"><?= $page_title ?></h1>
    <form id="create-form" class="mt-6">
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-teal-500 text-xs font-bold mb-2" for="employee_id">
                    Employee ID
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="employee_id" type="text" name="employee_id" required>
            </div>
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-teal-500 text-xs font-bold mb-2" for="date">
                    Date
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="date" type="date" name="date" required>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-teal-500 text-xs font-bold mb-2" for="clock_in">
                    Clock In
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="clock_in" type="time" name="clock_in" required>
            </div>
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-teal-500 text-xs font-bold mb-2" for="clock_out">
                    Clock Out
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="clock_out" type="time" name="clock_out" required>
            </div>
        </div>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" type="submit">
            Create
        </button>
    </form>
</div>

<!-- Include footer -->
<?php require_once 'footer.php'; ?>

<!-- AJAX JavaScript -->
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/<?= $mod_slug ?>.php',
                data: $(this).serialize(),
                success: function() {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>