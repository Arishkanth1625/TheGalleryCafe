<?php 
    include("../db/database.php");
    session_start();

    if(isset($_SESSION["id"])) {
        $user_id = $_SESSION["id"];
    }else {
        $user_id = '';
    }
    if(empty($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    }
    
    include("../components/add_wishlist.php");
    include("../components/add_cart.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="../CSS/customer_style.css">
</head>
<style>
   body{
        background: url('../images/homepage\ \(2\).jpg');
        padding: 1rem 7%;
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
    }

    .intro{
        padding: 7vw 7vw;
        background: url('../images/bg5.jpg');
        border-bottom: var(--border);
        height: 80vh;
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        margin-top: 10px;
    }
</style>
<body>
    
    <?php include("../components/user_header.php"); ?>

    <div class="intro">
        <div class="main_slide">
            <div class="text">
                <h1>Indulge in the Taste of <span>Exquisite Delicacies</span></h1>
                <p>Welcome to a Gallery Cafe. At our establishment, 
                    we take pride in crafting moments of joy through 
                    delicious food that tantalizes the taste buds and 
                    warms the soul. Immerse yourself in a world of 
                    flavors and let every bite tell a story of passion 
                    and perfection.<a href="../customer/about.php"> More...</a></p>
            </div>
            <div class="home_image">
            <img src="../images/background content.png" alt="">
            </div>
            
        </div>
    </div>
    <section class="hero">
        <div class="hero-slider">
            <div class="swiper-wrapper">
            <?php 
                $select_hero_products = $conn->prepare("SELECT * FROM products WHERE hero_slider = ?");
                $select_hero_products->execute([1]);
                if($select_hero_products->rowCount() > 0) {

                    while($fetch_hero_products = $select_hero_products->fetch(PDO::FETCH_ASSOC)) {
            ?>
                        <div class="swiper-slide">
                            <div class="content">
                                <span>Special Offer</span>
                                <h3><?= $fetch_hero_products['name'] ?></h3>
                                <a href="menu.php" class="btn">see menu</a>
                            </div>
                            <div class="image">
                                <img src="../uploaded images/<?= $fetch_hero_products['image'] ?>" alt="">
                            </div>
                        </div>
            <?php
                    }
                }
            ?>

            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- Special Events and Promotions Section -->
    <section class="events-promotions">
        <h1 class="title">Special Events & Promotions</h1>
        <div class="box-container">
            <?php 
                $select_events = $conn->prepare("SELECT * FROM events_promotions ORDER BY date DESC");
                $select_events->execute();
                if($select_events->rowCount() > 0) {
                    while($fetch_events = $select_events->fetch(PDO::FETCH_ASSOC)) {
            ?>
                        <div class="box">
                            <img src="../uploaded images/<?= $fetch_events['image'] ?>" alt="">
                            <div class="content">
                                <h3><?= $fetch_events['title'] ?></h3>
                                <p><?= $fetch_events['description'] ?></p>
                                <span><?= date("F d, Y", strtotime($fetch_events['date'])) ?></span>
                            </div>
                        </div>
            <?php
                    }
                } else {
                    echo '<div class="empty">No events or promotions available!</div>';
                }
            ?>
        </div>
    </section>

    <section class="category">
        <h1 class="title">food category</h1>
        <div class="box-container">

            <a href="category.php?category=fast food" class="box">
                <img src="../images/cat-1.png" alt="">
                <h3>fast food</h3>
            </a>

            <a href="category.php?category=main dishes" class="box">
                <img src="../images/cat-2.png" alt="">
                <h3>main dishes</h3>
            </a>

            <a href="category.php?category=drinks" class="box">
                <img src="../images/cat-3.png" alt="">
                <h3>drinks</h3>
            </a>

            <a href="category.php?category=desserts" class="box">
                <img src="../images/cat-4.png" alt="">
                <h3>desserts</h3>
            </a>

        </div>
    </section>


    <section class="products">
        <h1 class="title">latest food</h1>

        <div class="box-container">
            <?php 
                $select_products = $conn->prepare("SELECT * FROM products ORDER BY id DESC LIMIT 6");
                $select_products->execute();
                if($select_products->rowCount() > 0) {
                    while($fetch_products = $select_products->fetch(pdo::FETCH_ASSOC)) {
            ?>
                    <form action="" method="POST" class="box">
                    <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                    <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                    <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                    <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">

                    <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" id="eye-btn" class="fas fa-eye"></a>
                <?php 
                    $is_wishlist_item = $conn->prepare('SELECT * FROM wishlist WHERE user_id = ? AND food_id = ?');
                    $is_wishlist_item->execute([$user_id, $fetch_products['id']]);
                    $is_wishlist_item->rowCount() > 0 ? $red = 'red' : $red = ''; 
                ?>
                    <button type="submit" name="add_to_wishlist" id="heart-btn" class="fas fa-heart" style="color: <?= $red ?>;"></button>
                    <button type="submit" name="add_to_cart" id="cart-btn" class="fas fa-shopping-cart"></button>

                    <img src="../uploaded images/<?= $fetch_products['image']; ?>" alt="">

                    <div class="cuisine-category">
                        <a href="category.php?category=<?= $fetch_products['category']; ?>" class="cat"
                        ><?= $fetch_products['category']; ?></a>
                        <a href="cuisine.php?cuisine=<?= $fetch_products['cuisine']; ?>" class="cuisine"
                        ><?= $fetch_products['cuisine']; ?></a>
                    </div>

                    <div class="name"><?= $fetch_products['name']; ?></div>
                    
                    <div class="flex">
                        <div class="price"><span>Rs </span><?= $fetch_products['price']; ?></div>
                        <input type="number" name="qty" class="qty" min="1" max="99"
                        value="1" maxlength="2" onkeypress="if(this.value.length == 2) return false;">
                    </div>
                    </form>
            <?php   
                }
                }else {
                    echo '<div class="empty">no products added yet!</div>';        
                }
            ?>

        </div>
        <div class="more-btn">
            <a href="menu.php" class="btn">view all</a>
        </div>
    </section>


    <div class="loader">
        <img src="../images/loader.gif" alt="">
    </div>
    
    <script src="../js/script.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>

        var swiper = new Swiper(".hero-slider", {
            loop:true,
            grabCursor:true,
            effect:"flip",
            pagination: {
                el: ".swiper-pagination",
                clickable:true,
            },
        });
        
    </script>

</body>
</html>
<?php include("../components/footer.php"); ?>