<?php

include( 'config.php' );
include( 'functions.php' );
include( 'top.php' );

?>
        
<header id="home-header">
    <h1>CLOSET!</h1>
</header>

<section id="home" class="content">

    <?php echo generate_gallery( $db, 'product', 'all' ); ?>

</section>
        
<?php include( 'bottom.php' ); ?>