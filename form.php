<?php

include( 'functions.php' ); include( 'top.php' );

$type = $_GET[ 'type' ];
$h1 = 'Add New Product';

if( $type == 'update_banner' ){
    $h1 = 'Update Banner';
} elseif( $type == 'sign_up' ) {
    $h1 = 'Sign Up';
}

$response = null;

if( $_POST[ 'submitted' ] ) {
    
    if( $type == 'add_product' ) {
        
        $response = add_product( $db,
                                 $_SESSION[ 'store_id' ],
                                 $_FILES[ 'image' ],
                                 $_POST[ 'name' ],
                                 $_POST[ 'gender' ],
                                 $_POST[ 'price' ] );
        
    } elseif( $type == 'update_banner' ) {
        
        update_banner( $db, $_SESSION[ 'store_id' ], $_FILES[ 'image' ] );
        
    } elseif( $type == 'sign_up' ) {
        
        $response = sign_up( $db,
                             $_POST[ 'email' ],
                             $_POST[ 'password' ],
                             $_POST[ 'retype_password' ],
                             $_POST[ 'first_name' ],
                             $_POST[ 'last_name' ],
                             $_POST[ 'type' ],
                             $_POST[ 'store_name' ],
                             $_FILES[ 'store_thumb' ],
                             $_FILES[ 'store_banner' ] );
        
    }
}
    

?>
        
<!--HEADER-->
<header>
    <h1><?php echo $h1; ?>!</h1>
</header>

<!--LINKS (ABSOLUTE)-->
<section class="content">

    <?php if( $type == 'add_product' ): ?>
    
    <form id="add-product"
          method="post"
          action="<?php echo $_SERVER[ 'PHP_SELF' ] . '?type=' . $type; ?>"
          enctype="multipart/form-data">
        
        <input type="hidden" name="submitted" value="1">
    
        <ul>
            <li>
                <label for="image">Image</label>
                <input type="file" name="image">
            </li>
            <li>
                <?php echo $response[ 'name' ] ?>
                <label for="name">Name</label>
                <input type="text"
                       name="name"
                       value="<?php echo $_POST[ 'name' ]; ?>">
            </li>
            <li>
                <?php echo $response[ 'gender' ] ?>
                <label for="gender">Gender</label>
                <select name="gender">
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                </select>
            </li>
            <li>
                <?php echo $response[ 'price' ] ?>
                <label for="price">Price</label>
                <input type="text"
                       name="price"
                       value="<?php echo $_POST[ 'price' ]; ?>">
            </li>
            <li>
                <input type="submit" value="Add Product">
            </li>
        </ul>
    
    </form>
    
    <?php elseif( $type == 'update_banner' ): ?>
    
    <form id="update-banner"
          method="post"
          action="<?php echo $_SERVER[ 'PHP_SELF' ] . '?type=' . $type; ?>"
          enctype="multipart/form-data">
        
        <input type="hidden" name="submitted" value="1">
    
        <ul>
            <li>
                <label for="image">New Banner</label>
                <input type="file" name="image">
            </li>
            <li>
                <input type="submit" value="Update Banner">
            </li>
        </ul>
    
    </form>
    
    <?php elseif( $type == 'sign_up' ): ?>
    
    <form id="sign-up-form"
          method="post"
          action="<?php echo $_SERVER[ 'PHP_SELF' ] . '?type=' . $type; ?>"
          enctype="multipart/form-data">
        
        <input type="hidden" name="submitted" value="1">

        <div id="customer-account">
            <!--CHOOSE CUSTOMER OR STORE ACCOUNT-->
            <ul id="choose-customer-store">
                <li>
                    <input type="radio"
                           id="radio-customer"
                           name="type"
                           value="0"
                           checked>

                    <label for="radio-customer">Customer</label>
                </li>
                
                <li>
                    <input type="radio"
                           id="radio-store"
                           name="type"
                           value="1">

                    <label for="radio-store">Store</label>
                </li>
            </ul>
    
            <!--EMAIL-->
            <?php echo $response[ 'email' ] ?>
            <label for="email">Email</label>
            <input type="text"
                   name="email"
                   value="<?php echo $_POST[ 'email' ]; ?>">

            <!--PASSWORD-->
            <?php echo $response[ 'password' ] ?>
            <label for="password">Password</label>
            <input type="password"
                   name="password"
                   value="<?php echo $_POST[ 'password' ]; ?>">

            <?php echo $response[ 'retype_password' ] ?>
            <label for="retype_password">Retype Password</label>
            <input type="password"
                   name="retype_password">

            <!--FIRST NAME-->
            <?php echo $response[ 'first_name' ] ?>
            <label for="first_name">First Name</label>
            <input type="text"
                   name="first_name"
                   value="<?php echo $_POST[ 'first_name' ]; ?>">

            <!--LAST NAME-->
            <?php echo $response[ 'last_name' ] ?>
            <label for="last_name">Last Name</label>
            <input type="text"
                   name="last_name"
                   value="<?php echo $_POST[ 'last_name' ]; ?>">
        </div>

        <!--STORE SIGN UP-->
        <div id="store-account">

            <!--STORE NAME-->
            <?php echo $response[ 'store_name' ] ?>
            <label for="store_name">Store Name</label>
            <input type="text"
                   name="store_name"
                   value="<?php echo $_POST[ 'store_name' ]; ?>">

            <!--STORE LOGO-->
            <label for="store_thumb">Store Logo</label>
            <input type="file" name="store_thumb">

            <!--STORE BANNER-->
            <label for="store_banner">Store Banner</label>
            <input type="file" name="store_banner">

        </div>

        <!--SUBMIT-->
        <input type="submit" value="Sign Up">

    </form>
    
    <?php endif; ?>

</section>
        
<?php include( 'bottom.php' ); ?>