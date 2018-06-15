<?php

include( 'config.php' );
include( 'functions.php' );
include( 'top.php' );

$gender = $_GET[ 'gender' ];

?>
        
<!--HEADER-->
<header>
    <h1>
        <?php
        switch( $gender ) {
                
            case "male":
                echo "Men!";
                break;
            
            case "female":
                echo "Women!";
                break;
                
            case "all":
                echo "All T-Shirts!";
                break;
                
            default:
                echo "Stores!";
                break;
                
        }
        ?>
    </h1>
</header>

<!--LINKS (ABSOLUTE)-->
<section class="content">

    <?php echo generate_gallery( $db, $_GET[ 'type' ], $_GET[ 'gender' ] ); ?>

</section>
        
<?php include( 'bottom.php' ); ?>