<?php
	$id = $_POST['id'];
	$action = $_POST['action'] ; // Simple xml request
    $ajax_action = $_REQUEST['action'] ; // Ajax request
    
    session_start();

    function searchForId($id, $array) {
        foreach ($array as $key => $val) {
            if (trim($val['car_id']) == trim($id)) {
                return $key;
            }
        }
        return null;
    }
    
    if($ajax_action == "add"){
        $id = $_REQUEST['id'];
        $qty = 1;
        $xml = simplexml_load_file("cars.xml") or die("Error: Cannot create object");

        foreach($xml->children() as $cars) {
            if($cars->id == $id){
                if($cars->availability == "True"){
                    
                    $car_id = $cars->id;
                    $brand = $cars->brand;
                    $model = $cars->model;
                    $model_year = $cars->model_year;
                    $mileage = $cars->mileage;
                    $fuel_type = $cars->fuel_type;
                    $seats = $cars->seats;
                    $price_per_day = $cars->price_per_day;
                    $description = $cars->description;
                    $qty = 1;
                    if(isset($_SESSION["cart_item"]))
                    {
                        $item_array_key = searchForId($id, $_SESSION["cart_item"]);
                        if(isset($item_array_key))
                        {
                            echo "Your have already added this car in cart.";
                            //If you want to block duplicate entry 
                        } else {
                            echo "Your selected car is added to cart successfully.";
                            $item_array = array();
                            $item_array["car_id"] = "$car_id";
                            $item_array["brand"] = "$brand";
                            $item_array["model"] = "$model";
                            $item_array["model_year"] = "$model_year";
                            $item_array["mileage"] = "$mileage";
                            $item_array["fuel_type"] = "$fuel_type";
                            $item_array["seats"] = "$seats";
                            $item_array["price_per_day"] = "$price_per_day";
                            $item_array["description"] = "$description";
                            $item_array["qty"] = "$qty";
                            array_push($_SESSION["cart_item"],$item_array);
                        }
                    }else{
                        echo "Your selected car is added to cart successfully.";
                        $item_array = array();
                        $item_array["car_id"] = "$car_id";
                        $item_array["brand"] = "$brand";
                        $item_array["model"] = "$model";
                        $item_array["model_year"] = "$model_year";
                        $item_array["mileage"] = "$mileage";
                        $item_array["fuel_type"] = "$fuel_type";
                        $item_array["seats"] = "$seats";
                        $item_array["price_per_day"] = "$price_per_day";
                        $item_array["description"] = "$description";
                        $item_array["qty"] = "$qty";
                        
                        $_SESSION["cart_item"][0] = $item_array;
                    }
                }else{
                    echo "Sorry, this car is not available right now, can you choose another car?";
                }
            }
        }
    } 
    if ($action == "edit"){
        if(isset($_POST["id"]) && !empty($_POST["id"])){
            $item_array_key = searchForId($_POST["id"], $_SESSION["cart_item"]);
            if(isset($item_array_key))
            {
                $_SESSION["cart_item"][$item_array_key]['qty'] = $_POST["qty"];
            }
        }
    } else if ($action == "delete"){
        if(isset($_POST["id"]) && !empty($_POST["id"])){
            $item_array_key = searchForId($_POST["id"], $_SESSION["cart_item"]);
            if(isset($item_array_key))
            {
                unset($_SESSION['cart_item'][$item_array_key]);
            }
        }
    } else if ($action = "checkout"){
        session_start();
        if(isset($_POST['email'])){
            $amount = $_POST['amount'];
            $name = $_POST['name'];

            $to = $_POST['email'];
            $subject = 'Hertz-UTS Car Rental System';

            $headers = "From: 13123029@student.uts.edu.au\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $message = '<html><body>';
            $message .= '<label style="font-size: 15px; padding: 10px 0;">Dear '. $name .',</label><br><br>';
            $message .= '<label style="font-size: 23px; font-weight: bold; color: #1F6521; padding: 20px 0;">Hertz-UTS Car Rental System</label><br><br>';
            $message .= '<label style="font-size: 15px; font-weight: bold;">Thank you for renting cars with us.<br> Your Total amout to be paid is: <span style="font-size:23px; color: #1F6521;">$ '. $amount .'</span></label><br><br>';
            $message .= '<label style="font-size: 15px; font-weight: bold;">Your Rental car details:</label><br>';
            if(!empty($_SESSION["cart_item"])){
                foreach($_SESSION["cart_item"] as $keys => $values){
                    $message .= '<table rules="all" style="border: 1px solid #1F6521; width:100%;" cellpadding="10">';
                    $message .= "<tr style='background: #c9ee59;'><td><strong>Model:</strong></td><td>". $values['brand'] ." ". $values['model'] ."(". $values['model_year'] .")</td></tr>";
                    $message .= "<tr><td><strong>Mileage:</strong</td><td>". $values['mileage'] ."</td></tr>";
                    $message .= "<tr><td><strong>Fuel Type:</strong> </td><td>". $values['fuel_type'] ."</td></tr>";
                    $message .= "<tr><td><strong>Seats:</strong> </td><td>". $values['seats'] ."</td></tr>";
                    $message .= "<tr><td><strong>Price per day:</strong> </td><td>$". $values['price_per_day'] ."</td></tr>";
                    $message .= "<tr><td><strong>Rent Days:</strong> </td><td>". $values['qty'] ."</td></tr>";
                    $message .= "<tr><td><strong>Description:</strong> </td><td>". $values['description'] ."</td></tr>";
                    $message .= "</table><br><br>";
                }
                $message .= '<label style="font-size: 15px; padding: 10px 0;">Enjoy your rides!!!</label><br><br>';
                $message .= '<label style="font-size: 15px; padding: 10px 0;">Best Regards,</label><br>';
                $message .= '<label style="font-size: 15px; padding: 10px 0;">Hertz-UTS Car Rental System</label><br>';
                $message .= "</body></html>";
                mail($to,$subject,$message,$headers);
                unset($_SESSION['cart_item']);
                echo "Thank you for renting car with us. Enjoy your rides!!!";
            }else{
                echo "Your Cart is Empty!";
            }
        }
    }

?>
