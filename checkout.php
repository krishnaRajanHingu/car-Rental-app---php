<?php
    $grant_total = $_POST['grantTotal'];
 ?>
<html>
    <head>
        <title>Hertz-UTS</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <style type="text/css"> 
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
            .checkout_container {
                width: 850px;
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
            .button_checkout{
                margin: 20px 0 0 34px;
                padding: 12px 30px;
                background-color: #D6CE00;
                color: white;
                text-decoration: none;
                font-size: large;
                border: 2px solid #1F6521;
                float: right;
            } 
            h2{
                text-align: center;
                font-size: 20px;
                color: #1F6521;
                margin-bottom: 30px;
            }
            div.main{
                padding: 10px 55px 40px;
            }
            input[type=text], .selectBox{
                width: 100%;
                height: 34px;
                padding-left: 5px;
                margin-bottom: 25px;
                margin-top: 8px;
                box-shadow: 0 0 2px #c9ee59;
                border: 1px solid #c9ee59;
                color: #4f4f4f;
                font-size: 16px;
            }
            label{
                color: #1F6521;
                font-size: 16px;
                font-weight: bold;
            }
            .asterisk{
                padding: 4px;
                color: red;
                font-weight: bold;
                font-size: 20px;
            }
            #errorMsg{
                color: red;
                font-weight: bold;
                font-size: 20px;
                margin-bottom: 8px;
            }
        </style>
       <script type="text/javascript">
            function openCheckout(){
                var email = $("#emailAddress").val();
                if($("#firstName").val() == "" || $("#lastName").val() == "" || email == "" || $("#address1").val() == "" || $("#city").val() == "" || $("#state").val() == "" || $("#paymentType").val() == "")
                {
                    $("#errorMsg").html("Please fill all the required fields.");
                    $(window).scrollTop(0);
                    return false;
                }
                else if(!validateEmail(email)){
                    $("#errorMsg").html("Please enter a valid email address.");
                    $(window).scrollTop(0);
                    return false;
                }
                else{
                    var total = <?php echo $grant_total ?>;
                    var name = $("#firstName").val() + " " + $("#lastName").val();
                    $("#errorMsg").html("");
                    var xhttp = new XMLHttpRequest();
                    xhttp.open("POST", "session.php", true);
                    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            window.location.href="car_list.php";
                            alert(xhttp.responseText);
                        }
                    };
                    xhttp.send("action=checkout&email="+email+"&name="+ name +"&amount="+total);
                }
            }
            function validateEmail($email) {
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                return emailReg.test( $email );
            }
            function openList(){
                window.location.href="car_list.php";
            }
        </script>
    </head>
    <body>
        <header>
            <h1>Hertz-UTS Car Rental System<span>We keep you moving...</span>
                <span onclick="openList();" style="float: right;"><img src="images/list.png" alt="menu image" style="margin-right: 21px;"></span>
            </h1>
        </header>
              
        <div class="checkout_container">
            <!-- Title -->
            <div class="title">Checkout Form</div>
            <div class="main">
                <form class="form" method="post" action="#">
                    <h2>Enter your all details. <span class="asterisk">*</span> indicates required field.</h2>
                    <div id="errorMsg"></div>
                    <label>First Name :<span class="asterisk">*</span></label>
                    <input type="text" name="firstName" id="firstName">

                    <label>Last Name :<span class="asterisk">*</span></label>
                    <input type="text" name="lastName" id="lastName">

                    <label>Email Address :<span class="asterisk">*</span></label>
                    <input type="text" name="emailAddress" id="emailAddress">

                    <label>Address Line 1 :<span class="asterisk">*</span></label>
                    <input type="text" name="address1" id="address1">

                    <label>Address Line 2 :</label>
                    <input type="text" name="address2" id="address2">

                    <label>City :<span class="asterisk">*</span></label>
                    <input type="text" name="city" id="city">

                    <label>State :<span class="asterisk">*</span></label>
                    <select name="state" id="state" class="selectBox">
                        <option value="ACT">Australia Capital Territory</option>
                        <option value="NSW">New South Wales</option>
                        <option value="NT">Northern Territory</option>
                        <option value="QLD">Queensland</option>
                        <option value="SA">South Australia</option>
                        <option value="TAS">Tasmania</option>
                        <option value="VIC">Victoria</option>
                        <option value="WA">Western Australia</option>
                    </select>

                    <label>Zip Code :<span class="asterisk">*</span></label>
                    <input type="text" name="zipCode" id="zipCode">

                    <label>Payment Type <span class="asterisk">*</span></label>
                    <select id="paymentType" name="paymentType" class="selectBox">
                        <option value="visa">VISA</option>
                        <option value="master">Master Card</option>
                        <option value="payPal">PayPal</option>
                    </select>
                </form>
                <label style="font-size: 21px;">Your total amount to be paid: $ <?php echo $grant_total ?><span></span></label>
                <div>
                    <button class="button_checkout" style="background-color: #1F6521;" onclick="openCheckout();">Purchase</button>
                    <button class="button_checkout" onclick="openList();"> Continue Shopping </button>
                </div>
            </div>
        </div>
    </body> 
</html>
