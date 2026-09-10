<?php

session_start();

require_once "../db.php";

if (!isset($_SESSION["student_id"])) {
    header("Location: login.php");
    exit();
}

$search = "";

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
}

if ($search != "") {

    $searchTerm = "%" . $search . "%";

    $stmt = $conn->prepare(
        "SELECT *
         FROM internships
         WHERE company_name LIKE ?
         OR internship_title LIKE ?
         OR skills_required LIKE ?
         OR location LIKE ?
         ORDER BY id DESC"
    );

    $stmt->bind_param(
        "ssss",
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm
    );

    $stmt->execute();

    $result = $stmt->get_result();

} else {

    $result = $conn->query(
        "SELECT *
         FROM internships
         ORDER BY id DESC"
    );
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Available Internships</title>

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
            margin: 35px auto;
            padding: 20px;
        }

        .top-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .top-section h1 {
            margin-bottom: 8px;
        }

        .top-section p {
            color: #666;
        }

        .search-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-form input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        .search-form button {
            padding: 12px 22px;
            border: none;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            cursor: pointer;
        }

        .search-form button:hover {
            background: #1d4ed8;
        }

        .clear {
            display: inline-block;
            margin-top: 10px;
            color: #dc2626;
            text-decoration: none;
        }

        .internship-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .internship-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.08);
        }

        .internship-card h2 {
            margin-top: 0;
            color: #111827;
        }

        .company {
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 15px;
        }

        .info {
            margin: 8px 0;
            color: #555;
        }

        .description {
            margin-top: 15px;
            line-height: 1.5;
        }

        .view-btn {
            display: inline-block;
            margin-top: 18px;
            background: #2563eb;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 6px;
        }

        .empty {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
        }

        @media (max-width: 750px) {

            .internship-grid {
                grid-template-columns: 1fr;
            }

            .search-form {
                flex-direction: column;
            }

            .navbar {
                padding: 15px;
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

        <a href="applications.php">
            My Applications
        </a>

        <a href="logout.php">
            Logout
        </a>

    </div>

</div>

<div class="container">

    <div class="top-section">

        <h1>Available Internships</h1>

        <p>
            Find an internship that matches your skills and interests.
        </p>

    </div>

    <div class="search-box">

        <form
            class="search-form"
            method="GET"
        >

            <input
                type="text"
                name="search"
                placeholder="Search by company, internship, skill or location..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button type="submit">
                Search
            </button>

        </form>

        <?php if ($search != ""): ?>

            <a class="clear" href="internships.php">
                Clear Search
            </a>

        <?php endif; ?>

    </div>

    <?php if ($result->num_rows > 0): ?>

        <div class="internship-grid">

            <?php while ($row = $result->fetch_assoc()): ?>

                <div class="internship-card">

                    <h2>
                        <?php
                        echo htmlspecialchars(
                            $row["internship_title"]
                        );
                        ?>
                    </h2>

                    <div class="company">

                        Company:
                        <?php
                        echo htmlspecialchars(
                            $row["company_name"]
                        );
                        ?>

                    </div>

                    <div class="info">

                        📍 Location:
                        <?php
                        echo htmlspecialchars(
                            $row["location"]
                        );
                        ?>

                    </div>

                    <div class="info">

                        ⏱ Duration:
                        <?php
                        echo htmlspecialchars(
                            $row["duration"]
                        );
                        ?>

                    </div>

                    <div class="info">

                        📅 Last Date:
                        <?php
                        echo htmlspecialchars(
                            $row["last_date"]
                        );
                        ?>

                    </div>

                    <div class="info">

                        💡 Skills:
                        <?php
                        echo htmlspecialchars(
                            $row["skills_required"]
                        );
                        ?>

                    </div>

                    <p class="description">

                        <?php

                        $description =
                            $row["description"];

                        if (strlen($description) > 150) {

                            echo htmlspecialchars(
                                substr($description, 0, 150)
                            ) . "...";

                        } else {

                            echo htmlspecialchars(
                                $description
                            );
                        }

                        ?>

                    </p>

                    <a
                        class="view-btn"
                        href="internship_details.php?id=<?php echo $row["id"]; ?>"
                    >
                        View Details
                    </a>

                </div>

            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <div class="empty">

            <h2>No internships found</h2>

            <p>
                <?php if ($search != ""): ?>

                    Try searching for something else.

                <?php else: ?>

                    No internship opportunities are currently available.

                <?php endif; ?>
            </p>

        </div>

    <?php endif; ?>

</div>

</body>

</html>