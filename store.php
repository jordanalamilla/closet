<?php

include( 'config.php' ); 
include( 'functions.php' ); 

$id = $_GET[ 'id' ];

$data = get_store_data( $db, $id );

include( 'top.php' );

?>
        
<!--HEADER-->
<header id="store-header">
    <div id="store-name">
        <h1><?php echo $data[1]; ?></h1>
    </div>
    
    <?php if( isset( $_SESSION[ 'store_id' ] )
             && $id == $_SESSION[ 'store_id' ] ): ?>
    
    <div id="store-buttons">
        <ul>
            <li><a href="form.php?type=add_product">Add New Product</a></li>
            <li><a href="form.php?type=update_banner">Update Banner</a></li>
        </ul>
    </div>
    
    <?php endif; ?>
    
    <div id="vignette"></div>
    
    <img id="banner-image" src="images/banner/<?php echo $data[2]; ?>" alt="<?php echo $data[1]; ?>" />
</header>

<!--LINKS (ABSOLUTE)-->
<section class="content">

    <?php echo generate_gallery( $db, $_GET[ 'type' ], null, $id ); ?>

</section>
        
<?php include( 'bottom.php' ); ?>