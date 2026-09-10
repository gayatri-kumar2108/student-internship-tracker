<?php

session_start();
require_once "../db.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION["student_id"];

$stmt = $conn->prepare(
    "SELECT
        applications.id,
        applications.applied_date,
        applications.status,
        internships.company_name,
        internships.internship_title,
        internships.location,
        internships.duration
     FROM applications
     INNER JOIN internships
     ON applications.internship_id = internships.id
     WHERE applications.student_id = ?
     ORDER BY applications.id DESC"
);

$stmt->bind_param("i", $student_id);
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Applications</title>

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
    background: #2563eb;
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
    max-width: 1100px;
    margin: 40px auto;
    padding: 20px;
}

h1 {
    margin-bottom: 8px;
}

.subtitle {
    color: #666;
    margin-bottom: 25px;
}

.table-container {
    background: white;
    border-radius: 10px;
    overflow-x: auto;

    box-shadow:
        0 4px 15px rgba(0,0,0,0.08);
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

th,
td {
    padding: 15px;
    border-bottom: 1px solid #eee;
    text-align: left;
}

th {
    background: #f3f4f6;
}

.status {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: bold;
}

.applied {
    background: #dbeafe;
    color: #1d4ed8;
}

.shortlisted {
    background: #fef3c7;
    color: #92400e;
}

.selected {
    background: #dcfce7;
    color: #166534;
}

.rejected {
    background: #fee2e2;
    color: #991b1b;
}

.empty {
    background: white;
    padding: 50px;
    text-align: center;
    border-radius: 10px;
}

.button {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 18px;
    background: #2563eb;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}

</style>

</head>

<body>

<div class="navbar">

    <h2>Internship Tracker</h2>

    <div>

        <a href="dashboard.php">Dashboard</a>

        <a href="internships.php">Internships</a>

        <a href="applications.php">My Applications</a>

        <a href="profile.php">Profile</a>

        <a href="logout.php">Logout</a>

    </div>

</div>

<div class="container">

<h1>My Applications</h1>

<p class="subtitle">
Track all your internship applications and their current status.
</p>

<?php if ($result->num_rows > 0): ?>

<div class="table-container">

<table>

<thead>

<tr>

<th>Company</th>
<th>Internship</th>
<th>Location</th>
<th>Duration</th>
<th>Applied Date</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php while ($row = $result->fetch_assoc()): ?>

<?php

$status_class = strtolower(
    $row["status"]
);

?>

<tr>

<td>
<?php
echo htmlspecialchars(
    $row["company_name"]
);
?>
</td>

<td>
<?php
echo htmlspecialchars(
    $row["internship_title"]
);
?>
</td>

<td>
<?php
echo htmlspecialchars(
    $row["location"]
);
?>
</td>

<td>
<?php
echo htmlspecialchars(
    $row["duration"]
);
?>
</td>

<td>
<?php
echo htmlspecialchars(
    date(
        "d-m-Y",
        strtotime($row["applied_date"])
    )
);
?>
</td>

<td>

<span class="status <?php echo $status_class; ?>">

<?php
echo htmlspecialchars(
    $row["status"]
);
?>

</span>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

<?php else: ?>

<div class="empty">

<h2>No Applications Yet</h2>

<p>
You have not applied for any internship.
</p>

<a
    class="button"
    href="internships.php"
>
Browse Internships
</a>

</div>

<?php endif; ?>

</div>

</body>

</html>