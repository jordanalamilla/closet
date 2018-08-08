<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>CLOSET!</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"
            integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
            crossorigin="anonymous"></script>
    
    <script>
    
    $( function() {
        
        //SHOW MOBILE NAV WHEN CLOSET BUTTON CLICKED
        $( '#mobile-nav-button' ).click( function(){
            $( '#mobile-nav-links' ).toggle();
        });
        
        //TOGGLE CUSTOMER AND STORE SIGN UP TEXT FIELDS
        $( "#store-account" ).hide();
        $( "#radio-store" ).click( function() { $( "#store-account" ).show(); });
        $( "#radio-customer" ).click( function() { $( "#store-account" ).hide(); });
        
    });
    
    </script>
    
    <!--[if lt IE 9]>
        <script src="js/html5shiv.min.js"></script>
    <![endif]-->
</head>
<body>

<?php include( 'nav.php' ); ?>