<?php

session_start();

require_once "../db.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit();
}

// Check internship ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: internships.php");
    exit();
}

$internship_id = intval($_GET["id"]);
$student_id = $_SESSION["student_id"];

$message = "";
$message_type = "";

// Get internship details
$stmt = $conn->prepare(
    "SELECT * FROM internships WHERE id = ?"
);

$stmt->bind_param("i", $internship_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {

    $stmt->close();

    header("Location: internships.php");
    exit();
}

$internship = $result->fetch_assoc();

$stmt->close();


// Check if student already applied
$check = $conn->prepare(
    "SELECT id
     FROM applications
     WHERE student_id = ?
     AND internship_id = ?"
);

$check->bind_param(
    "ii",
    $student_id,
    $internship_id
);

$check->execute();

$application_result = $check->get_result();

$already_applied = ($application_result->num_rows > 0);

$check->close();


// Handle application
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$already_applied) {

    // Check application deadline
    $today = date("Y-m-d");

    if ($internship["last_date"] < $today) {

        $message = "The application deadline has passed.";
        $message_type = "error";

    } else {

        $apply = $conn->prepare(
            "INSERT INTO applications
            (student_id, internship_id, status)
            VALUES (?, ?, 'Applied')"
        );

        $apply->bind_param(
            "ii",
            $student_id,
            $internship_id
        );

        if ($apply->execute()) {

            $message = "Application submitted successfully!";
            $message_type = "success";

            $already_applied = true;

        } else {

            $message = "Unable to submit application.";
            $message_type = "error";
        }

        $apply->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?php echo htmlspecialchars($internship["internship_title"]); ?>
    </title>

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
            max-width: 850px;
            margin: 40px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 35px;
            border-radius: 12px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.08);
        }

        h1 {
            margin-top: 0;
            color: #111827;
        }

        .company {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 25px;
        }

        .info {
            margin: 14px 0;
            padding: 12px;
            background: #f8fafc;
            border-radius: 6px;
        }

        .section {
            margin-top: 30px;
        }

        .section h3 {
            color: #111827;
        }

        .description {
            line-height: 1.7;
            color: #444;
        }

        .skills {
            line-height: 1.7;
        }

        .apply-button {
            width: 100%;
            margin-top: 30px;
            padding: 14px;

            border: none;
            border-radius: 7px;

            background: #16a34a;
            color: white;

            font-size: 17px;
            cursor: pointer;
        }

        .apply-button:hover {
            background: #15803d;
        }

        .disabled {
            background: #6b7280;
            cursor: not-allowed;
        }

        .message {
            padding: 14px;
            border-radius: 7px;
            text-align: center;
            margin-bottom: 20px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
            color: #2563eb;
            text-decoration: none;
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
            My Applications
        </a>

        <a href="logout.php">
            Logout
        </a>

    </div>

</div>


<div class="container">

    <a class="back" href="internships.php">
        ← Back to Internships
    </a>


    <div class="card">

        <h1>

            <?php
            echo htmlspecialchars(
                $internship["internship_title"]
            );
            ?>

        </h1>


        <div class="company">

            <?php
            echo htmlspecialchars(
                $internship["company_name"]
            );
            ?>

        </div>


        <?php if ($message != ""): ?>

            <div class="message <?php echo $message_type; ?>">

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php endif; ?>


        <div class="info">

            <strong>Location:</strong>

            <?php
            echo htmlspecialchars(
                $internship["location"]
            );
            ?>

        </div>


        <div class="info">

            <strong>Duration:</strong>

            <?php
            echo htmlspecialchars(
                $internship["duration"]
            );
            ?>

        </div>


        <div class="info">

            <strong>Application Last Date:</strong>

            <?php
            echo htmlspecialchars(
                $internship["last_date"]
            );
            ?>

        </div>


        <div class="section">

            <h3>
                Internship Description
            </h3>

            <p class="description">

                <?php
                echo nl2br(
                    htmlspecialchars(
                        $internship["description"]
                    )
                );
                ?>

            </p>

        </div>


        <div class="section">

            <h3>
                Skills Required
            </h3>

            <p class="skills">

                <?php
                echo htmlspecialchars(
                    $internship["skills_required"]
                );
                ?>

            </p>

        </div>


        <?php if ($already_applied): ?>

            <button
                class="apply-button disabled"
                disabled
            >
                ✓ Already Applied
            </button>

        <?php else: ?>

            <form method="POST">

                <button
                    type="submit"
                    class="apply-button"
                >
                    Apply for Internship
                </button>

            </form>

        <?php endif; ?>


    </div>

</div>

</body>

</html>