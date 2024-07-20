<?php 
include("../db/database.php");
session_start();

if(isset($_SESSION["admin_id"])) {
    $admin_id = $_SESSION["admin_id"];
}else {
    $admin_id = '';
    header('location: admin_login.php');
}

// Add Event/Promotion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_event'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded images/' . $image;

    if (move_uploaded_file($image_tmp_name, $image_folder)) {
        $add_event = $conn->prepare("INSERT INTO events_promotions (title, description, image, date) VALUES (?, ?, ?, ?)");
        $add_event->execute([$title, $description, $image, $date]);
        $message = 'Event/Promotion added successfully';
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        $message = 'Failed to upload image';
    }
}

// Update Event/Promotion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_event'])) {
    $id = $_POST['event_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    
    $update_event = $conn->prepare("UPDATE events_promotions SET title = ?, description = ?, date = ? WHERE id = ?");
    $update_event->execute([$title, $description, $date, $id]);
    
    if(!empty($_FILES['image']['name'])) {
        // Handle image update
        $image = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded images/' . $image;
        
        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            $update_image = $conn->prepare("UPDATE events_promotions SET image = ? WHERE id = ?");
            $update_image->execute([$image, $id]);
        }
    }

    $message = 'Event/Promotion updated successfully';
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

// Delete Event/Promotion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_event'])) {
    $id = $_POST['event_id'];
    $delete_event = $conn->prepare("DELETE FROM events_promotions WHERE id = ?");
    $delete_event->execute([$id]);
    $message = 'Event/Promotion deleted successfully';
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event/Promotion</title>
    <link rel="stylesheet" href="../CSS/admin_style.css">
</head>
<body>

<?php include("../components/admin_header.php"); ?>

<div class="form-container">
    <form action="" method="POST" enctype="multipart/form-data">
        <h3>Add New Event/Promotion</h3>
        <?php
        if (isset($message)) {
            echo '<div class="message">' . $message . '</div>';
        }
        ?>
        <input type="text" name="title" placeholder="Title" class="box" required>
        <textarea name="description" placeholder="Description" class="box" required></textarea>
        <input type="date" name="date" class="box" required>
        <input type="file" name="image" class="box" accept="image/*" required>
        <input type="submit" name="add_event" value="Add Event/Promotion" class="btn">
    </form>
</div>

<div class="events-container">
    <h3>All Events/Promotions</h3>
    <table class="events-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Date</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $select_events = $conn->prepare("SELECT * FROM events_promotions ORDER BY date DESC");
            $select_events->execute();
            if($select_events->rowCount() > 0) {
                while($fetch_events = $select_events->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <tr>
                        <td><?= $fetch_events['title'] ?></td>
                        <td><?= $fetch_events['description'] ?></td>
                        <td><?= date("F d, Y", strtotime($fetch_events['date'])) ?></td>
                        <td><img src="../uploaded images/<?= $fetch_events['image'] ?>" alt="<?= $fetch_events['title'] ?>" style="max-width: 100px;"></td>
                        <td>
                            <form action="" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="event_id" value="<?= $fetch_events['id']; ?>">
                                <input type="text" name="title" value="<?= $fetch_events['title']; ?>" class="box" required>
                                <textarea name="description" class="box" required><?= $fetch_events['description']; ?></textarea>
                                <input type="date" name="date" value="<?= $fetch_events['date']; ?>" class="box" required>
                                <input type="file" name="image" class="box" accept="image/*">
                                <input type="submit" name="update_event" value="Update" class="btn">
                                <input type="submit" name="delete_event" value="Delete" class="btn" onclick="return confirm('Are you sure you want to delete this event?');">
                            </form>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo '<tr><td colspan="5">No events or promotions available!</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
