<?php

session_start();

require_once "../db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

// Check internship ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: manage_internships.php");
    exit();
}

$id = intval($_GET["id"]);

$message = "";
$message_type = "";

// Get existing internship
$stmt = $conn->prepare(
    "SELECT * FROM internships WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {
    $stmt->close();
    header("Location: manage_internships.php");
    exit();
}

$internship = $result->fetch_assoc();
$stmt->close();

// Update internship
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $company_name = trim($_POST["company_name"]);
    $internship_title = trim($_POST["internship_title"]);
    $description = trim($_POST["description"]);
    $skills_required = trim($_POST["skills_required"]);
    $location = trim($_POST["location"]);
    $duration = trim($_POST["duration"]);
    $last_date = $_POST["last_date"];

    if (
        empty($company_name) ||
        empty($internship_title) ||
        empty($description) ||
        empty($skills_required) ||
        empty($location) ||
        empty($duration) ||
        empty($last_date)
    ) {

        $message = "Please fill in all fields.";
        $message_type = "error";

    } else {

        $update = $conn->prepare(
            "UPDATE internships
             SET company_name = ?,
                 internship_title = ?,
                 description = ?,
                 skills_required = ?,
                 location = ?,
                 duration = ?,
                 last_date = ?
             WHERE id = ?"
        );

        $update->bind_param(
            "sssssssi",
            $company_name,
            $internship_title,
            $description,
            $skills_required,
            $location,
            $duration,
            $last_date,
            $id
        );

        if ($update->execute()) {

            $message = "Internship updated successfully!";
            $message_type = "success";

            // Update displayed values
            $internship["company_name"] = $company_name;
            $internship["internship_title"] = $internship_title;
            $internship["description"] = $description;
            $internship["skills_required"] = $skills_required;
            $internship["location"] = $location;
            $internship["duration"] = $duration;
            $internship["last_date"] = $last_date;

        } else {

            $message = "Error updating internship.";
            $message_type = "error";
        }

        $update->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Internship</title>

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
            width: 650px;
            max-width: 92%;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        h1 {
            text-align: center;
            margin-top: 0;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
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
            font-size: 15px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 25px;
            border: none;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .message {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            text-align: center;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
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

        <a href="manage_internships.php">
            Manage Internships
        </a>

        <a href="logout.php">
            Logout
        </a>

    </div>

</div>

<div class="container">

    <h1>Edit Internship</h1>

    <p class="subtitle">
        Update internship opportunity details
    </p>

    <?php if ($message != ""): ?>

        <div class="message <?php echo $message_type; ?>">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>

    <form method="POST">

        <label>Company Name *</label>

        <input
            type="text"
            name="company_name"
            value="<?php echo htmlspecialchars($internship["company_name"]); ?>"
            required
        >

        <label>Internship Title *</label>

        <input
            type="text"
            name="internship_title"
            value="<?php echo htmlspecialchars($internship["internship_title"]); ?>"
            required
        >

        <label>Description *</label>

        <textarea
            name="description"
            required
        ><?php echo htmlspecialchars($internship["description"]); ?></textarea>

        <label>Skills Required *</label>

        <input
            type="text"
            name="skills_required"
            value="<?php echo htmlspecialchars($internship["skills_required"]); ?>"
            required
        >

        <label>Location *</label>

        <input
            type="text"
            name="location"
            value="<?php echo htmlspecialchars($internship["location"]); ?>"
            required
        >

        <label>Duration *</label>

        <input
            type="text"
            name="duration"
            value="<?php echo htmlspecialchars($internship["duration"]); ?>"
            required
        >

        <label>Application Last Date *</label>

        <input
            type="date"
            name="last_date"
            value="<?php echo htmlspecialchars($internship["last_date"]); ?>"
            required
        >

        <button type="submit">
            Update Internship
        </button>

    </form>

</div>

</body>

</html>