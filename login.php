<?php

include( 'config.php' );
include( 'functions.php' );

$response = [];
$location = "";

if( isset( $_GET[ 'id' ] ) ) { $location = "product.php?id={$_GET[ 'id' ]}"; }

if( $_POST[ 'posted' ] ) {
    
    $response = login( $db,
                       $_POST[ 'email' ],
                       $_POST[ 'password' ],
                       $location );
}

include( 'top.php' );

?>

<header><h1>Login</h1></header>

<section class="content">

    <div id="login">

        <form id="login-form" action="<?php
                                      if( isset( $_GET[ 'id' ] ) ) {
                                          echo $_SERVER[ 'PHP_SELF' ] . "?id={$_GET[ 'id' ]}";
                                      } else {
                                          echo $_SERVER[ 'PHP_SELF' ];
                                      }
                                      ?>" method="post">
            
            <ul>
                <input type="hidden" name="posted" value="1" />

                <li>
                    <?php echo $response[ 'email' ]; ?>
                    <input type="email"
                           name="email"
                           id="email"
                           placeholder="Email"
                           value="<?php echo $_POST[ 'email' ] ?>"/>
                </li>

                <li>
                    <?php echo $response[ 'password' ]; ?>
                    <input type="password"
                           name="password"
                           id="password"
                           placeholder="Password"
                           value="<?php echo $_POST[ 'password' ] ?>"/>
                </li>

                <li>
                    <input id="login-button"
                           type="submit"
                           value="Login" />
                </li>
            </ul>

        </form>

    </div>

</section>

<?php include( 'bottom.php' ); ?>