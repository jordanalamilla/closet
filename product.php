<?php
include( 'config.php' );
include( 'functions.php' );

$product_data   = get_product_data( $db, $_GET[ 'id' ] );
$store_data     = get_store_data( $db, $product_data[ 1 ] );

$add_to_cart = '<input type="submit" value="Add To Cart">';

if( $_POST[ 'submitted' ] ) {
    
    if( check_login() ) {
        
        $product = array(
            "product_id"=> $product_data[ 0 ],
            "image"     => $product_data[ 5 ],
            "name"      => $product_data[ 2 ],
            "store"     => $store_data[ 1 ],
            "size"      => $_POST[ 'size' ],
            "price"     => $product_data[ 4 ]
        );
        
        add_to_cart( $product, $_SESSION[ 'user_id' ] );
        
        $add_to_cart = 'Thank You!';
        
    } else {
        redirect( "login.php?id={$product_data[ 0 ]}" );
    }
    
}

 include( 'top.php' );

?>
        
<!--HEADER-->
<header id="store-header">
    <div id="store-name">
        <h1><?php echo $store_data[1]; ?></h1>
    </div>
    
    <div id="vignette"></div>
    
    <img src="images/banner/<?php echo $store_data[2]; ?>" alt="<?php echo $store_data[1]; ?>" />
</header>

<!--LINKS (ABSOLUTE)-->
<section class="content">

    <div id="product">
            
        <img src="<?php echo PRODUCT_IMAGE_FOLDER . $product_data[ 5 ]; ?>"
             alt="<?php echo $product_data[ 2 ] ?>">
        
        <form id="add-to-cart-form"
              method="post"
              action="<?php echo $_SERVER[ 'PHP_SELF' ] . '?id=' . $product_data[ 0 ]; ?>">
            
            <input type="hidden" name="submitted" value="1">
            
            <ul>
                <li id="product-name"><?php echo $product_data[ 2 ]; ?></li>              <!--NAME-->
                
                <li>By <?php echo $store_data[1]; ?></li>              <!--STORE NAME-->
                
                <li>$<?php echo number_format( $product_data[ 4 ], 2 ); ?></li>              <!--PRICE-->
                
                <li id="size-dropdown">
                    <select name="size">
                        <option value="S">Small</option>
                        <option value="M">Medium</option>
                        <option value="L">Large</option>
                    </select>
                </li>
                
                <li id="add-to-cart-button"><?php echo $add_to_cart; ?></li>
            </ul>
            
        </form>
        
    </div>

</section>
        
<?php include( 'bottom.php' );

//0 product_id
//1 store_id
//2 name
//3 gender
//4 price
//5 image
                 
?>