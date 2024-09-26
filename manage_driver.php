<?php
include 'db.php';

// Add new driver
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_driver'])) {
    $driver_name = $_POST['driver_name'];
    $vehicle_type = $_POST['vehicle_type'];
    $vehicle_number = $_POST['vehicle_number'];
    $contact_number = $_POST['contact_number']; // New contact number variable

    $sql = "INSERT INTO drivers (driver_name, vehicle_type, vehicle_number, contact_number) VALUES ('$driver_name', '$vehicle_type', '$vehicle_number', '$contact_number')";
    
    if ($conn->query($sql) === TRUE) {
        echo "New driver added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete driver
if (isset($_GET['delete_id'])) {
    $driver_id = $_GET['delete_id'];

    $sql = "DELETE FROM drivers WHERE id = $driver_id";
    if ($conn->query($sql) === TRUE) {
        echo "Driver deleted successfully!";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Fetch driver details
$drivers_result = $conn->query("SELECT * FROM drivers");

// Edit driver (fetch data)
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_result = $conn->query("SELECT * FROM drivers WHERE id = $edit_id");
    $edit_driver = $edit_result->fetch_assoc();
}

// Update driver details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_driver'])) {
    $driver_id = $_POST['driver_id'];
    $driver_name = $_POST['driver_name'];
    $vehicle_type = $_POST['vehicle_type'];
    $vehicle_number = $_POST['vehicle_number'];
    $contact_number = $_POST['contact_number']; // New contact number variable

    $sql = "UPDATE drivers SET driver_name = '$driver_name', vehicle_type = '$vehicle_type', vehicle_number = '$vehicle_number', contact_number = '$contact_number' WHERE id = $driver_id";

    if ($conn->query($sql) === TRUE) {
        echo "Driver updated successfully!";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Drivers</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Existing styles */
        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            background-color: lightgreen;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            background-color: #007bff;
            border-radius: 5px;
        }
        .edit-btn {
            background-color: orange;
        }
        .delete-btn {
            background-color: red;
        }
        .home-btn {
            background-color: green;
            margin-bottom: 20px;
            display: inline-block;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    
    <h1>Manage Drivers</h1>

    <!-- Add/Edit Driver Form -->
    <div class="container">
        <h2><?php echo isset($edit_driver) ? 'Edit Driver' : 'Add New Driver'; ?></h2>
        <form action="manage_driver.php" method="POST">
            <input type="hidden" name="driver_id" value="<?php echo isset($edit_driver) ? $edit_driver['id'] : ''; ?>">
            
            <input type="text" name="driver_name" placeholder="Driver Name" required value="<?php echo isset($edit_driver) ? $edit_driver['driver_name'] : ''; ?>" style="padding: 10px; width: 30%;">

            <!-- Vehicle Type Dropdown -->
            <select name="vehicle_type" required style="padding: 10px; width: 30%;">
                <option value="">Select Vehicle Type</option>
                <option value="Bus" <?php echo isset($edit_driver) && $edit_driver['vehicle_type'] == 'Bus' ? 'selected' : ''; ?>>Bus</option>
                <option value="Van" <?php echo isset($edit_driver) && $edit_driver['vehicle_type'] == 'Van' ? 'selected' : ''; ?>>Van</option>
                <option value="Traveller" <?php echo isset($edit_driver) && $edit_driver['vehicle_type'] == 'Traveller' ? 'selected' : ''; ?>>Traveller</option>
                <option value="Magic" <?php echo isset($edit_driver) && $edit_driver['vehicle_type'] == 'Magic' ? 'selected' : ''; ?>>Magic</option>
            </select>

            <input type="text" name="vehicle_number" placeholder="Vehicle Number" required value="<?php echo isset($edit_driver) ? $edit_driver['vehicle_number'] : ''; ?>" style="padding: 10px; width: 30%;">
            
            <input type="text" name="contact_number" placeholder="Contact Number" required value="<?php echo isset($edit_driver) ? $edit_driver['contact_number'] : ''; ?>" style="padding: 10px; width: 30%;">
            
            <button type="submit" name="<?php echo isset($edit_driver) ? 'update_driver' : 'add_driver'; ?>" style="padding: 10px; margin-top: 10px;">
                <?php echo isset($edit_driver) ? 'Update Driver' : 'Add Driver'; ?>
            </button>
        </form>
    </div>

    <!-- Display Driver List -->
    <div class="container">
        <h2>Driver List</h2>
        <table>
            <tr>
                <th>Driver Name</th>
                <th>Vehicle Type</th>
                <th>Vehicle Number</th>
                <th>Contact Number</th>
                <th>Actions</th>
            </tr>
            <?php if ($drivers_result->num_rows > 0): ?>
                <?php while($row = $drivers_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['driver_name']; ?></td>
                        <td><?php echo $row['vehicle_type']; ?></td>
                        <td><?php echo $row['vehicle_number']; ?></td>
                        <td><?php echo $row['contact_number']; ?></td> <!-- New contact number column -->
                        <td>
                            <a href="manage_driver.php?edit_id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                            <a href="manage_driver.php?delete_id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this driver?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No drivers found</td>
                </tr>
            <?php endif; ?>
        </table>
        <a href="admin.php" class="home-btn">Home</a>
    </div>
    
</body>
</html>
