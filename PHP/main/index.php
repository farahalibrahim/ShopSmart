<?php
include_once('../connection.inc.php');
include_once('../dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]);
session_start();

// for testing, delete cookies
// setcookie('user_id', "", time() - 3600, '/');
// setcookie('user_name', "", time() - 3600, '/');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Smart - Main Page</title>
    <!-- body css -->
    <link rel="stylesheet" href="../../CSS/index.css">
    <!-- header and footer's css -->
    <link rel="stylesheet" href="../../CSS/header_footer.css">
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!--link to box icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!--link to google symbols-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" /> <!-- <link rel="stylesheet" href="../CSS/popular_products.css"> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        #home {
            padding-top: 100px;
        }

        .popular-container,
        .recent-container,
        .recommend-container {
            display: flex;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            /* Adjust as needed */
            grid-gap: 10px;
            margin-top: 2rem;
            background: #ffffffff;
            overflow-x: scroll;
            white-space: nowrap;
            height: 250px;
        }

        .profile button span {
            color: green;
        }


        .productcard {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 10px;
            width: 200px;
            display: inline-block;
        }

        .productcard:hover {
            transform: scale(1.05);
        }

        .pro_cont {}

        .productcard h3,
        .productcard .product_name {
            padding-left: 10px;
            color: black;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            overflow-wrap: break-word;
            font-size: 16px;
            width: 100%;
            align-items: center;
            display: flex;
            flex: 1;
            position: relative;
        }
        

        .productcard img {
            width: 100%;
            height: 100px;
            object-fit: contain;
            object-position: center;
            border-radius: 5px;
            /* Add rounded corners */
            margin-bottom: 10px;
        }

        .productcard:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        /* .productcard .product_details {
            font-size: 1rem;
           display: flex;
            
        } */


        
        

        .productcard .product_details .product_price {

            margin-left: 5px;
            padding-right: 200px;
            margin-top: 10px;
            font-size: 14px;
            color: black;
            padding-left: 20px;
            width: 100px;
            display: inline-block
        }

        .productcard a {
            margin-left: 150px;
            padding-bottom: 20px;
            border-radius: 10px;

        }

        .productcard a span {
            color: #eee;
            border: 1px solid green;
            padding: 5px;
            border-radius: 20px;
            background: green;
        }

        .productcard a span:hover {
            color: green;
            border: 1px solid green;
            background: #eee;
        }


        a.fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 56px;
            height: 56px;
            background-color: #fff314;
            color: white;
            border-radius: 50%;
            text-decoration: none;
            font-size: 24px;
            box-shadow: 0 8px 20px 0 rgba(0, 0, 0, 0.40);
            z-index: 900;
        }

        .chat-popup {
            position: fixed;
            bottom: 85px;
            right: 20px;
            width: 300px;
            height: 400px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px 0 rgba(0, 0, 0, 0.20);
            padding: 20px;
            padding-bottom: 5px;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            max-height: 600px;
            overflow-y: auto;
        }


        #chat-content {
            flex-grow: 1;
            height: 75%;
            max-height: 80%;
            overflow-y: auto;
            margin-bottom: 10px;
        }

        #chat_footer {
            display: flex;
            max-height: 10%;
            justify-content: space-between;
        }

        #chat_header {
            display: flex;
            max-height: 10%;
            padding-bottom: 5px;
            justify-content: flex-start;
        }

        #chat-input {
            flex-grow: 1;
            margin-right: 10px;
        }

        #send-button {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #back_arrow {
            display: none;
        }
    </style>
    <!-- header -->
    <?php include_once '../header.php'; ?>
</head>

<body>


    <?php
    $chat_header = isset($_SESSION['ticket_id']) ? "Ticket# " . $_SESSION['ticket_id'] : "Live Chat";
    if (isset($_COOKIE['user_id'])) :
        $user = $_COOKIE['user_id'];
        $sql = 'SELECT account_status FROM user WHERE id =  "' . $user . '"';
        $stmt = DatabaseHelper::runQuery($conn, $sql);
        $freeze = $stmt->fetchColumn();
        if ($freeze != "freeze") :
    ?>
            <a href="#" class="fab" id="fab"><span class="material-symbols-outlined" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);">support_agent</span></a>
            <div id="chat-popup" class="chat-popup" style="display: none;">
                <h2 id="chat_header"><?= $chat_header ?></h2>
                <div id="chat-content"></div>
                <div id="chat_footer">
                    <input type="text" id="chat-input" placeholder="Type your message here..." oninput="validateInput()">
                    <button id="send-button" disabled>Send</button>
                </div>
            </div>
    <?php endif;
    endif; ?>
    <script>
        // prevent sending empty messages
        function validateInput() {
            var input = document.getElementById('chat-input');
            var button = document.getElementById('send-button');
            if (input.value.trim() === '') {
                button.disabled = true;
                return;
            }
        }

        $(document).ready(function() {
            // Load chat messages
            function loadMessages() {
                $.ajax({
                    url: 'live_chat/load_messages.php',
                    success: function(data) {
                        $('#chat-content').html(data);
                    }
                });
            }
            $('#fab').click(function() {
                loadMessages();
            });

            // Send a chat message
            $('#send-button').click(function() {
                var message = $('#chat-input').val();
                $.ajax({
                    url: 'live_chat/check_ticket.php',
                    method: 'POST',
                    data: {
                        message: message
                    }, // Send the message along with the request
                    success: function(data) {
                        if (data === 'ticket_created') {
                            // If a new ticket was created, reload the page to update the session
                            location.reload();
                        } else {
                            // If a ticket already exists, send the message as usual
                            $.ajax({
                                url: 'live_chat/send_message.php',
                                method: 'POST',
                                data: {
                                    message: message
                                },
                                success: function(data) {
                                    $('#chat-input').val('');
                                    loadMessages();
                                }
                            });
                        }
                    }
                });
            });

            // Load messages every second to update the chat in real-time
            setInterval(function() {
                $.ajax({
                    url: 'live_chat/check_ticket.php',
                    method: 'POST',
                    success: function(data) {
                        if (data !== 'ticket_created') {
                            loadMessages();
                        }
                    }
                });
            }, 10000);
            // Check if the ticket is closed(by admin) every 5 seconds
            setInterval(function() {
                $.ajax({
                    url: 'live_chat/check_ticket_status.php',
                    method: 'POST',
                    success: function(data) {
                        if (data === 'closed') {
                            location.reload();
                        }
                    }
                });
            }, 5000); // Check every 5 seconds
        });
    </script>
    <!--Home Page-->
    <section class="home" id="home">
        <div class="swiper-wrapper">
            <!--Slide 1-->
            <div class="swiper-slide container ">
                <div class="home-text">
                    <span>Shop Smart</span>
                    <h1>Choose Your <br>best Offer <br>NOW !!!</h1>
                    <a href="offers.php" class="btn">Shop Now <span class="material-symbols-outlined" id="R-arrow">
                            arrow_forward
                        </span></a>
                </div>
                <img src="../../pics/Best Offer 3D Text.G03.watermarked.2k.png" alt="">
            </div>

            <!--Slide 2-->
            <div class="swiper-slide container">
                <div class="home-text">
                    <span>Shop Smart</span>
                    <h1>Compare between <br>Many Offers <br> <span class="material-symbols-outlined">
                            compare_arrows
                        </span></h1>
                    <!-- <a href="#" class="btn">Shop Now <span class="material-symbols-outlined" id="R-arrow">
                                    compare_arrows
                                </span></a> -->
                    <p>Find you favorite products to start!</p>
                </div>
                <img src="../../pics/Compare Symbol.G03.watermarked.2k.png" alt="">
            </div>
            <!--Slide 3-->
            <div class="swiper-slide container">
                <div class="home-text">
                    <span>Shop Smart</span>
                    <h1>Track the price of your<br>Favorite Product! <br></h1>
                    <a href="http://localhost:3000/PHP/shopping_list/shoppinglist.php" class="btn">Shop Now <span class="material-symbols-outlined" id="R-arrow">
                            arrow_forward
                        </span></a>
                </div>
                <img src="../../pics/Symbol Graph Red Green.G03.watermarked.2k.png" alt="">
            </div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        </div>
    </section>

    <!--Categories Section-->
    <section class="categories" id="categories">
        <div class="heading">
            <h1>Browse Our <br><span>Categories</span></h1>
            <!-- <a href="#" class="btn">See All <span class="material-symbols-outlined" id="R-arrow">
                    arrow_forward
                </span></a> -->
        </div>
        <!--Categories Container-->
        <div class="categories-container">
            <?php
            $sql = "SELECT DISTINCT category FROM product";
            $stmt = DatabaseHelper::runQuery($conn, $sql);
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($categories as $category) {
                $categoryName = $category['category'];

                $sql = "SELECT COUNT(*) FROM product WHERE category = :category";
                $category_stmt = DatabaseHelper::runQuery($conn, $sql, array(":category" => $categoryName));
                $result = $category_stmt->fetch(PDO::FETCH_ASSOC);
                $count = $result['COUNT(*)'];
            ?>
                <div class="box box1">
                    <img src="../../pics/<?php echo $categoryName; ?>.png" alt="">
                    <h2><?php echo $categoryName; ?></h2>
                    <span><?php echo $count; ?> Items</span>
                    <a href="../products/products.php?category=<?php echo str_replace(' & ', '_', $categoryName); ?>"><span class="material-symbols-outlined" id="catarrow">
                            arrow_forward
                        </span></a>
                </div>
            <?php
            }
            ?>
        </div>

    </section>

    <!--Products Section-->

    <section class="products" id="products">
        <div class="heading">
            <h1>Our Popular <br><span>Products</span></h1>
            <a href="popular_products.php" class="btn">See All <span class="material-symbols-outlined" id="R-arrow">
                    arrow_forward
                </span></a>
        </div>
        <!--Products Container-->
        <div class="popular-container">
            <?php include_once("get_popular_products.php");
            echo get_popular_products($conn, 8);
            ?>
        </div>
    </section>

    <?php
    // sections are only displayed if user logged in and has at least one order already
    // Check if the user is logged in
    if (isset($_COOKIE['user_id'])) :
        // Query to count the number of orders for the current user
        $sql = "SELECT COUNT(*) as order_count FROM `order` WHERE user_id = :user_id";
        $stmt = DatabaseHelper::runQuery($conn, $sql, array(":user_id" => $_COOKIE['user_id']));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user has at least one order
        if ($result['order_count'] > 0) : ?> <!-- Displayed only if user logged in -->
            <!--Buy Again-->
            <section class="products" id="products">
                <div class="heading">
                    <h1>Buy <br><span>Again</span></h1>
                    <!-- <a href="../PHP/popular_products.php" class="btn">See All <span class="material-symbols-outlined" id="R-arrow">
                    arrow_forward
                </span></a> -->
                </div>
                <!--Products Container-->
                <div class="recent-container">
                    <?php include_once("buy_again.php");
                    echo get_recent_products($conn, 8, $_COOKIE['user_id']);
                    ?>
                </div>
            </section>

            <!--You May Also Like-->
            <section class="products" id="products">
                <div class="heading">
                    <h1>You May Also <br><span>Like</span></h1>
                    <!-- <a href="../PHP/popular_products.php" class="btn">See All <span class="material-symbols-outlined" id="R-arrow">
                    arrow_forward
                </span></a> -->
                </div>
                <!--Products Container-->
                <div class="recommend-container">
                    <?php include_once("recommended_products.php");
                    echo $products;
                    ?>
                </div>
            </section>
    <?php endif;
    endif; ?>

    <!--Footer Section-->
    <?php include_once '../footer.php'; ?>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!--link to js-->
    <script src="../../JS/index.js"></script>
    <script>
        $(document).ready(function() {
            $('#fab').on('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                var chatPopup = $('#chat-popup');
                chatPopup.css('display', chatPopup.css('display') === 'none' ? 'block' : 'none');
            });

            $(window).on('click', function(event) {
                var chatPopup = $('#chat-popup');
                var fab = $('#fab');
                if (!$(event.target).closest(chatPopup).length && !$(event.target).closest(fab).length) {
                    chatPopup.css('display', 'none');
                }
            });
        });
    </script>
</body>

</html>