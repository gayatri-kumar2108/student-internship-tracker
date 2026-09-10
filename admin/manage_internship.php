<?php

session_start();

require_once "../db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

// Delete internship
if (isset($_GET["delete"])) {

    $id = intval($_GET["delete"]);

    $stmt = $conn->prepare(
        "DELETE FROM internships WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_internships.php");
    exit();
}

// Get all internships
$result = $conn->query(
    "SELECT * FROM internships ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Internships</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
        }

        .navbar {
            background: #111827;
            color: white;
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }

        .container {
            max-width: 1200px;
            margin: 35px auto;
            padding: 20px;
        }

        .top-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .top-section h1 {
            margin: 0;
        }

        .add-btn {
            background: #2563eb;
            color: white;
            padding: 11px 18px;
            text-decoration: none;
            border-radius: 6px;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            overflow-x: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th,
        td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f3f4f6;
        }

        tr:hover {
            background: #fafafa;
        }

        .edit {
            background: #2563eb;
            color: white;
            padding: 7px 12px;
            text-decoration: none;
            border-radius: 5px;
        }

        .delete {
            background: #dc2626;
            color: white;
            padding: 7px 12px;
            text-decoration: none;
            border-radius: 5px;
        }

        .empty {
            text-align: center;
            padding: 40px;
            color: #666;
        }

    </style>

</head>

<body>

<div class="navbar">

    <h2>Internship Tracker - Admin</h2>

    <div>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="logout.php">
            Logout
        </a>

    </div>

</div>

<div class="container">

    <div class="top-section">

        <h1>Manage Internships</h1>

        <a class="add-btn" href="add_internship.php">
            + Add Internship
        </a>

    </div>

    <div class="table-container">

        <?php if ($result->num_rows > 0): ?>

            <table>

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Company</th>
                        <th>Internship</th>
                        <th>Location</th>
                        <th>Duration</th>
                        <th>Last Date</th>
                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                <?php while ($row = $result->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo $row["id"]; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["company_name"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["internship_title"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["location"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["duration"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row["last_date"]); ?>
                        </td>

                        <td>

                            <a
                                class="edit"
                                href="edit_internship.php?id=<?php echo $row["id"]; ?>"
                            >
                                Edit
                            </a>

                            <a
                                class="delete"
                                href="manage_internships.php?delete=<?php echo $row["id"]; ?>"
                                onclick="return confirm('Are you sure you want to delete this internship?');"
                            >
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        <?php else: ?>

            <div class="empty">

                <h3>No internships found</h3>

                <p>Add your first internship opportunity.</p>

            </div>

        <?php endif; ?>

    </div>

</div>

</body>

</html>