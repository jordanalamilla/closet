<nav id="desktop-nav">
    <ul id="navigation">
        <li id="logo"><a href="index.php">CLOSET!</a></li>

        <ul id="links">
            <li class="link"> <a href="gallery.php?type=product&gender=male">Men</a> </li>
            <li class="link"> <a href="gallery.php?type=product&gender=female">Women</a> </li>
            <li class="link"> <a href="gallery.php?type=store">Stores</a> </li>
        </ul>

        <?php if( !$_SESSION[ 'logged_in' ] == LOGIN_TOKEN ): ?>
        
        <!--IF NOT LOGGED IN-->
        <li class="account" id="sign-up"> <a class="black button" href="form.php?type=sign_up">Sign Up</a> </li>
        <li class="account"> <a href="login.php">Login</a> </li>
        
        <?php else: ?>
        
            <!--IF LOGGED IN-->
            <li class="account" id="sign-up"> <a class="black button" href="logout.php">Logout</a> </li>
        
            <?php if( !$_SESSION[ 'user_type' ] ): ?>

                <!--IF LOGGED IN AS CUSTOMER-->
               <li class="account">
                    <a href="cart.php">
                        <?php echo $_SESSION[ 'user_first_name' ] ?>'s Cart
                    </a>
                </li>

                <?php else: ?>

                <!--IF LOGGED IN AS STORE-->
                <li class="account" id="store-nav-link">
                    <a href="store.php?type=product&id=<?php echo $_SESSION[ 'store_id' ]; ?>">
                        <?php echo $_SESSION[ 'store_name' ] ?>
                    </a>
                    
                    <ul id="store-options-list">
                        <li><a href="form.php?type=add_product">Add New Product</a></li>
                        <li><a href="form.php?type=update_banner">Update Banner</a></li>
                    </ul>
                    
                </li>

            <?php endif; ?>
        
        <?php endif; ?>
        
    </ul>
</nav>

<!--MOBILE NAV-->

<nav id="mobile-nav">
    
    <button id="mobile-nav-button">CLOSET!</button>
    
    <ul id="mobile-nav-links">

        <li><a href="index.php">Browse All</a></li>
        <li> <a href="gallery.php?type=product&gender=male">Men</a> </li>
        <li> <a href="gallery.php?type=product&gender=female">Women</a> </li>
        <li> <a href="gallery.php?type=store">Stores</a> </li>

        <?php if( !$_SESSION[ 'logged_in' ] == LOGIN_TOKEN ): ?>
        
        <!--IF NOT LOGGED IN-->
        <li> <a href="form.php?type=sign_up">Sign Up</a> </li>
        <li> <a href="login.php">Login</a> </li>
        
        <?php else: ?>
        
            <!--IF LOGGED IN-->
            <li> <a href="logout.php">Logout</a> </li>
        
            <?php if( !$_SESSION[ 'user_type' ] ): ?>

                <!--IF LOGGED IN AS CUSTOMER-->
                <li>
                    <a href="cart.php">
                        <?php echo $_SESSION[ 'user_first_name' ] ?>'s Cart
                    </a>
                </li>

            <?php else: ?>

                <!--IF LOGGED IN AS STORE-->
                <li>
                    <a href="store.php?type=product&id=<?php echo $_SESSION[ 'store_id' ]; ?>">
                        <?php echo $_SESSION[ 'store_name' ] ?>
                    </a>
                </li>
                    
                <li><a href="form.php?type=add_product">Add New Product</a></li>
        
                <li><a href="form.php?type=update_banner">Update Banner</a></li>

            <?php endif; ?>
        
        <?php endif; ?>
        
    </ul>
</nav>