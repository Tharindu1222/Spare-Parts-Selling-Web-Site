<?php
    session_start();
    $db_name = "light";
    $connection = mysqli_connect("localhost","root","","light");

    if(isset($_POST["add"])){
        if(isset($_SESSION["shopping_cart"])){
            $item_array_id = array_column($_SESSION["shopping_cart"],"product_id");
            if(!in_array($_GET["id"],$item_array_id)){
                $count = count($_SESSION["shopping_cart"]);
                $item_array = array(
                    'product_id' => $_GET["id"],
                    'product_name' => $_POST["hidden_name"],
                    'product_price' => $_POST["hidden_price"],
                    'product_quantity' => $_POST["quantity"],
                );
                $_SESSION["shopping_cart"][$count] = $item_array;
                echo '<script>window.location="light.php"</script>';
            }else{
                echo '<script>alert("Product is already in  the cart")</script>';
                echo '<script>window.location="light.php"</script>';
            }
        }else{
            $item_array = array(
                'product_id' => $_GET["id"],
                'product_name' => $_POST["hidden_name"],
                'product_price' => $_POST["hidden_price"],
                'product_quantity' => $_POST["quantity"],
            );
            $_SESSION["shopping_cart"][0] = $item_array;
        }
    }

    if(isset($_GET["action"])){
        if($_GET["action"] == "delete"){
            foreach($_SESSION["shopping_cart"] as $keys => $value){
                if($value["product_id"] == $_GET["id"]){
                    unset($_SESSION["shopping_cart"][$keys]);
                    echo '<script>alert("Product has been removed")</script>';
                    echo '<script>window.location="light.php"</script>';
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>

<title>Spare Parts Online</title>
    <!-- link to css -->
    <link rel="stylesheet" href="style.css">
    <!-- box icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <style>
        .product{
            border: 1px solid #eaeaec;
            margin: 2px 2px 20px 2px;
            padding: 10px;
            text-align: center;
            background-color: #ffffff;
        }
        table,th,tr{
              text-align: center;
        }
        .title2{
            text-align: center;
            color: #66afe9;
            background-color: #efefef;
            padding: 2%;
        }
        h2{
            text-align: center;
            color: #66afe9;
            background-color: #efefef;
            padding: 2%;
        }
        table th{
            background-color: #efefef;
        }
    </style>
</head>
<body>

                <!-- nav container -->
        <div class="nav container">
            <!-- menu icon -->
            <i class='bx bx-menu' id="menu-icon"></i>
            <!-- logo -->
            <a href="#" class="logo">Spare <span> Parts </span> Online</a>
            <!-- nav list -->
            <ul class="navbar">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="aboutus.php">About</a></li>
                
          
            </ul>
            <!-- search icon -->
            <i class='bx bx-search-alt-2' id="search-icon"></i>
            <!-- search box -->
            <div class="search-box container">
                <input type="search" name="" placeholder="search here...">
            </div>
        </div>


    <div class="container" style="width: 65%">
        <h1><center><a href="#" class="logo">light <span> Modification </span> cart</a></center></h1> <br><br>
        <?php
            $query = "select * from product order by id asc";
            $result = mysqli_query($connection,$query);
            if(mysqli_num_rows($result)>0){
                while($row = mysqli_fetch_array($result)){
                    ?>
                    <div class="col-md-3" style="float: left;">
                        <form method="post" action="light.php?action=add&id=<?php echo $row["id"];?>">
                            <div class="product">
                                <img src="<?php echo $row["image"];?>" width="190px" height="200px" class="img-responsive"><br><br>
                                <font color="#000000" face="courier" size="4"><b><?php echo $row["description"];?></font></b> <br><br>
                                <h5 class="text-danger"><b><?php echo $row["price"];?><b></h5>
                                <input type="text" name="quantity" class="form-control" value="1">
                                <input type="hidden" name="hidden_name" value="<?php echo $row["description"];?>">
                                <input type="hidden" name="hidden_price" value="<?php echo $row["price"];?>">
                                <input type="submit" name="add" style="margin-top: 5px;" class="btn btn-success" value="Add to cart">
                            </div>
                        </form>
                    </div>
        <?php
                }
            }
        ?>

        <div style="clear: both"></div><br>
        <h1><center><a href="#" class="logo">light <span> Modification </span> cart <span> details </span></a> </center></h1> <br>
        <div class="table-responsive">
            <table class="table table-bordered">
            <tr>
                <th width="30%">Product Description</th>
                <th width="10%">Quantity</th>
                <th width="13%">Price Details</th>
                <th width="10%">Total Price</th>
                <th width="17%">Remove Item</th>
            </tr>
            <?php
                if(!empty($_SESSION["shopping_cart"])){
                    $total=0;
                    foreach($_SESSION["shopping_cart"] as $key => $value){
                    ?>
                <tr>
                        <td><?php echo $value["product_name"];?></td>
                        <td><?php echo $value["product_quantity"];?></td>
                        <td><?php echo $value["product_price"];?></td>
                        <td><?php echo number_format($value["product_quantity"]*$value["product_price"],2);?></td>
                        <td><a href="light.php?action=delete&id=<?php echo $value["product_id"]; ?>"><span class="text-danger">Remove Item</span></a></td>
                </tr>
                <?php
                    $total = $total + ($value["product_quantity"]*$value["product_price"]);
                    }
                ?>
                <tr>
                        <td colspan="3" align="right">Total</td>
                        <td align="right"><?php echo number_format($total,2);?></td>
                        <td><a href="#" class="btn">Pay Now</a></td> 
                </tr>
                <?php
                }
                ?>
            </table>
        </div>

    </div>
    <!-- footer -->
    <section class="footer">
        <div class="footer-container container">
            <div class="footer-box">
                <a href="#" class="logo">Car<span>Point</span></a>
                <div class="social">
                    <a href="#"><i class='bx bxl-facebook'></i></a>
                    <a href="#"><i class='bx bxl-twitter'></i></a>
                    <a href="#"><i class='bx bxl-instagram'></i></a>
                    <a href="#"><i class='bx bxl-youtube'></i></a>
                </div>
            </div>
            <div class="footer-box">
                <h3>Page</h3>
                <a href="index.php">Home</a>
                <a href="index.php/#parts">Categaris</a>
                <a href="index.php/#parts2">Offer Parts</a>
                <a href="index.php/#made">We Maded</a>
                <a href="aboutus.php">About</a>
            </div>
            <div class="footer-box">
                <h3>Legal</h3>
                <a href="#">Privacy</a>
                <a href="#">Refund Policy</a>
                <a href="#">Cookie Policy</a>
            </div>
            <div class="footer-box">
                <h3>Contact</h3>
                <p>United States</p>
                <p>Japan</p>
                <p>Sri Lanka</p>
            </div>
        </div>
    </section>
    <!-- link to js -->
    <script src="main.js"></script>
</body>
</html>