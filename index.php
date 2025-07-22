<?php
include "calendar.php"
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <meta name="description" content="Calendar Project">
    <link rel="stylesheet" href="style/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>

    <header>
        <h1>Calendar</h1>
    </header>

    <!-- Success / Error message -->
    <?php if ($successMsg): ?>
        <div class="alert success"><?= $successMsg ?></div>
    <?php elseif ($errorMsg): ?>
        <div class="alert error"><?= $errorMsg ?></div>
    <?php endif; ?>

    <!-- Clock -->
    <div class="clock-container">
        <div id="clock"></div>
    </div>

    <!-- Calendar -->
    <div class="calendar">
        <div class="nav-btn-container">
            <button class="nav-btn" onclick="changeMonth(-1)"> Prev </button>
            <h2 id="monthYear" style="margin: 0"></h2>
            <button class="nav-btn" onclick="changeMonth(1)"> Next </button>
        </div>

        <div class="calendar-grid" id="calendar"></div>
    </div>

    <!-- Modal for Add / Edit / Delete -->
    <div class="modal" id="eventModal">
        <div class="modal-content">

            <div id="eventSelectorWrapper">
                <label for="eventSelector"><strong>Select Event:</strong></label>
                <select name="" id="eventSelector" onchange="">
                    <option disabled selected value="">Choose Event...</option>
                </select>
            </div>

            <!-- Main Form -->
            <form method="POST" id="eventForm">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="event_id" id="eventId">

                <label for="title">Title:</label>
                <input type="text" name="title" id="title" require>

                <label for="description">Description:</label>
                <input type="text" name="description" id="description" require>

                <label for="startDate">Start Date:</label>
                <input type="date" name="start_date" id="startDate" require>

                <label for="endDate">End Date:</label>
                <input type="date" name="end_date" id="endDate" require>

                <label for="startTime">Start Time:</label>
                <input type="time" name="start_time" id="startTime" require>

                <label for="endTime">End Time:</label>
                <input type="time" name="end_time" id="endTime" require>


                <button type="submit">Save</button>


            </form>

            <!-- Delete Form -->
            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="event_id" id="deleteEventId">
                <button type="submit">Delete</button>
            </form>

            <!-- Cancel -->
            <button type="button" class="submit-btn" onclick="closeModal()">Cancel</button>
        </div>
    </div>

    <!-- Calendar logic -->
    <script>
        const events = <?= json_encode($eventsFromDB, JSON_UNESCAPED_UNICODE); ?>
    </script>

    <script src="script/calendar.js"></script>



</body>

</html>