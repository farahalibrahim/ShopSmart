<?php
include_once('connection.inc.php');
include_once('dbh.class.inc.php');
$conn = DatabaseHelper::connect([DBCONNSTRING, DBUSER, DBPASS]); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF_8">
    <meta name="viewport" content="width=device_width, initial_scale=1.0">
    <title>Admin</title>
    <style>
        .hidden {
            display: none;
        }
    </style>
    <!-- for use of AJAX and JQuery for autocomplete  -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php
            // if($_GET[] == 'add_product'){
            //     echo 'document.querySelector(".add_product").classList.remove("hidden");';
            // } else if($_GET[] == 'add_supermarket'){
            //     echo 'document.querySelector(".add_supermarket").classList.remove("hidden");';
            // } else 
            if ($_GET['add_tag'] == 'success') {
                $_GET['add_tag'] == '';
                echo 'document.querySelector(".add_tag.form").classList.remove("hidden");';
                echo 'alert("Tag added successfully");';
            }
            // } else if($_GET[] == 'add_user'){
            //     echo 'document.querySelector(".add_user").classList.remove("hidden");';
            // }
            ?>
        });
    </script>
</head>

<body>
    <button class="add_product btn nav">Add Product</button>
    <button class="add_supermarket btn nav">Add Supermarket</button>
    <button class="add_tag btn nav">Add Tag</button>
    <button class="add_user btn nav">Add User</button>

    <!-- add product from -->
    <form action="addproduct.php" method="post" enctype="multipart/form_data" class="add_product form hidden">

        <!-- product barcode and autocomplete fields -->
        <label for="product_barcode_input">Product Barcode:<input type="text" name="barcode" id="product_barcode_input" maxlength="12" pattern="^\d{12}$" title="Barcode must be exactly 12 digits" required></label>
        <div id="product_barcode_input_autocomplete" style="display: none;">xxx</div><br>

        <!-- product supermarket spinner, filled using php from db-->
        <label for="product_supermarket_spinner">Supermarket:<select name="supermarket" id="product_supermarket_spinner" required>
                <?php
                try {
                    // Fill the supermarket select
                    $sql = "SELECT id, name FROM supermarket";
                    $stmt = DatabaseHelper::runQuery($conn, $sql);
                    $supermarkets = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($supermarkets as $supermarket) :

                ?>
                        <option value="<?php echo $supermarket['id'];

                                        ?>"><?php echo $supermarket['name'];

                                            ?>
                        </option>
                <?php endforeach;
                } catch (PDOException $e) {
                    die("Connection failed: " . $e->getMessage());
                }

                ?>
            </select></label><br>


        <!-- product name and autocomplete fields -->
        <label for="product_name_input">Product name:<input type="text" name="product_name" id="product_name_input" required></label><br>
        <div id="product_name_input_autocomplete" style="display: none;">xxx</div>

        <!-- product manufacturer and autocomplete fields -->
        <label for="product_manufacturer_input">Manufacturer:<input type="text" name="manufacturer" id="product_manufacturer_input" required></label><br>
        <div id="product_manufacturer_input_autocomplete" style="display: none;">xxx</div>


        <!-- product category spinner, filled using js-->
        <label for="product_category_spinner">Category:<select name="category" id="product_category_spinner" required></select></label><br>

        <!-- product image src method -->
        <label for="image_url">Image Source:
            <input type="radio" name="image_src" id="image_url" value="url" required>From URL
            <input type="radio" name="image_src" id="image_file" value="file" required>Upload file</label>
        <div id="image_url_container" class="hidden">
            Image URL:
            <input type="text" id="image_url_input" name="image_url_src" />
        </div>
        <div id="image_file_container" class="hidden">
            Image file:
            <input type="file" id="image_file_input" name="image_file_src" accept="image/*" />
        </div>
        <br>
        <!-- is product measured by weight or by pcs  -->
        <label for="measure_type">How is the product measured?
            <input type="radio" name="quantity_type" id="weight" value="weight" required>by weight
            <input type="radio" name="quantity_type" id="piece" value="piece" required>by piece
        </label>
        <div id="quantity_info" class="hidden">
            <label for="product_quantity" id="weight_piece_label">Quantity:</label>
            <input type="number" name="quantity" id="product_quantity" min="1" max="2147483647" required><br>
        </div>

        <!-- price  -->
        <br><label for="unit_price">Unit Price:</label>
        <input type="number" step=".01" name="price" id="unit_price" min="0" max="99999999.99" required><br>

        <!-- product tag, filled from db using php -->
        <label for="product_select_tag"> Product tag:
            <select id="product_select_tag" name="tag" required>
                <?php
                try {
                    $sql = "SELECT * FROM `product_tags`;";
                    $stmt = DatabaseHelper::runQuery($conn, $sql);
                    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($tags as $tag) : ?><option value="<?php echo $tag['tag']; ?>"><?php echo $tag['tag'] . " - " . $tag['tag_title']; ?>
                        </option>
                <?php endforeach;
                } catch (PDOException $e) {
                    die("Connection failed: " . $e->getMessage());
                }
                ?>
            </select>
        </label><br>

        <button class="add_product btn" name="add_product" type="submit">Add</button>

    </form>

    <!-- add supermarket from -->
    <form action="addsupermarket.php" method="get" class="add_supermarket form hidden">
        <button class="add_supermarket btn" name="add_supermarket" type="submit">Add</button>
    </form>

    <!-- add tag from -->
    <form action="addtag.php" method="get" class="add_tag form hidden">

        <!-- product tag, filled from db using php -->
        <label for="product_select_tag"> Before adding, check available tags:
            <select id="product_select_tag" name="tag" required>
                <!-- product tag spinner in add form also have same id, check if okay in different forms -->
                <?php
                try {
                    $sql = "SELECT * FROM `product_tags`;";
                    $stmt = DatabaseHelper::runQuery($conn, $sql);
                    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($tags as $tag) : ?><option value="<?php echo $tag['tag']; ?>"><?php echo $tag['tag'] . " - " . $tag['tag_title']; ?>
                        </option>
                <?php endforeach;
                } catch (PDOException $e) {
                    die("Connection failed: " . $e->getMessage());
                }
                ?>
            </select>
        </label><br>
        <label for="add_tag_input">Tag:<input type="text" name="tag" id="add_tag_input" maxlength="5" placeholder="xxxxx" pattern="^\d{5}$" title="must be 5 digits or less" required></label><br>
        <label for="add_tag_name_input">Tag name:<input type="text" name="tag_name" id="add_tag_name_input"></label><br>
        <button class="add_tag btn" name="add_tag" type="submit">Add</button>

    </form>
    <!-- add user from, limited to admin, packing, delivery. consumers are added by signup -->
    <form action="adduser.php" method="get" class="add_user form hidden">
        <button class="add_user btn" name="add_user" type="submit">Add</button>
    </form>
    <!-- JS script that need DOMContentLoaded -->
    <script src="../JS/autocomplete.js"></script>
    <script src="../JS/admin.js"></script>
</body>

</html>