<?php 
    include("../db/database.php");
    session_start();
    
    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    }else {
        $user_id = '';
        header("location:login.php");
    }
    if(empty($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    }

    include("../components/add_wishlist.php");
    include("../components/add_cart.php");

    $total = 0;

    if(isset($_POST['delete'])) {
        $pid_delete = $_POST['pid_delete'];
        unset($_SESSION['cart'][$pid_delete]);
        $message[] = 'Removed the item from the cart';
    }
    if(isset($_POST['delete_all'])) {
        $_SESSION['cart'] = array();
        $message[] = 'Removed all items from the cart';
    }
    if(isset($_POST['edit_qty'])) {
        $_SESSION['cart'][$_POST['pid_delete']] = $_POST['qty'];
    }
    if(isset($_POST['checkout'])) {
        count($_SESSION['cart']) > 0? header('location: checkout.php') : $message[] = 'Your cart is empty!';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../CSS/customer_style.css">
</head>
<style>
   body{
    background: url('../images/bg2.jpg');
    padding: 1rem 7%;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    }
</style>
<body>
    <?php include("../components/user_header.php"); ?>
    <section class="products">
        <h1 class="title">your cart</h1>

        <div class="box-container">
    <?php 
        if(count($_SESSION["cart"]) > 0) {
            foreach($_SESSION['cart'] as $pid => $qty) {
                $product_query = $conn->prepare("SELECT * FROM products WHERE id = ?");
                $product_query->execute([$pid]);
            
                while($fetch_product = $product_query->fetch(PDO::FETCH_ASSOC)) {
                $sub_total = $fetch_product['price'] * $qty;
                $total += $sub_total;    
        ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="box">
                    <input type="hidden" name="pid_delete" value="<?= $pid; ?>">

                    <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" id="eye-btn" class="fas fa-eye"></a>
                    <button type="submit" class="fas fa-times" name="delete"
                    onclick="return confirm('delete this item?');"></button>
                        
                    <img src="../uploaded images/<?= $fetch_product['image']; ?>" alt="">
                    
                    <div class="name"><?= $fetch_product['name']; ?></div>
                    
                    <div class="flex">
                        <div class="price"><span>Rs </span><?= $fetch_product['price']; ?></div>
                        <input type="number" name="qty" class="qty" min="1" max="99"
                        value="<?= $qty ?>" onkeypress="if(this.value.length == 2) return false;">
                        <button type="submit" name="edit_qty" class="fas fa-edit"></button>
                    </div>
                    <div class="sub-total">sub total : Rs<span><?= $sub_total; ?></span></div>
                </form>
        <?php } 
           } 
        }else {
            echo '<div class="empty">No products added to cart yet!</div>';
        } ?>
        </div>

        <div class="cart-total">
            <p>Cart total: Rs <span><?= $total ?></span></p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <button class="btn" name="checkout">proceed to checkout</button>
            </form>
        </div>
        
        <div class="more-btn">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <button type="submit" class="delete-btn" name="delete_all"
                onclick="return confirm('delete all from cart?');">remove all</button>
            </form>
        </div>
        
    </section>

    <div class="loader">
        <img src="../images/loader.gif" alt="">
    </div>
    
    <script src="../js/script.js"></script>

</body>
</html>
<?php include("../components/footer.php"); ?>