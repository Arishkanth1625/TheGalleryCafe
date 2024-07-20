<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Cafe Parking System</title>
    <link rel="stylesheet" href="../CSS/parkin.css">
</head>
<body>
    <div class="container">
        <h1>Gallery Cafe Parking System</h1>
        <div class="parking-floors">
            <h2>Floor 1</h2>
            <div class="parking-grid" id="floor1">
            </div>
            <h2>Floor 2</h2>
            <div class="parking-grid" id="floor2">
            </div>
        </div>
        <div class="booking-form">
            <h2>Book Your Spot</h2>
            <form id="bookingForm">
                <label for="floorNumber">Floor Number</label>
                <input type="text" id="floorNumber" name="floorNumber" readonly>
                
                <label for="spotNumber">Spot Number</label>
                <input type="text" id="spotNumber" name="spotNumber" readonly>
                
                <label for="userName">Name</label>
                <input type="text" id="userName" name="userName" required>
                
                <button type="submit">Book Spot</button>
            </form>
        </div>
        <div class="confirmation" id="confirmation">
            <p id="confirmationMessage"></p>
        </div>
    </div>
    <script src="../js/parking.js"></script>
</body>
</html>
