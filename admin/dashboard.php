<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

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

        .logout {
            color: white;
            text-decoration: none;
            background: #dc2626;
            padding: 9px 15px;
            border-radius: 6px;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
        }

        .welcome {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .welcome h1 {
            margin-top: 0;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .card h3 {
            margin-top: 0;
        }

        .card a {
            display: inline-block;
            margin-top: 10px;
            background: #111827;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 6px;
        }

        @media (max-width: 700px) {

            .cards {
                grid-template-columns: 1fr;
            }

            .navbar {
                padding: 15px;
            }

        }

    </style>

</head>

<body>

<div class="navbar">

    <h2>Internship Tracker - Admin</h2>

    <a class="logout" href="logout.php">
        Logout
    </a>

</div>

<div class="container">

    <div class="welcome">

        <h1>
            Welcome, <?php echo htmlspecialchars($_SESSION["admin_name"]); ?>!
        </h1>

        <p>
            Manage internship opportunities and student applications.
        </p>

    </div>

    <div class="cards">

        <div class="card">

            <h3>Add Internship</h3>

            <p>
                Add a new internship opportunity.
            </p>

            <a href="add_internship.php">
                Add Internship
            </a>

        </div>

        <div class="card">

            <h3>Manage Internships</h3>

            <p>
                View, edit or delete internships.
            </p>

            <a href="manage_internships.php">
                Manage
            </a>

        </div>

        <div class="card">

            <h3>Applications</h3>

            <p>
                View student internship applications.
            </p>

            <a href="applications.php">
                View Applications
            </a>

        </div>

    </div>

</div>

</body>

</html>