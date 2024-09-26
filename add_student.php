<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $class = $_POST['class'];
    $photo = $_FILES['photo']['name'];

    // File upload
    move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photo);

    $sql = "INSERT INTO students (full_name, contact_number, address, photo, class) VALUES ('$full_name', '$contact_number', '$address', '$photo', '$class')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>New student added successfully</p>";
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
    <title>Add Student</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #eaeff2;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #fff;
            background-color: #5A9; /* Classy dark green */
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            font-size: 2.2em;
            letter-spacing: 1px;
        }
        .form-container {
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ddd;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .form-container:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.15);
        }
        input[type="text"],
        input[type="file"],
        select {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus,
        select:focus {
            border-color: #5A9; /* Classy dark green */
            outline: none;
            background-color: #f0f0f0;
        }
        button {
            background-color: #5A9; /* Classy dark green */
            color: white;
            border: none;
            padding: 15px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        button:hover {
            background-color: #48875a; /* Darker green */
            transform: scale(1.05);
        }
        .back-link {
            display: block;
            text-align: center;
            font-size: larger;
            font-weight: bold;
            margin-top: 20px;
            color: #5A9; /* Classy dark green */
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .back-link:hover {
            color: #48875a;
        }
        label {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Add Student</h1>
    <div class="form-container">
        <form action="add_student.php" method="POST" enctype="multipart/form-data">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" placeholder="Enter full name" required>
            
            <label for="contact_number">Contact Number</label>
            <input type="text" id="contact_number" name="contact_number" placeholder="Enter contact number" required>
            
            <label for="address">Address</label>
            <input type="text" id="address" name="address" placeholder="Enter address" required>
            
            <label for="class">Class</label>
            <select id="class" name="class" required>
                <option value="">Select Class</option>
                <option value="J.KG/S.KG">J.KG / S.KG</option>
                <option value="1st">1st</option>
                <option value="2nd">2nd</option>
                <option value="3rd">3rd</option>
                <option value="4th">4th</option>
                <option value="5th">5th</option>
                <option value="6th">6th</option>
                <option value="7th">7th</option>
                <option value="8th">8th</option>
                <option value="9th">9th</option>
                <option value="10th">10th</option>
            </select>
            
            <label for="photo">Upload Photo</label>
            <input type="file" id="photo" name="photo" required>
            
            <button type="submit">Add Student</button>
        </form>
    </div>
    <a href="admin.php" class="back-link">Back to Dashboard</a>
</body>
</html>
