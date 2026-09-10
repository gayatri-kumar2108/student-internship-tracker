<?php

session_start();
require_once "../db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

$result = $conn->query(
    "SELECT
        applications.id,
        applications.applied_date,
        applications.status,
        students.name AS student_name,
        students.email AS student_email,
        students.course,
        internships.company_name,
        internships.internship_title
     FROM applications
     INNER JOIN students
     ON applications.student_id = students.id
     INNER JOIN internships
     ON applications.internship_id = internships.id
     ORDER BY applications.id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Student Applications</title>

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
}

.navbar a {
    color: white;
    text-decoration: none;
    margin-left: 15px;
}

.container {
    max-width: 1250px;
    margin: 40px auto;
    padding: 20px;
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
    min-width: 1000px;
}

th,
td {
    padding: 13px;
    border-bottom: 1px solid #eee;
    text-align: left;
}

th {
    background: #f3f4f6;
}

select {
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

button {
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    background: #2563eb;
    color: white;
    cursor: pointer;
}

.empty {
    background: white;
    padding: 40px;
    text-align: center;
}

</style>

</head>

<body>

<div class="navbar">

<h2>Internship Tracker - Admin</h2>

<div>

<a href="dashboard.php">Dashboard</a>

<a href="manage_internships.php">Internships</a>

<a href="logout.php">Logout</a>

</div>

</div>

<div class="container">

<h1>Student Applications</h1>

<p>
Review applications and update their status.
</p>

<?php if ($result->num_rows > 0): ?>

<div class="table-container">

<table>

<thead>

<tr>

<th>Student</th>
<th>Email</th>
<th>Course</th>
<th>Company</th>
<th>Internship</th>
<th>Applied Date</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php while ($row = $result->fetch_assoc()): ?>

<tr>

<td>
<?php
echo htmlspecialchars(
    $row["student_name"]
);
?>
</td>

<td>
<?php
echo htmlspecialchars(
    $row["student_email"]
);
?>
</td>

<td>
<?php
echo htmlspecialchars(
    $row["course"]
);
?>
</td>

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
    date(
        "d-m-Y",
        strtotime($row["applied_date"])
    )
);
?>
</td>

<td>

<form
    method="POST"
    action="update_status.php"
>

<input
    type="hidden"
    name="application_id"
    value="<?php echo $row["id"]; ?>"
>

<select name="status">

<option
    value="Applied"
    <?php
    if ($row["status"] == "Applied")
        echo "selected";
    ?>
>
Applied
</option>

<option
    value="Shortlisted"
    <?php
    if ($row["status"] == "Shortlisted")
        echo "selected";
    ?>
>
Shortlisted
</option>

<option
    value="Selected"
    <?php
    if ($row["status"] == "Selected")
        echo "selected";
    ?>
>
Selected
</option>

<option
    value="Rejected"
    <?php
    if ($row["status"] == "Rejected")
        echo "selected";
    ?>
>
Rejected
</option>

</select>

<button type="submit">
Update
</button>

</form>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

<?php else: ?>

<div class="empty">

<h2>No Applications</h2>

<p>
Students have not submitted any applications yet.
</p>

</div>

<?php endif; ?>

</div>

</body>

</html>