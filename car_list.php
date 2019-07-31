<html>
    <head>
        <title>Hertz-UTS</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <style type="text/css"> 
            /* Flex-related code */
            .flex {
                display: flex;
                flex-wrap: wrap;
                margin-top: 40px;
            }
            .flex > section {
                align-items: center;
                display: flex;
                flex: 1 1 0;
                flex-direction: column;
                text-align: center;
            }
            ul {
                display: flex;
                justify-content: space-between;
            }
            aside {
                margin-top: auto;
            }
            /* Basic styling for UI */
            body {
                background: #c9ee59;
                font: 16px "Titillium Web", sans-serif;
                margin: 0;
            }
            img {
                width: 400px;
                border-radius: 2px;
            }
            @media (max-width: 1600px) {
                img{
                    width: 250px;
                }
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
            h2 {
                font-size: 25px;
            }
            .flex > section {
                background: #fff;
                padding: 2em 1em;
                margin: 0.5em;
                border-radius: 4px;
                border-top: 5px solid #1F6521;
            }
            button {
                background: #1F6521 url(http://i.imgur.com/RWKrr8S.png);
                background-repeat: no-repeat;
                background-position: 2em 50%;
                background-size: 2em;
                border: 0;
                border-radius: 4px;
                cursor: pointer;
                color: #FFF;
                font-weight: bold;
                font-size: 13px;
                padding: 2em 4em;
                padding-left: 8em;
                text-transform: uppercase;
            }
            button:hover {
                background-color: #A4A71E;
                background-size: 3em;
                background-position: 1.5em 50%;
            }
            .car_price {
                font-size: 20px;
                float: left;
                color: #1F6521;
                margin-top: 0;
                margin-left: 10px;
            }
            .car_availibility {
                float: right;
                font-size: 20px;
                color: #1F6521;
                margin-top: 0;
                margin-right: 10px;
            }
            .cart{
                padding: 6px 20px 11px 0;
                float: right;
            }
            .cart img{
                width: 40px;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>Hertz-UTS Car Rental System<span>We keep you moving...</span>
                <span class="cart" onclick="openCart();"><img src="images/cart.png" alt="cart image"></span>
            </h1>
        </header>
        <section id="carList" class="flex"></section>
        <script>
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    list_cars(this);
                }
            };
            xhttp.open("GET", "cars.xml", true);
            xhttp.send();
            
            function list_cars(xml){
                var xmlDoc = xml.responseXML;
                var str = "";
                var cars = xmlDoc.getElementsByTagName("carrentalsystem")[0].children;
                var fields = new Array("id","brand","model","model_year","mileage","fuel_type","seats","price_per_day","description","availability");
                for (var i=0; i<cars.length; i++){
                    var car = cars[i];
                    var car_detail_items = car.children;
                    var car_details = new Array(10);
                    var availability = "";
                    for(var j=0;j<car_details.length;j++){
                        if(car_detail_items[j].nodeName===fields[j]){
                            car_details[j] = car_detail_items[j].childNodes[0].nodeValue;
                        }else{
                            alert("Car details are not valid.");
                            continue;
                        }
                    }
                    if(car_details[9] == "True"){
                        availability = "Available";
                    }else{
                        availability = "Not Available";
                    }
                    
                    str = str + '<section>'
                            + '<img src="images/' + car_details[2] + '.jpg" alt="car image">'
                            + '<div>'
                            + '<h2>' + car_details[1] + ' ' + car_details[2] + ' (' + car_details[3] + ')</h2>'
                            + '<p><b>Mileage: </b>' + car_details[4] + ' kms </p>'
                            + '<p><b>Fuel Type: </b>' + car_details[5] + '</p>'
                            + '<p><b>Seats: </b>' + car_details[6] + ' </p>'
                            + '<p class="car_description">' + car_details[8] + '</p>'
                            + '<p class="car_price"> <b>AUD ' + car_details[7] + '</b></p>'
                            + '<p class="car_availibility"><b>' + availability + '</b></p>'
                            + '<button type="button" onclick="check_avilability(' + car_details[0] + ')" >Add To Cart</button>'
                            + '</div>'
                            + '</section>';
                }
                $("#carList").html(str);
            }
            
            function check_avilability(id){ // With both example Ajax and Xml
                $.ajax({
                    type: "POST",  
                    url: "session.php?action=add",
                    data:{id:id},
                    success: function(data) {
                        alert(data);
                    } 
                });    
                // var xhttp = new XMLHttpRequest();
                // xhttp.open("POST", "session.php", true);
                // xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                // xhttp.onreadystatechange = function() {
                //     if (this.readyState == 4 && this.status == 200) {
                //         alert(xhttp.responseText);
                //         //alert("Your selected car is added to cart successfully.");
                //     }
                // };
                // xhttp.send("id="+id+"&action=add");
            }
            function openCart(){
                window.location.href="cart.php";
            }
        </script>
    </body> 
</html>
