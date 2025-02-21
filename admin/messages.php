<?php

   include('../db/database.php');

   session_start();

   if(isset($_SESSION["admin_id"])) {
      $admin_id = $_SESSION["admin_id"];
   }else {
      $admin_id = '';
      header('location: admin_login.php');
   } 

   if(isset($_GET['delete'])){
      $delete_id = $_GET['delete'];
      $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
      $delete_message->execute([$delete_id]);
      header('location: messages.php');
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

   <?php include '../components/admin_header.php' ?>

   <section class="messages">

      <h1 class="heading">messages</h1>

      <div class="box-container">

      <?php
         $select_messages = $conn->prepare("SELECT * FROM messages");
         $select_messages->execute();
         if($select_messages->rowCount() > 0){
            while($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="box">
         <p> ID : <span><?php if(empty($fetch_messages['user_id'])){echo "Not registered";}else{ echo $fetch_messages['user_id'];} ?></span> </p>
         <p> Name : <span><?= $fetch_messages['name']; ?></span> </p>
         <p> Number : <span><?= $fetch_messages['number']; ?></span> </p>
         <p> Email : <span><?= $fetch_messages['email']; ?></span> </p>
         <p> Date : <span><?= $fetch_messages['date']; ?></span> </p>
         <p> Message : <span><?= $fetch_messages['message']; ?></span> </p>
         <a href="messages.php?delete=<?= $fetch_messages['id']; ?>" class="delete-btn" onclick="return confirm('delete this message?');">delete</a>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">you have no messages</p>';
         }
      ?>

      </div>

   </section>

   <script src="../js/admin_script.js"></script>

</body>
</html>