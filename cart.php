<?php
include( 'config.php' );
include( 'functions.php' );

if( isset( $_GET[ 'delete_id' ] ) ) {
    remove_from_cart( $_GET[ 'delete_id' ], $_SESSION[ 'user_id' ] );
}

$cart = $_SESSION[ 'cart' . $_SESSION[ 'user_id' ] ];
$total = 0;

if( !count( $cart ) == 0 ) {
    
    foreach( $cart as $item ) {
        $total += $item[ 'price' ];
    }
}

include( 'top.php' );

?>

<header>
    <h1>Cart</h1>
</header>

<!--LINKS (ABSOLUTE)-->
<section class="content">

<!--    <div id="product">-->
        
        <table id="cart">
            
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th>Store</th>
                    <th>Size</th>
                    <th class="cart-price">Price</th>
                </tr>
            </thead>
        
            <?php if( count( $cart ) != 0 ): ?>
            
                <?php foreach( $cart as $i => $item ): ?>

                    <tr class="cart-item">

                        <td class="cart-image">
                            <img src="<?php echo PRODUCT_IMAGE_FOLDER . $item[ 'image' ]; ?>"
                                 alt="<?php echo $item[ 'name' ]; ?>">
                        </td>

                        <td class="cart-name"><?php echo $item[ 'name' ]; ?></td>

                        <td class="cart-store"><?php echo $item[ 'store' ]; ?></td>

                        <td class="cart-size"><?php echo $item[ 'size' ]; ?></td>

                        <td class="cart-price">$<?php echo $item[ 'price' ]; ?></td>

                        <td class="cart-delete">

                            <form id="cart-delete-button"
                                  action="<?php echo $_SERVER[ 'PHP_SELF' ]; ?>"
                                  method="get">

                                <input type="hidden"
                                       name="delete_id"
                                       value="<?php echo $i; ?>" />

                                <input name="delete"
                                       type="submit"
                                       value="&times;" />

                            </form>

                        </td>

                    </tr>

                <?php endforeach; ?>
            
            <?php else: ?>
            
            <tr>Your cart is empty.</tr>
            
            <?php endif; ?>
            
        </table>
        
        <div id="checkout">
        
            <h2 id="checkout-title">Checkout</h2>
            
            <p>Purchase
                
                <?php echo count( $cart ); ?> 
                
                <?php   if( count( $cart ) == 1 ): echo 'item';
                        else: echo 'items';
                        endif;
                ?>
                
                for a total of $<?php echo $total; ?>.</p>
            
            <a id="checkout-button" href="checkout.php">Checkout</a>
        
        </div>
        
<!--    </div>-->

</section>
        
<?php include( 'bottom.php' );?>