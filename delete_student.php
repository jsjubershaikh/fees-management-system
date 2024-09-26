<?php
include 'db.php';

// Check if ID is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete student from database
    $sql = "DELETE FROM students WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Student deleted successfully</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
    }
} else {
    echo "<p style='color: red;'>Invalid request</p>";
}

$conn->close();
?>

<a href="student_list.php" class="back-link">Back to Student List</a>
