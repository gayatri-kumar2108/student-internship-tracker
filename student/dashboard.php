<?php

session_start();
require_once "../db.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION["student_id"];

$total_internships = 0;
$total_applications = 0;
$shortlisted = 0;
$selected = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM internships"
);

$total_internships =
    $result->fetch_assoc()["total"];

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM applications
     WHERE student_id = ?"
);

$stmt->bind_param("i", $student_id);
$stmt->execute();

$total_applications =
    $stmt->get_result()->fetch_assoc()["total"];

$stmt->close();

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM applications
     WHERE student_id = ?
     AND status = 'Shortlisted'"
);

$stmt->bind_param("i", $student_id);
$stmt->execute();

$shortlisted =
    $stmt->get_result()->fetch_assoc()["total"];

$stmt->close();

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM applications
     WHERE student_id = ?
     AND status = 'Selected'"
);

$stmt->bind_param("i", $student_id);
$stmt->execute();

$selected =
    $stmt->get_result()->fetch_assoc()["total"];

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Student Dashboard</title>

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

.logout {
    background: #dc2626;
    padding: 8px 12px;
    border-radius: 5px;
}

.container {
    max-width: 1100px;
    margin: 35px auto;
    padding: 20px;
}

.welcome {
    background: white;
    padding: 25px;
    border-radius: 10px;

    box-shadow:
        0 3px 12px rgba(0,0,0,0.08);
}

.welcome h1 {
    margin-top: 0;
}

.stats {
    display: grid;
    grid-template-columns:
        repeat(4, 1fr);

    gap: 20px;
    margin-top: 25px;
}

.stat {
    background: white;
    padding: 25px;
    text-align: center;
    border-radius: 10px;

    box-shadow:
        0 3px 12px rgba(0,0,0,0.08);
}

.stat h2 {
    color: #2563eb;
    font-size: 30px;
    margin: 5px;
}

.actions {
    display: grid;
    grid-template-columns:
        repeat(3, 1fr);

    gap: 20px;
    margin-top: 25px;
}

.action {
    background: white;
    padding: 25px;
    text-align: center;
    border-radius: 10px;

    box-shadow:
        0 3px 12px rgba(0,0,0,0.08);
}

.action a {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 15px;

    background: #2563eb;
    color: white;

    text-decoration: none;
    border-radius: 6px;
}

@media (max-width: 750px) {

    .stats,
    .actions {
        grid-template-columns: 1fr;
    }

}

</style>

</head>

<body>

<div class="navbar">

<h2>Internship Tracker</h2>

<div>

<a href="dashboard.php">
Dashboard
</a>

<a href="internships.php">
Internships
</a>

<a href="applications.php">
Applications
</a>

<a href="profile.php">
Profile
</a>

<a
    class="logout"
    href="logout.php"
>
Logout
</a>

</div>

</div>

<div class="container">

<div class="welcome">

<h1>

Welcome,
<?php
echo htmlspecialchars(
    $_SESSION["student_name"]
);
?>!

</h1>

<p>
Find internships, apply online and track your applications.
</p>

</div>

<div class="stats">

<div class="stat">

<h2>
<?php echo $total_internships; ?>
</h2>

<p>
Available Internships
</p>

</div>

<div class="stat">

<h2>
<?php echo $total_applications; ?>
</h2>

<p>
My Applications
</p>

</div>

<div class="stat">

<h2>
<?php echo $shortlisted; ?>
</h2>

<p>
Shortlisted
</p>

</div>

<div class="stat">

<h2>
<?php echo $selected; ?>
</h2>

<p>
Selected
</p>

</div>

</div>

<div class="actions">

<div class="action">

<h3>
Browse Internships
</h3>

<p>
Find suitable internship opportunities.
</p>

<a href="internships.php">
Browse
</a>

</div>

<div class="action">

<h3>
My Applications
</h3>

<p>
Check your application status.
</p>

<a href="applications.php">
View
</a>

</div>

<div class="action">

<h3>
My Profile
</h3>

<p>
Update your student information.
</p>

<a href="profile.php">
Profile
</a>

</div>

</div>

</div>

</body>

</html>