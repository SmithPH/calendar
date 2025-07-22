<?php

include "connection.php";

$successMsg = "";
$errorMsg = "";
$eventsFromDB = []; // Initialise a new array to store the fetched events

//  Handle Add events
if ($_SERVER["REQUEST_METHOD"] == "POST" && ($_POST["action"] ?? "") == "add") {
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $startDate = $_POST["start_date"] ?? "";
    $endDate = $_POST["end_date"] ?? "";
    $startTime = $_POST["start_time"] ?? "";
    $endTime = $_POST["end_time"] ?? "";


    if ($title && $description && $startDate && $endDate && $startTime && $endTime) {

        $stmt = $conn->prepare(
            "INSERT INTO events (title, description, start_date, end_date, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssssss", $title, $description, $startDate, $endDate, $startTime, $endTime);
        $stmt->execute();
        $stmt->close();

        header("Location: " . $_SERVER["PHP_SELF"] . "?success=1");
        exit;
    } else {
        header("Location: " . $_SERVER["PHP_SELF"] . "?error=1");
        exit;
    }
}


// Handle Edit events
if ($_SERVER["REQUEST_METHOD"] == "POST" && ($_POST["action"] ?? "") == "edit") {
    $id = $_POST["event_id"] ?? null;
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $startDate = $_POST["start_date"] ?? "";
    $endDate = $_POST["end_date"] ?? "";
    $startTime = $_POST["start_time"] ?? "";
    $endTime = $_POST["end_time"] ?? "";

    if ($id && $title && $description && $startDate && $endDate && $startTime && $endTime) {

        $stmt = $conn->prepare(
            "UPDATE events SET title = ?, description = ?, start_date = ?, end_date = ?, start_time = ?, end_time = ? WHERE id = ?"
        );
        $stmt->bind_param("ssssssi", $title, $description, $startDate, $endDate, $startTime, $endTime, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: " . $_SERVER["PHP_SELF"] . "?success=2");
        exit;
    } else {
        header("Location: " . $_SERVER["PHP_SELF"] . "?error=2");
        exit;
    }
}


// Handle Delete events
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "delete") {
    $id = $_POST["event_id"] ?? null;

    if ($id) {
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        header("Location: " . $_SERVER["PHP_SELF"] . "?success=3");
        exit;
    } else {
        header("Location: " . $_SERVER["PHP_SELF"] . "?error=3");
        exit;
    }
}


//  Success and Error Messages
if (isset($_GET["success"])) {
    $successMsg = match ($_GET["success"]) {
        '1' => "Event Added Successfully",
        '2' => "Event Edited Successfully",
        '3' => "Event Deleted Successfully",
        default => ""
    };
}

if (isset($_GET["error"])) {
    $errorMsg = "Error occurred. Please check your input";
}

// Fetch All Events and Spread over date range
$result = $conn->query("SELECT * FROM events");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $startDate = new DateTime($row["start_date"]);
        $endDate = new DateTime($row["end_date"]);

        while ($startDate <= $endDate) {
            $eventsFromDB[] = [
                "id" => $row["id"],
                "title" => $row["title"],
                "description" => $row["description"],
                "date" => $startDate->format('Y-m-d'),
                "start_date" => $row["start_date"],
                "end_date" => $row["end_date"],
                "start_time" => $row["start_time"],
                "end_time" => $row["end_time"],
            ];

            $startDate->modify('+1 day');
        }
    }
}

$conn->close();
