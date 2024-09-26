<?php
include 'db.php';

// Total students count
$result = $conn->query("SELECT * FROM students");
$total_students = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            color: orange;
            margin-top: 20px;
            font-size: 2.5em;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
        .container {
            width: 400px;
            height: 150px; /* Square-shaped containers */
            margin: 40px;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        .flex-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        .search-container {
            width: auto; /* Adjusts to the search bar's size */
            margin: 20px auto; /* Centered with margin */
            border-radius: 5px; /* Rounded corners */
        }
        input[type="text"] {
            padding: 12px; /* Increased padding */
            width: 300px; /* Larger width for the search box */
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .add-container {
            background-color: skyblue;
        }
        .total-container {
            background-color: lightcoral;
        }
        .driver-container {
            background-color: lightgreen;
        }
        button:hover {
            opacity: 0.9;
        }
        /* Footer styling */
        footer {
            text-align: center;
            background-color: #333;
            color: white;
            padding: 10px;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <h1>Fees Management Dashboard</h1>

    <div class="search-container">
        <form action="search_student.php" method="POST">
            <input type="text" name="search" placeholder="Search Student" required>
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="flex-container">
        <div class="container add-container">
            <h2>Add Students</h2>
            <button onclick="window.location.href='add_student.php'">Add Student</button>
        </div>

        <div class="container total-container">
            <h2>Total Students:-  <?php echo $total_students; ?></h2>
           
            <button onclick="window.location.href='student_list.php'">Show Students</button>
        </div>

        <div class="container driver-container">
            <h2>Manage Driver</h2>
            <button onclick="window.location.href='manage_driver.php'">Manage Driver</button>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        Made by Juber Shaikh | 91+ 7020052862 | jsjubershaikh25@gmail.com
    </footer>

</body>
</html>
