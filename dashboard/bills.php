<!doctype html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title> Pay Bills </title>
		<link href="https://fonts.googleapis.com/css2?family=Zilla+Slab:wght@300&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../css/index.css">
	
</head>
<style type="text/css">
	body{
		background-image: url(../img/2918298.jpg);
		background-size: cover;
	}
</style>

<?php
session_start();


require_once "../functions.php";
checkUser();
patientCheck();



?>

<body>
<p> <u>Make your payments here.<br></u></p>
<p style='color:red'> <u>Please note that your appointments will not be honoured if you haven't made a payment.</u></p><br><br>

<span id="ajax"></span><br><br>

<form>
	<label>Email Address</label><br>
		<input type="text" name="email" id="email" required><br><br>

		<label>Amount</label><br>
		<input type="number" name="amount" id="amount" required><br><br>

		<label>Phone number</label><br>
		<input type="text" name="phone" id="phone" required><br><br>
    
</form>


    <button type="button" id="pay">Pay Now</button><br><br>
    <a href="patient.php">Back</a>&nbsp

<script type="text/javascript" src="js/jquery-3.2.1.js"></script>
<script type="text/javascript" src="https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
<script type="text/javascript">


const API_publicKey = "FLWPUBK-902adba8d930e1d4748fd2554dec604b-X";

var pay = document.getElementById("pay");
pay.addEventListener("click", payWithRave, false);


    function payWithRave() {
    	 var email = document.getElementById("email").value;
    var amount = document.getElementById("amount").value;
    var number = document.getElementById("phone").value;

    function getRandomString(length) {
    var randomChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var result = '';
    for ( var i = 0; i < length; i++ ) {
        result += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
    }
    return result;
}

var ref = getRandomString(13);

        var x = getpaidSetup({
            PBFPubKey: API_publicKey,
            customer_email: email,
            amount:amount,
            customer_phone: number,
            currency: "NGN",
            txref: getRandomString(13),
            meta: [{
                metaname: "flightID",
                metavalue: "AP1234"
            }],
            onclose: function() {},
            callback: function(response) {
                var txref = response.tx.txRef;
                var code = response.tx.chargeResponseCode; 
                var msg = response.data.respmsg;
                var amtt = response.tx.charged_amount;
                var status = response.tx.status;
                // collect txRef returned and pass to a                    server page to complete status check.
                console.log("This is the response returned after a charge", response);
                if ((code = "00") && (amtt == amount)){
                   // window.location = "handle_bills.php";
                console.log("Input amount " + amount +  "Proccessed amount" + amtt + txref);
               alert("Thanks for your payment. Check your email for confirmation");

				$.post("handle_bills.php", {
                    
                       email : email,
                       amount : amount,
                       ref : ref,
                       phone : number

				},  function(data,status){
					$("#ajax").html(data);
                });

                } 
                else {
                    // redirect to a failure page.
                }

                x.close(); // use this to close the modal immediately after payment.
            }
        });
    }

</script>
	</body>
	</html>