<?php
include 'db.php';

$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch student and fees data
$fees_data = [];
if ($student_id) {
    $result = $conn->query("SELECT * FROM fees_record.fees WHERE student_id = $student_id");
    if ($result) {
        $fees_data = $result->fetch_assoc();
    } else {
        echo "Error fetching data: " . $conn->error; // Debugging line
    }
}

// Months names
$months = [
    1 => "June",
    2 => "July",
    3 => "August",
    4 => "September",
    5 => "October",
    6 => "November",
    7 => "December",
    8 => "January",
    9 => "February",
    10 => "March",
    11 => "April",
];

// Handle form submission (Fees Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $month = isset($_POST['month']) ? intval($_POST['month']) : 0;
    $paid_fees = isset($_POST['paid_fees']) ? floatval($_POST['paid_fees']) : 0;
    $monthly_fees = isset($_POST['monthly_fees']) ? floatval($_POST['monthly_fees']) : 0;

    // Update total monthly fees for the current month
    if (!$conn->query("UPDATE fees_record.fees SET monthly_fees = $monthly_fees WHERE student_id = $student_id")) {
        echo "Error updating monthly fees: " . $conn->error; // Debugging line
    }

    // Update fees for the given month
    if (!$conn->query("UPDATE fees_record.fees SET {$months[$month]} = $paid_fees WHERE student_id = $student_id")) {
        echo "Error updating fees: " . $conn->error; // Debugging line
    }

    // Generate receipt if fees are paid
    if ($paid_fees > 0) {
        $receipt_date = date('Y-m-d H:i:s');
        $remaining_fees = $monthly_fees - $paid_fees;
        $receipt_content = "Receipt for Student ID: {$student_id}\nDate: $receipt_date\nMonth: {$months[$month]}\nPaid Fees: $paid_fees\nRemaining Fees: $remaining_fees";

        // Save receipt in a text file
        if (!file_exists('receipts')) {
            mkdir('receipts', 0777, true);
        }
        $receipt_path = "receipts/receipt_{$student_id}_{$months[$month]}_" . date('YmdHis') . ".txt";
        file_put_contents($receipt_path, $receipt_content);

        // Save the history record
        if (!$conn->query("INSERT INTO fees_record.fees_history (student_id, month, paid_fees, remaining_fees, receipt) VALUES ($student_id, '{$months[$month]}', $paid_fees, $remaining_fees, '$receipt_path')")) {
            echo "Error saving receipt history: " . $conn->error; // Debugging line
        }
    }

    // Refresh the data after submission
    header("Location: fees_details.php?id=$student_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Fees</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        input[type="number"] {
            padding: 5px;
            width: 100px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .update-btn {
            padding: 8px 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .update-btn:hover {
            background-color: #218838;
        }

        .status-section {
            margin-top: 50px;
        }

        .back-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<h2>Manage Fees for Student ID: <?php echo $student_id; ?></h2>

<!-- Fees Update Table -->
<table>
    <thead>
        <tr>
            <th>Month</th>
            <th>Total Monthly Fees</th>
            <th>Paid Fees</th>
            <th>Update</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($months as $month_num => $month_name): ?>
            <tr>
                <td><?php echo $month_name; ?></td>
                <form method="POST" action="">
                    <td>
                        <input type="number" name="monthly_fees" value="<?php echo $fees_data['monthly_fees'] ?? 0; ?>" min="0" step="0.01">
                    </td>
                    <td>
                        <input type="number" name="paid_fees" value="<?php echo $fees_data[$month_name] ?? 0; ?>" min="0" step="0.01">
                    </td>
                    <td>
                        <input type="hidden" name="month" value="<?php echo $month_num; ?>">
                        <button type="submit" class="update-btn">Update</button>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- New Table for Month and Status -->
<div class="status-section">
    <h3>Fees Status</h3>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Status</th>
                <th>Remaining Fees</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($months as $month_num => $month_name): ?>
                <tr>
                    <td><?php echo $month_name; ?></td>
                    <td>
                        <?php 
                        // Check if a receipt exists for the current month
                        $receipt_path = "receipts/receipt_{$student_id}_{$month_name}_" . date('YmdHis') . ".txt";
                        if (file_exists($receipt_path)) {
                            echo "<a href='{$receipt_path}' download>Receipt Available</a>";
                        } else {
                            echo "No Receipt";
                        }
                        ?>
                    </td>
                    <td>
                        <?php 
                        // Display remaining fees for the current month
                        $current_monthly_fees = $fees_data['monthly_fees'] ?? 0;
                        $paid_amount = $fees_data[$month_name] ?? 0;
                        $remaining_fees = max(0, $current_monthly_fees - $paid_amount);
                        echo $remaining_fees;
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<a href="student_list.php" class="back-btn">Back to Student List</a>

</body>
</html>
