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

// Define module slug
$mod_slug = 'تذاكر';

// Define page title
$page_title = 'Create تذاكر';

// Include header
require_once 'header.php';
?>

<!-- Content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
    <h1 class="text-3xl text-emerald-600"><?= $page_title ?></h1>
    <form id="create-form" class="mt-6">
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                    Title
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="title" type="text" placeholder="Title">
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                    Description
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="description" type="text" placeholder="Description">
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                    Price
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="price" type="number" placeholder="Price">
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                    Status
                </label>
                <select class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-teal-500" id="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" type="submit">
            Create تذاكر
        </button>
    </form>
</div>

<!-- JavaScript -->
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = {
                title: $('#title').val(),
                description: $('#description').val(),
                price: $('#price').val(),
                status: $('#status').val()
            };
            $.ajax({
                type: 'POST',
                url: '../backend/<?= $mod_slug ?>.php',
                data: formData,
                success: function(data) {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>