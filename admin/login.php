<?php

session_start();

require_once "../db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {

        $message = "Please enter email and password.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id, name, email, password
             FROM admins
             WHERE email = ?"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $admin = $result->fetch_assoc();

            if (password_verify($password, $admin["password"])) {

                $_SESSION["admin_id"] = $admin["id"];
                $_SESSION["admin_name"] = $admin["name"];
                $_SESSION["admin_email"] = $admin["email"];

                header("Location: dashboard.php");
                exit();

            } else {

                $message = "Incorrect password.";

            }

        } else {

            $message = "Admin account not found.";

        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Login</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #eef2ff;
        }

        .container {
            width: 400px;
            max-width: 90%;
            margin: 100px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
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

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 22px;
            border: none;
            border-radius: 6px;
            background: #111827;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #374151;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 15px;
        }

        .info {
            margin-top: 20px;
            padding: 12px;
            background: #f3f4f6;
            border-radius: 6px;
            text-align: center;
            font-size: 14px;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Admin Login</h1>

    <p class="subtitle">
        Internship Tracker Administration
    </p>

    <?php if ($message != ""): ?>

        <div class="error">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <label>Email</label>

        <input
            type="email"
            name="email"
            placeholder="Enter admin email"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Enter admin password"
            required
        >

        <button type="submit">
            Admin Login
        </button>

    </form>

    <div class="info">

        Default Admin<br><br>

        Email: <strong>admin@gmail.com</strong><br>

        Password: <strong>admin123</strong>

    </div>

</div>

</body>

</html>