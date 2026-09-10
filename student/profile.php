<?php

session_start();
require_once "../db.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION["student_id"];

$message = "";

$stmt = $conn->prepare(
    "SELECT * FROM students WHERE id = ?"
);

$stmt->bind_param("i", $student_id);
$stmt->execute();

$result = $stmt->get_result();

$student = $result->fetch_assoc();

$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $phone = trim($_POST["phone"]);
    $course = trim($_POST["course"]);
    $skills = trim($_POST["skills"]);

    if (
        empty($name) ||
        empty($course)
    ) {

        $message = "Name and course are required.";

    } else {

        $update = $conn->prepare(
            "UPDATE students
             SET name = ?,
                 phone = ?,
                 course = ?,
                 skills = ?
             WHERE id = ?"
        );

        $update->bind_param(
            "ssssi",
            $name,
            $phone,
            $course,
            $skills,
            $student_id
        );

        if ($update->execute()) {

            $_SESSION["student_name"] = $name;

            $student["name"] = $name;
            $student["phone"] = $phone;
            $student["course"] = $course;
            $student["skills"] = $skills;

            $message = "Profile updated successfully.";

        } else {

            $message = "Unable to update profile.";

        }

        $update->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Profile</title>

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
}

.navbar a {
    color: white;
    text-decoration: none;
    margin-left: 15px;
}

.container {
    width: 600px;
    max-width: 92%;
    margin: 40px auto;

    background: white;
    padding: 30px;

    border-radius: 10px;

    box-shadow:
        0 4px 15px rgba(0,0,0,0.08);
}

h1 {
    text-align: center;
}

label {
    display: block;
    margin-top: 15px;
    margin-bottom: 6px;
    font-weight: bold;
}

input,
textarea {
    width: 100%;
    padding: 11px;

    border: 1px solid #ccc;
    border-radius: 6px;
}

textarea {
    min-height: 100px;
}

button {
    width: 100%;
    margin-top: 25px;
    padding: 12px;

    border: none;
    border-radius: 6px;

    background: #2563eb;
    color: white;

    font-size: 16px;
}

.message {
    padding: 12px;
    background: #dcfce7;
    color: #166534;
    border-radius: 6px;
    text-align: center;
    margin-bottom: 15px;
}

</style>

</head>

<body>

<div class="navbar">

<h2>Internship Tracker</h2>

<div>

<a href="dashboard.php">Dashboard</a>

<a href="internships.php">Internships</a>

<a href="applications.php">Applications</a>

<a href="logout.php">Logout</a>

</div>

</div>

<div class="container">

<h1>My Profile</h1>

<?php if ($message != ""): ?>

<div class="message">

<?php echo htmlspecialchars($message); ?>

</div>

<?php endif; ?>

<form method="POST">

<label>Full Name</label>

<input
    type="text"
    name="name"
    value="<?php echo htmlspecialchars($student["name"]); ?>"
    required
>

<label>Email</label>

<input
    type="email"
    value="<?php echo htmlspecialchars($student["email"]); ?>"
    disabled
>

<label>Phone</label>

<input
    type="text"
    name="phone"
    value="<?php echo htmlspecialchars($student["phone"]); ?>"
>

<label>Course</label>

<input
    type="text"
    name="course"
    value="<?php echo htmlspecialchars($student["course"]); ?>"
    required
>

<label>Skills</label>

<textarea
    name="skills"
><?php echo htmlspecialchars($student["skills"]); ?></textarea>

<button type="submit">
Update Profile
</button>

</form>

</div>

</body>

</html>