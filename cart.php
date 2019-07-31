<?php
    session_start();
    $grantTotal = 0;
?>

<html>
    <head>
        <title>Hertz-UTS</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <style type="text/css">
            /* Basic styling for UI */
            body {
                background: #c9ee59;
                font: 16px "Titillium Web", sans-serif;
                margin: 0;
            }
            header {
                text-align: center;
                background: #1F6521;
                padding: 1px;
            }
            h1 {
                color: #fff;
                font-family: Helvetica, Arial, sans-serif;
                font-weight: 300;
                font-size: 38px;
            }
            h1 > span {
                font-size: 17px;
                margin-left: 8px;
                font-style: italic;
            }
            .shopping-cart {
                width: 950px;
                margin: 53px auto;
                background: #FFFFFF;
                box-shadow: 1px 2px 3px 0px rgba(0,0,0,0.10);
                border-radius: 6px;
                display: flex;
                flex-direction: column;
            }
            .title {
                border-bottom: 1px solid #c9ee59;
                padding: 20px 30px;
                color: #1F6521;
                font-size: 23px;
                font-weight: bold;
                box-shadow: 1px 2px 3px 0px rgb(201,238,89);
            }
            .item {
                padding: 20px;
                display: flex;
                border-bottom: 1px solid #c9ee59;
                box-shadow: 1px 2px 3px 0px rgb(201,238,89);
            }
            /* Buttons -  Delete and Like */
            .buttons > span {
                float: left;
                width: 100%;
            }
            .delete-btn {
                width: 44px;
                padding: 10px 10px 10px 0;
            }
            /* Product Image */
            .image{
                width: 23%;
                height: auto;
            }
            .image img{
                width: 50%;
            }
            /* Product Description */
            .description {
                padding-top: 10px;
                margin-right: 20px;
                width: 180px;
            }
            .description span {
                display: block;
                font-size: 15px;
                color: #43484D;
                margin-top: 15px;
            }
            /* Product Quantity */
            .quantity {
                padding-top: 20px;
                margin-right: 63px;
            }
            .quantity input {
                -webkit-appearance: none;
                text-align: center;
                width: 40px;
                font-size: 16px;
                color: #43484D;
                font-weight: 300;
            }
            button[class*=btn] {
                width: 30px;
                height: 30px;
                background-color: #c9ee59;
                border-radius: 6px;
                border: none;
                cursor: pointer;
            }
            button:focus,
            input:focus {
                outline:0;
            }
            /* Total Price */
            .price_per_day {
                width: 90px;
                padding-top: 27px;
                text-align: center;
                font-size: 16px;
                font-weight: 300;
                margin-right: 60px;
            }
            .sub_total{
                width: 102px;
                padding-top: 27px;
                text-align: center;
                font-size: 16px;
                font-weight: 300;
            }
            .button_checkout{
                margin: 20px;
                padding: 12px 30px;
                background-color: #D6CE00;
                color: white;
                text-decoration: none;
                font-size: large;
                border: 2px solid #1F6521;
                float: right;
            }   
            .hide{
                display: none;
            }
            .show{
                display: block;
            }
            /* Responsive */
            @media (max-width: 800px) {
                .shopping-cart {
                    width: 100%;
                    height: auto;
                    overflow: hidden;
                }
                .item {
                    height: auto;
                    flex-wrap: wrap;
                    justify-content: center;
                }
                .image img {
                    width: 50%;
                }
                .image,
                .quantity,
                .description {
                    width: 100%;
                    text-align: center;
                    margin: 6px 0;
                }
                .buttons {
                    margin-right: 20px;
                }
            }
       </style>
       <script type="text/javascript">
            function minusQty(id, price) {
                var value = parseInt($("#carQty_"+id).val());
                var prevGrant = $("#grandTotal").html().split(" ")[1];
                var prevTotal = $("#total_price_"+id).html().split(" ")[1];
                var withoutItem = prevGrant - prevTotal;

                if (value > 1) {
                    value = value - 1;
                } else {
                    value = 1;
                }
                $("#carQty_"+id).val(value);
                $("#total_price_"+id).html("$ " + (value * price));
                $("#grandTotal").html("$ " + (withoutItem + (value * price)));
                var xhttp = new XMLHttpRequest();
                xhttp.open("POST", "session.php", true);
                xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        //alert("Your selected car is added to cart successfully.");
                    }
                };
                xhttp.send("id="+id+"&qty="+value+"&action=edit");
            }

            function plusQty(id, price) {
                var value = parseInt($("#carQty_"+id).val());
                var prevGrant = $("#grandTotal").html().split(" ")[1];
                var prevTotal = $("#total_price_"+id).html().split(" ")[1];
                var withoutItem = prevGrant - prevTotal;

                if (value < 20) {
                    value = value + 1;
                } else {
                    value = 20;
                }

                $("#carQty_"+id).val(value);
                $("#total_price_"+id).html("$ " + (value * price));
                $("#grandTotal").html("$ " + (withoutItem + (value * price)));

                var xhttp = new XMLHttpRequest();
                xhttp.open("POST", "session.php", true);
                xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        //alert("Your selected car is added to cart successfully.");
                    }
                };
                xhttp.send("id="+id+"&qty="+value+"&action=edit");
            }

            function deleteItem(id){
                var r = confirm("Are you sure want to delete this car from cart?");
                if (r == true) {
                    // For Edit Grand Total
                    var total = $("#total_price_"+id).html().split(" ")[1];
                    var prevGrant = $("#grandTotal").html().split(" ")[1];
                    $("#grandTotal").html("$ " + (prevGrant - total));
                    $("#item_"+id).remove();

                    // Remove Header and toal bar
                    var items = $("#main_container").find('.item').length;
                    if(items == 1){
                         $(".deleteEmptyCart").remove();
                         $("#emptyCart").removeClass("hide");
                    }
                    
                    // For Edit Session
                    var xhttp = new XMLHttpRequest();
                    xhttp.open("POST", "session.php", true);
                    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                        }
                    };
                    xhttp.send("id="+id+"&action=delete");
                }
            }
            
            function openCheckout(){
                $("#cartForm").attr("action", "checkout.php");
            }
            function openList(){
                window.location.href="car_list.php";
            }
            function openCarList(){
                $("#cartForm").attr("action", "car_list.php");
            }
        </script>
    </head>
    <body>
        <header>
            <h1>Hertz-UTS Car Rental System<span>We keep you moving...</span>
                <span onclick="openList();" style="float: right;"><img src="images/list.png" alt="menu image" style="margin-right: 21px;"></span>
            </h1>
        </header>
        <form method="post" id="cartForm" class="shopping-cart">
            <!-- Title -->
            <div class="title">Shopping Bag</div>
            <div id="main_container">
                <?php
                if(!empty($_SESSION["cart_item"]))
                { ?>
                    <div class="item deleteEmptyCart">
                        <div style="width:39%; text-align:end; font-weight:bold; color: #1F6521;">Vehicle</div>
                        <div style="width:22%; text-align:end; font-weight:bold; color: #1F6521;">Rental Days</div>
                        <div style="width:18%; text-align:end; font-weight:bold; color: #1F6521;">Price per day</div>
                        <div style="width:15%; text-align:end; font-weight:bold; color: #1F6521;">Subtotal</div>
                    </div>
                    <?php 
                        $total = 0;
                        foreach($_SESSION["cart_item"] as $keys => $values)
                        {
                            
                    ?>
                    <div class="item" id="item_<?php echo $values["car_id"];?>">
                        <div class="buttons">
                            <span><img src="images/delete.png" id="delete_<?php echo $values["car_id"];?>" class="delete-btn" onclick="deleteItem(<?php echo $values["car_id"];?>);" alt=""/></span>
                        </div>

                        <div class="image">
                            <img src="images/<?php echo $values['model'];?>.jpg" alt=""/>
                        </div>

                        <div class="description">
                            <span><?php echo $values["brand"];?> <?php echo $values["model"];?> (<?php echo $values["model_year"];?>)</span>
                        </div>

                        <div class="quantity">
                            <button class="plus-btn" id="plus_<?php echo $values["car_id"];?>" type="button" onclick="plusQty(<?php echo $values["car_id"];?>, <?php echo $values["price_per_day"];?>);">
                                <img src="images/plus.svg" alt="" />
                            </button>
                            <input type="number" id="carQty_<?php echo $values["car_id"];?>" value="<?php echo $values['qty'];?>" min="1" max="20" disabled>
                            <button class="minus-btn" id="minus_<?php echo $values["car_id"];?>" type="button" onclick="minusQty(<?php echo $values["car_id"];?>, <?php echo $values["price_per_day"];?>);">
                                <img src="images/minus.svg" alt="" />
                            </button>
                        </div>

                        <div class="price_per_day">$ <?php echo $values["price_per_day"];?></div>
                        <?php
                            $total = $values["qty"] * $values["price_per_day"];
                            $grantTotal = $grantTotal + $total;
                        ?>
                        <div class="sub_total" id="total_price_<?php echo $values["car_id"];?>">$ <?php echo $total;?></div>
                    </div>
                
                <?php } ?>
                <?php $contents = ob_get_contents();?>
                </div>
                <div style="color: #1F6521;" class="deleteEmptyCart">
                    <div style="text-align: end; margin-top: 22px; font-size: 20px; font-weight: bold;">Grant Total: 
                        <div style="padding: 0 80px; float: right;" id="grandTotal">$ <?php echo $grantTotal; ?></div>
                    </div>
                </div>
                <?php 
                if ($grantTotal > 0){
                ?>
                    <div class="deleteEmptyCart">
                        <button class="button_checkout" style="background-color: #1F6521;" onclick="openCheckout();"> Checkout</button>
                        <button class="button_checkout" onclick="openCarList();">Continue Shopping </button>
                    </div>
                    <input type="hidden" name="grantTotal" value="<?php echo $grantTotal; ?>">
                <?php } ?>
                <?php
                    }
                else { ?>
                <div>
                    <img  width="100%" src="images/cartempty.jpg" alt="cart image" />            
                </div>
             <?php   } ?>
            <div id="emptyCart" class="hide">
                <img  width="100%" src="images/cartempty.jpg" alt="cart image" />            
            </div>
        </form>
    </body> 
</html>
