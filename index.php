<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Student Internship Tracker</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f7fb;
    color: #111827;
}

.navbar {
    background: #2563eb;
    color: white;
    padding: 18px 50px;

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
    margin-left: 20px;
}

.hero {
    text-align: center;
    padding: 90px 20px;
    background: white;
}

.hero h1 {
    font-size: 42px;
    margin-bottom: 15px;
}

.hero p {
    font-size: 18px;
    color: #666;
    max-width: 700px;
    margin: auto;
    line-height: 1.6;
}

.buttons {
    margin-top: 30px;
}

.button {
    display: inline-block;
    padding: 13px 25px;
    margin: 5px;

    border-radius: 7px;

    text-decoration: none;
    font-weight: bold;
}

.student {
    background: #2563eb;
    color: white;
}

.admin {
    background: #111827;
    color: white;
}

.features {
    max-width: 1000px;
    margin: 50px auto;
    padding: 20px;

    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.feature {
    background: white;
    padding: 25px;
    text-align: center;

    border-radius: 10px;

    box-shadow:
        0 3px 12px rgba(0,0,0,0.08);
}

.feature h3 {
    color: #2563eb;
}

footer {
    text-align: center;
    padding: 25px;
    color: #666;
}

@media (max-width: 700px) {

    .features {
        grid-template-columns: 1fr;
    }

    .hero h1 {
        font-size: 30px;
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

<a href="student/login.php">
Student Login
</a>

<a href="student/register.php">
Register
</a>

<a href="admin/login.php">
Admin
</a>

</div>

</div>

<section class="hero">

<h1>
Student Internship Opportunity Tracker
</h1>

<p>

Find internship opportunities, apply online,
and track your application status from one simple platform.

</p>

<div class="buttons">

<a
    class="button student"
    href="student/login.php"
>
Student Login
</a>

<a
    class="button admin"
    href="admin/login.php"
>
Admin Login
</a>

</div>

</section>

<div class="features">

<div class="feature">

<h3>
Find Internships
</h3>

<p>
Search internship opportunities by company,
skills and location.
</p>

</div>

<div class="feature">

<h3>
Apply Online
</h3>

<p>
Students can apply for suitable internships
directly through the system.
</p>

</div>

<div class="feature">

<h3>
Track Applications
</h3>

<p>
Students can track whether their application
is Applied, Shortlisted, Selected or Rejected.
</p>

</div>

</div>

<footer>

<p>
Student Internship Opportunity & Application Tracking System
</p>

</footer>

</body>

</html>