<?php

include( 'config.php' );
include( 'functions.php' );

$_SESSION[ 'logged_in' ]        = null;
$_SESSION[ 'user_type' ]        = null;
$_SESSION[ 'user_name' ]        = null;
$_SESSION[ 'user_first_name' ]  = null;

unset( $_SESSION[ 'logged_in' ] );
unset( $_SESSION[ 'user_type' ] );
unset( $_SESSION[ 'user_name' ] );
unset( $_SESSION[ 'user_first_name' ] );

if( isset( $_SESSION[ 'store_id' ] ) ) {

    $_SESSION[ 'store_id' ]         = null;
    $_SESSION[ 'store_user_id' ]    = null;
    $_SESSION[ 'store_name' ]       = null;
    $_SESSION[ 'store_thumb' ]      = null;
    $_SESSION[ 'store_banner' ]     = null;
    
    unset( $_SESSION[ 'store_id' ] );
    unset( $_SESSION[ 'store_user_id' ] );
    unset( $_SESSION[ 'store_name' ] );
    unset( $_SESSION[ 'store_thumb' ] );
    unset( $_SESSION[ 'store_banner' ] );
}

$_SESSION[ 'console' ] = 'Logged out';

redirect();