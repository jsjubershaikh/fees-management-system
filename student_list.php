<?php
include 'db.php';

// Search functionality
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
    $result = $conn->query("SELECT * FROM students WHERE full_name LIKE '%$search_query%'");
} else {
    $result = $conn->query("SELECT * FROM students");
}

// Check if query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Initialize arrays for categories
$kg_students = [];
$other_students = [];

// Categorize students
if ($result->num_rows > 0) {
    while ($student = $result->fetch_assoc()) {
        if ($student['class'] === 'J.KG/S.KG') {
            $kg_students[] = $student;
        } else {
            $other_students[] = $student; // 1st to 10th class students
        }
    }
}

// Function to display student table
function displayStudentTable($students, $title) {
    echo "<h2>$title</h2>";
    echo "<table class='student-table'>
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Contact Number</th>
                <th>Address</th>
                <th>Class</th>
                <th>Fees Details</th>
                <th>Actions</th>
            </tr>";
    
    foreach ($students as $student) {
        echo "<tr>
                <td>{$student['id']}</td>
                <td><img src='uploads/{$student['photo']}' alt='Student Photo' width='50' class='student-photo'></td>
                <td>" . htmlspecialchars($student['full_name']) . "</td>
                <td>" . htmlspecialchars($student['contact_number']) . "</td>
                <td>" . htmlspecialchars($student['address']) . "</td>
                <td>" . htmlspecialchars($student['class']) . "</td>
                <td><a href='fees_details.php?id={$student['id']}' class='btn manage-fees-btn'>Manage Fees</a></td>
                <td>
                    <a href='edit_student.php?id={$student['id']}' class='btn edit-btn'>Edit</a>
                    <a href='#' class='btn delete-btn' onclick=\"confirmDelete({$student['id']}); return false;\">Delete</a>
                </td>
              </tr>";
    }
    echo "</table>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            font-size: 2.5em;
            margin-bottom: 40px;
        }

        h2 {
            font-size: 1.8em;
            color: #34495e;
            margin-bottom: 15px;
            border-bottom: 2px solid #bdc3c7;
            padding-bottom: 5px;
        }

        .search-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .search-container input[type="text"] {
            width: 60%;
            padding: 12px;
            border-radius: 25px;
            border: 1px solid #ccc;
            font-size: 1.1em;
            margin-right: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .search-container button {
            padding: 12px 20px;
            font-size: 1.1em;
            border: none;
            border-radius: 25px;
            background-color: #2980b9;
            color: white;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .search-container button:hover {
            background-color: #3498db;
        }

        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 50px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        .student-table th, .student-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 1.1em;
        }

        .student-table th {
            background-color: #2980b9;
            color: white;
            font-weight: bold;
        }

        .student-table tr:hover {
            background-color: #f2f2f2;
        }

        .student-photo {
            border-radius: 5px;
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        .btn {
            display: inline-block;
            padding: 8px 15px; /* Medium size */
            border-radius: 4px; /* Boxy look with a slight curve */
            font-weight: bold;
            font-size: small; /* Medium font size */
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            border: 1px solid transparent;
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .manage-fees-btn {
            background-color: #27ae60;
            color: white;
        }

        .manage-fees-btn:hover {
            background-color: #2ecc71;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .edit-btn {
            background-color: #3498db;
            color: white;
            border-color: #2980b9; /* Solid border */
        }

        .edit-btn:hover {
            background-color: #2980b9;
            border-color: #1f639b;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
            border-color: #c0392b;
        }

        .delete-btn:hover {
            background-color: #c0392b;
            border-color: #a03224;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #7f8c8d;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.2em;
        }

        .back-link:hover {
            background-color: #95a5a6;
        }
    </style>
    <script>
        function confirmDelete(studentId) {
            if (confirm('Are you sure you want to delete this student?')) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        alert("Student deleted successfully.");
                        location.reload();
                    } else {
                        alert("Error deleting student.");
                    }
                };
                xhr.send("id=" + studentId + "&action=delete");
            }
        }
    </script>
</head>
<body>
    <h1>Student List</h1>
    
    <div class="search-container">
        <form action="student_list.php" method="POST">
            <input type="text" name="search" placeholder="Search Student" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <?php 
    // Handle deletion if the request is POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $conn->query("DELETE FROM students WHERE id = $id");
        echo json_encode(["status" => "success"]);
        exit; // Prevent further output
    }

    displayStudentTable($kg_students, 'J.KG / S.KG Students');
    displayStudentTable($other_students, '1st to 10th Grade Students');
    ?>

    <a href="admin.php" class="back-link">Back to Dashboard</a>
</body>
</html>
