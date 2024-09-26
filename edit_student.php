<?php
include 'db.php';

// Check if ID is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch student data
    $result = $conn->query("SELECT * FROM students WHERE id = $id");
    $student = $result->fetch_assoc();
} else {
    header("Location: student_list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $class = $_POST['class'];
    $photo = $_FILES['photo']['name'];

    // Update student data
    $sql = "UPDATE students SET full_name='$full_name', contact_number='$contact_number', address='$address', class='$class'";

    if (!empty($photo)) {
        move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photo);
        $sql .= ", photo='$photo'";
    }

    $sql .= " WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Student updated successfully</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: orange;
            margin-bottom: 30px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        input[type="text"], select, input[type="file"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #3498db;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
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
</head>
<body>
    <h1>Edit Student</h1>
    <form action="edit_student.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <input type="text" name="full_name" placeholder="Full Name" value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
        <input type="text" name="contact_number" placeholder="Contact Number" value="<?php echo htmlspecialchars($student['contact_number']); ?>" required>
        <input type="text" name="address" placeholder="Address" value="<?php echo htmlspecialchars($student['address']); ?>" required>
        
        <select name="class" required>
            <option value="">Select Class</option>
            <option value="J.KG/S.KG" <?php echo $student['class'] == 'J.KG/S.KG' ? 'selected' : ''; ?>>J.KG / S.KG</option>
            <option value="1st" <?php echo $student['class'] == '1st' ? 'selected' : ''; ?>>1st</option>
            <option value="2nd" <?php echo $student['class'] == '2nd' ? 'selected' : ''; ?>>2nd</option>
            <option value="3rd" <?php echo $student['class'] == '3rd' ? 'selected' : ''; ?>>3rd</option>
            <option value="4th" <?php echo $student['class'] == '4th' ? 'selected' : ''; ?>>4th</option>
            <option value="5th" <?php echo $student['class'] == '5th' ? 'selected' : ''; ?>>5th</option>
            <option value="6th" <?php echo $student['class'] == '6th' ? 'selected' : ''; ?>>6th</option>
            <option value="7th" <?php echo $student['class'] == '7th' ? 'selected' : ''; ?>>7th</option>
            <option value="8th" <?php echo $student['class'] == '8th' ? 'selected' : ''; ?>>8th</option>
            <option value="9th" <?php echo $student['class'] == '9th' ? 'selected' : ''; ?>>9th</option>
            <option value="10th" <?php echo $student['class'] == '10th' ? 'selected' : ''; ?>>10th</option>
        </select>
        
        <input type="file" name="photo" accept="image/*">
        
        <button type="submit">Update Student</button>
    </form>
    <a href="student_list.php" class="back-link">Back to Student List</a>
</body>
</html>
