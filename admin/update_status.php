<?php

session_start();
require_once "../db.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_POST["application_id"]) &&
    isset($_POST["status"])
) {

    $application_id = intval(
        $_POST["application_id"]
    );

    $status = $_POST["status"];

    $allowed_statuses = [
        "Applied",
        "Shortlisted",
        "Selected",
        "Rejected"
    ];

    if (in_array($status, $allowed_statuses)) {

        $stmt = $conn->prepare(
            "UPDATE applications
             SET status = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "si",
            $status,
            $application_id
        );

        $stmt->execute();

        $stmt->close();
    }
}

header("Location: applications.php");
exit();

?>