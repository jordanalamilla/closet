<?php

include( 'config.php' );
include( 'res/class.upload.php' );

function sanitize( $db, $string ) {
    $string = mysqli_real_escape_string( $db, strip_tags( trim( $string ) ) );
    return $string;
}

function initialize_cart( $user_id ) {
    if( !isset( $_SESSION[ 'cart' . $user_id ] ) ) {
        $_SESSION[ 'cart' . $user_id ] = array();
    }
}

function add_to_cart( $product, $user_id ) {
    array_push( $_SESSION[ 'cart' . $user_id ], $product );
}

function remove_from_cart( $product_id, $user_id ) {
    
    $_SESSION[ 'cart' . $user_id ][ $product_id ] = null;
    unset( $_SESSION[ 'cart' . $user_id ][ $product_id ] );
}

function generate_file_name( $store_id, $item_name, $original_file_name, $add_extension = true ) {
    
    $file_name = '';
    
    $original_file_name_parts = explode( ".", $original_file_name );
    $extension                = strtolower( $original_file_name_parts[1] );
    
//    $store_name_parts   = explode( " ", $store_name );
//    $store_name_final   = strtolower( implode( "-", $store_name_parts ) );
    
    $item_name_parts    = explode( " ", $item_name );
    $item_name_final    = strtolower( implode( "-", $item_name_parts ) );
    
    if( $add_extension ) {
        $file_name           = date( 'Y-m-d' ) . '-' .
                               time() . '-' .
                               'sid' . $store_id . '-' .
                               $item_name_final .
                               '.' . $extension;
    } else {
        $file_name           = date( 'Y-m-d' ) . '-' .
                               time() . '-' .
                               'sid' . $store_id . '-' .
                               $store_name_final . '-' .
                               $item_name_final;
    }
    
    return $file_name;
    
}

function redirect( $location = '' ) {
    header( 'Location: ' . SITE_ROOT . $location );
    die();
}

function sign_up( $db, $email, $password, $retype_password, $first_name, $last_name, $type = 0, $store_name = null, $store_thumb = null, $store_banner = null ) {
    
    //ARRAY TO STORE ERRORS AND SUCCESS MESSAGES
    $response = array();
    
    //CHECK FOR EMPTY TEXT FIELDS
    if( strlen( $email ) < 1 )              $response[ 'email' ]            = '<p class="error">Please enter an email address.</p>';
    if( strlen( $password ) < 1 )           $response[ 'password' ]         = '<p class="error">Please enter a password.</p>';
    if( strlen( $retype_password ) < 1 )    $response[ 'retype_password' ]  = '<p class="error">Please retype your password.</p>';
    if( strlen( $first_name ) < 1 )         $response[ 'first_name' ]       = '<p class="error">Please enter your first name.</p>';
    if( strlen( $last_name ) < 1 )          $response[ 'last_name' ]        = '<p class="error">Please enter your last name.</p>';
        
    //CHECK THAT PASSWORD AND RETYPE MATCH
    if( $password != $retype_password )
        $response[ 'retype_password' ]  = '<p class="error">Your passwords don\'t match, please try again.</p>';
    
    if( count( $response ) == 0 ) {

        //SANITIZE INPUTS
        $email          = sanitize( $db, $email );
        $password       = sanitize( $db, $password );
        $first_name     = sanitize( $db, $first_name );
        $last_name      = sanitize( $db, $last_name );
        
        //HASH PASSWORD
        $password = password_hash( $password, PASSWORD_DEFAULT );
        
        //ENTER NEW USER INTO DATABASE
        $insert = "INSERT INTO
                   user(user_email, user_password, user_first_name, user_last_name, user_type)
                   VALUES('$email','$password','$first_name','$last_name',$type)";
        
        $result = mysqli_query( $db, $insert )
                    or die( mysqli_error( $db ) . '<br>' . $insert );
        
    }
    
    if( $type == 1 ) {
        
        if( strlen( $store_name ) < 1 ) $response[ 'store_name' ] = '<p class="error">Please enter a store name.</p>';
        
        if( count( $response ) == 0 ) {
            
        //GET USER ID TO CREATE NEW STORE
            $query    = "SELECT * FROM user
                         WHERE user_email = '$email'";
            
            $result   = mysqli_query( $db, $query )
                            or die( mysqli_error( $db ) . '<br>' . $query );
            
            $row      = mysqli_fetch_assoc( $result );
            
            $store_user_id  = $row[ 'user_id' ];
            
        //ENTER NEW STORE INTO DATABASE (NAME ONLY)
            $insert = "INSERT INTO
                       store(store_id,store_user_id,store_name,store_thumb,store_banner)
                       VALUES('','$store_user_id','$store_name','','')";

            $result = mysqli_query( $db, $insert )
                        or die( mysqli_error( $db ) . '<br>' . $insert );
            
        //GET STORE ID FOR NEW STORE - USED TO ASSIGN THUMB AND BANNER UPON UPLOAD
            $query    = "SELECT * FROM store
                             WHERE store_user_id = '$store_user_id'";
            $result   = mysqli_query( $db, $query )
                            or die( mysqli_error( $db ) . '<br>' . $query );
            $row      = mysqli_fetch_assoc( $result );

            $store_id = $row[ 'store_id' ];
            
            $_SESSION[ 'store_id' ] = $store_id;
            
            
            
            //IF THUMB UPLOADED SUCCESSFULLY..
            $logo_file_name = upload_image( $store_thumb, 'logo' );

            //INSERT ALL OTHER TEXT DATA, ALONG WITH IMAGE FILENAME, INTO PROJECT TABLE
            $insert = "UPDATE store
                       SET store_thumb = '$logo_file_name'
                       WHERE store_id = $store_id";

            //EXECUTE INSERT
            $result = mysqli_query( $db, $insert )
                or die( mysqli_error( $db ) . '<br>' . $insert );
                    
                    
            
            //IF BANNER UPLOADED SUCCESSFULLY..
            $banner_file_name = upload_image( $store_banner, 'banner' );

            //INSERT ALL OTHER TEXT DATA, ALONG WITH IMAGE FILENAME, INTO PROJECT TABLE
            $insert = "UPDATE store
                       SET store_banner = '$banner_file_name'
                       WHERE store_id = $store_id";

            //EXECUTE INSERT
            $result = mysqli_query( $db, $insert )
                or die( mysqli_error( $db ) . '<br>' . $insert );
            
        }
        
    }
    
    return $response;
    
}

function check_login() {
    
    $logged_in = false;
    
    if( $_SESSION[ 'logged_in' ] == LOGIN_TOKEN ) {
        $logged_in = true;
    }
    
    return $logged_in;
}

function login( $db, $email, $password, $location = '' ) {
    
    //ARRAY TO STORE ERRORS AND SUCCESS MESSAGES
    $response = array();
    
    if( strlen( $email ) < 1 ) {
        
        //IF NO EMAIL ADDRESS WAS ENTERED, SEND AN ERROR
        $response[ 'email' ] = '<p class="error">Please enter an email address.</p>';
        
    } else {

        //FIND USER IN DATABASE
        $query = "SELECT * FROM user WHERE user_email='$email' LIMIT 1";
        
        $result = mysqli_query( $db, $query )
            or die( mysqli_error( $db ) . '<br>' . $query );

        //IF USER WAS FOUND..
        if( mysqli_num_rows( $result ) == 1 ) {

            $user_row = mysqli_fetch_assoc( $result );

            //..CHECK PASSWORD
            if( strlen( $password ) < 1 ) {
                
                //IF NO PASSWORD WAS ENTERED, SEND ERROR
                $response[ 'password' ] = '<p class="error">Please enter a password.</p>';
                
            } else {
                
                //IF PASSWORD WAS ENTERED, CHECK DATABASE FOR MATCH
                if( password_verify( $password, $user_row[ 'user_password' ] ) ) {

                    //SET COMMON INFO FOR CUSTOMERS AND STORES
                    $_SESSION[ 'logged_in' ]        = LOGIN_TOKEN;
                    $_SESSION[ 'user_id' ]          = $user_row[ 'user_id' ];
                    $_SESSION[ 'user_type' ]        = $user_row[ 'user_type' ];
                    $_SESSION[ 'user_name' ]        = $user_row[ 'user_first_name' ] . ' ' . $user_row[ 'user_last_name' ];
                    $_SESSION[ 'user_first_name' ]  = $user_row[ 'user_first_name' ];
                    
                    $response[ 'success' ]          = '<p class="success">Login Successful.</p>';
                    $_SESSION[ 'console' ]          = $response[ 'success' ];
                    
                    if( !$user_row[ 'user_type' ] ) {
                        
                        initialize_cart( $_SESSION[ 'user_id' ] );
                    
                    } elseif( $user_row[ 'user_type' ] ) {
                        
                        //GET STORE DATA
                        $query = "SELECT * FROM store WHERE store_user_id='{$user_row[ 'user_id' ]}' LIMIT 1";

                        $result = mysqli_query( $db, $query )
                            or die( mysqli_error( $db ) . '<br>' . $query );
                        
                        $store_row = mysqli_fetch_assoc( $result );
                        
                        //SET STORE DATA IN SESSION
                        $_SESSION[ 'store_id' ]         = $store_row[ 'store_id' ];
                        $_SESSION[ 'store_user_id' ]    = $store_row[ 'store_user_id' ];
                        $_SESSION[ 'store_name' ]       = $store_row[ 'store_name' ];
                        $_SESSION[ 'store_thumb' ]      = $store_row[ 'store_thumb' ];
                        $_SESSION[ 'store_banner' ]     = $store_row[ 'store_banner' ];
                    }
                    
                    //SEND USER BACK TO HOME PAGE
                    redirect( $location );

                } else {

                    //IF PASSWORD DOESN'T MATCH, SEND ERROR
                    $response[ 'password' ] = '<p class="error">Incorrect password.</p>';

                } //INCORRECT PASSWORD
                
            }

        } else {

            //IF NO USER IS FOUND, SEND ERROR
            $response[ 'email' ] = '<p class="error">This user does not exist: ' . $email . '</p>';

        } //INCORRECT USER
        
    }
    
    //RETURN ARRAY WITH RESPONSES
    return $response;
    
}

function generate_gallery( $db, $type, $gender = null, $id = null ) {
    
    $html = '';
    
    if( $gender == null && $id > 0 ) {
        
        $_SESSION[ 'console' ] = "id set";
        
        $query = "SELECT * FROM product WHERE product_store_id = $id";

        $result = mysqli_query( $db, $query )
                or die( mysqli_error( $db ) . '<br>' . $query );
        
        while ( $product_row = mysqli_fetch_assoc( $result ) ) {
            
            $product_name = '';
            
            if( strlen( $product_row[ 'product_name' ] ) > 15 ) {
                $product_name = substr( $product_row[ 'product_name' ], 0, 15 ) . '...';
            } else {
                $product_name = $product_row[ 'product_name' ];
            }
         
            $html .= "<a href='product.php?id={$product_row[ 'product_id' ]}'>
                        <div class='item'>
                            <ul>
                                <li><img src='images/products/{$product_row[ 'product_image' ]}' alt='{$product_row[ 'product_name' ]}' /></li>
                                <li class='product-name'>$product_name</li>
                                <li class='product-price'>$" . number_format( $product_row[ 'product_price' ], 2 ) . "</li>
                            </ul>
                        </div>
                    </a>";
            
        }
        
    } else {
    
        $query = "SELECT * FROM $type";

        $result = mysqli_query( $db, $query )
                or die( mysqli_error( $db ) . '<br>' . $query );

        switch ( $type ) {

            case "store":
                
                $_SESSION[ 'console' ] = "store selected";
                
                while ( $store_row = mysqli_fetch_assoc( $result ) ) {
                    $html .= "<a href='store.php?id=" . $store_row[ 'store_id' ] . "'>
                                <div class='item'>
                                    <ul>
                                        <li><img src='images/thumbs/" . $store_row[ 'store_thumb' ] . "' alt='" . $store_row[ 'store_name' ] . "' /></li>
                                        <li class='product-name'>" . $store_row[ 'store_name' ] . "</li>
                                    </ul>
                                </div>
                            </a>";
                }
            break;

            case "product":
                
                $_SESSION[ 'console' ] = "product selected";
                
                if( $gender == 'male' ) {

                    while ( $product_row = mysqli_fetch_assoc( $result ) ) {
            
                        $product_name = '';

                        if( strlen( $product_row[ 'product_name' ] ) > 15 ) {
                            $product_name = substr( $product_row[ 'product_name' ], 0, 15 ) . '...';
                        } else {
                            $product_name = $product_row[ 'product_name' ];
                        }

                        if( $product_row[ 'product_gender' ] == 'M' ) {

                            $html .= "<a href='product.php?id={$product_row[ 'product_id' ]}'>
                                    <div class='item'>
                                        <ul>
                                            <li><img src='images/products/{$product_row[ 'product_image' ]}' alt='{$product_row[ 'product_name' ]}' /></li>
                                            <li class='product-name'>$product_name</li>
                                            <li class='product-price'>$" . number_format( $product_row[ 'product_price' ], 2 ) . "</li>
                                        </ul>
                                    </div>
                                </a>";

                        }

                    }

                } elseif( $gender == 'female' ) {

                    while ( $product_row = mysqli_fetch_assoc( $result ) ) {
            
                        $product_name = '';

                        if( strlen( $product_row[ 'product_name' ] ) > 15 ) {
                            $product_name = substr( $product_row[ 'product_name' ], 0, 15 ) . '...';
                        } else {
                            $product_name = $product_row[ 'product_name' ];
                        }

                        if( $product_row[ 'product_gender' ] == 'F' ) {

                            $html .= "<a href='product.php?id={$product_row[ 'product_id' ]}'>
                                    <div class='item'>
                                        <ul>
                                            <li><img src='images/products/{$product_row[ 'product_image' ]}' alt='{$product_row[ 'product_name' ]}' /></li>
                                            <li class='product-name'>$product_name</li>
                                            <li class='product-price'>$" . number_format( $product_row[ 'product_price' ], 2 ) . "</li>
                                        </ul>
                                    </div>
                                </a>";

                        }

                    }

                } else {
                    
                    $_SESSION[ 'console' ] = "all selected";

                    while ( $product_row = mysqli_fetch_assoc( $result ) ) {
            
                        $product_name = '';

                        if( strlen( $product_row[ 'product_name' ] ) > 15 ) {
                            $product_name = substr( $product_row[ 'product_name' ], 0, 15 ) . '...';
                        } else {
                            $product_name = $product_row[ 'product_name' ];
                        }

                        $html .= "<a href='product.php?id={$product_row[ 'product_id' ]}'>
                                <div class='item'>
                                    <ul>
                                        <li><img src='images/products/{$product_row[ 'product_image' ]}' alt='{$product_row[ 'product_name' ]}' /></li>
                                        <li class='product-name'>$product_name</li>
                                        <li class='product-price'>$" . number_format( $product_row[ 'product_price' ], 2 ) . "</li>
                                    </ul>
                                </div>
                            </a>";

                    }

                }
            break;
        }
    }
    
    return $html;
    
}

function get_product_data( $db, $id ) {
    
    $data = [];
    
    $query = "SELECT * FROM product WHERE product_id=$id LIMIT 1";
        
    $result = mysqli_query( $db, $query )
        or die( mysqli_error( $db ) . '<br>' . $query );
    
    $product_row = mysqli_fetch_assoc( $result );
    
    foreach( $product_row as $item ) {
         array_push( $data, $item );
    }
    
    return $data;
    
}

function get_store_data( $db, $id ) {
    
    $data = [];
    
    $query = "SELECT store_id, store_name, store_banner FROM store WHERE store_id=$id LIMIT 1";
        
    $result = mysqli_query( $db, $query )
        or die( mysqli_error( $db ) . '<br>' . $query );
    
    $store_row = mysqli_fetch_assoc( $result );
    
    foreach( $store_row as $item ) {
         array_push( $data, $item );
    }
    
    //PRODUCTS-----------------------
    
    $products = [];
    
    $query = "SELECT * FROM product WHERE product_store_id=$id";

    $result = mysqli_query( $db, $query )
            or die( mysqli_error( $db ) . '<br>' . $query );
    
    while( $product_row = mysqli_fetch_assoc( $result ) ) {

        array_push( $products, "<a href='product.html'>
                    <div class='product'>
                        <ul>
                            <li><img src='images/products/{$product_row[ 'product_image' ]}' alt='{$product_row[ 'product_name' ]}' /></li>
                            <li class='black button product-name'>{$product_row[ 'product_name' ]}</li>
                            <li class='product-price'>{$product_row[ 'product_price' ]}</li>
                        </ul>
                    </div>
                </a>" );
        
    }
    
    array_push( $data, $products );
    
    return $data;
    
}

function add_product( $db, $store_id, $image, $name, $gender, $price ) {
    
    //ARRAY TO STORE ERRORS AND SUCCESS MESSAGES
    $response = array();

    //CHECK EACH VARIABLE FOR EMPTINESS
    //IF EMPTY, SEND ERROR
    if( strlen( $name ) < 1 )   $response[ 'name' ]     = '<p class="response">Please enter a name.</p>';
    if( strlen( $gender ) < 1 ) $response[ 'gender' ]   = '<p class="response">Please choose a gender.</p>';
    if( strlen( $price ) < 1 )  $response[ 'price' ]    = '<p class="response">Please enter price.</p>';


    //IF NO ERRORS..
    if( count( $response ) == 0 ) {

        //..SANTIZE ALL INPUTS
        $name   = sanitize( $db, $name );
        $gender = sanitize( $db, $gender );
        $price  = sanitize( $db, $price );

        //IF IMAGE UPLOADED SUCCESSFULLY..
        $image_file_name = upload_image( $image, 'product', $name );

        //INSERT ALL OTHER TEXT DATA, ALONG WITH IMAGE FILENAME, INTO PROJECT TABLE
        $insert = "INSERT INTO
                   product(product_store_id, product_name, product_gender, product_price, product_image)
                   VALUES($store_id,'$name','$gender',$price,'$image_file_name')";

        //EXECUTE INSERT
        $result = mysqli_query( $db, $insert )
            or die( mysqli_error( $db ) . '<br>' . $insert );
    }
    
    return $response;
}

function update_banner( $db, $store_id, $image ) {

    //UPLOAD AND RESIZE IMAGE
    $image_file_name = upload_image( $_FILES[ 'image' ], 'banner' );

    //INSERT ALL OTHER TEXT DATA, ALONG WITH IMAGE FILENAME, INTO PROJECT TABLE
    $insert = "UPDATE store SET store_banner='$image_file_name' WHERE store_id=$store_id";

    //EXECUTE INSERT
    $result = mysqli_query( $db, $insert )
        or die( mysqli_error( $db ) . '<br>' . $insert );
    
}

function upload_image( $image_to_upload, $type, $name = null ) {
    
    //NEW INSTANCE OF UPLOAD
    $image = new upload( $image_to_upload );
    
    switch( $type ) {
            
        case "banner":
            
            //GENERATE FILENAME
            $image_file_name = generate_file_name( $_SESSION[ 'store_id' ],
                                                   'banner',
                                                   $image_to_upload[ 'name' ],
                                                   false );
            if( $image->uploaded ) {

                $aspect_ratio = $image->image_src_x / 1000;                          //GET ASPECT RATIO OF RESIZED IMAGE
                $y_crop = ( ( ( $image->image_src_y / $aspect_ratio ) - 240 ) / 2); //CALCULATE PIXELS TO CROP (T&B) TO GET 240 HEIGHT AFTER RESIZE

                $image->file_new_name_body      = $image_file_name;          //SET THE NEW FILE NAME
                $image->image_resize            = true;                     //DECLARE IMAGE WILL BE RESIZED
                $image->image_x                 = 1000;                    //RESIZE WIDTH IN PIXELS
                $image->image_ratio_y           = true;                   //MAINTAIN ASPECT RATIO FOR HEIGHT
                $image->image_crop              = array( $y_crop, 0 );   //CROP THE IMAGE
                $image->process( '../closet/images/banner/' );          //START PROCESSING IMAGE, UPLOAD TO DESTINATION

                if( $image->processed ) {
                    echo 'image resized.';
                    $image->clean();
                } else {
                    echo 'error:' . $image->error;
                }
            } break;
            
        case "logo":
            
            //GENERATE FILENAME
            $image_file_name = generate_file_name( $_SESSION[ 'store_id' ],
                                                   'logo',
                                                   $image_to_upload[ 'name' ],
                                                   false );
            if( $image->uploaded ) {

                $aspect_ratio = $image->image_src_x / 300;                           //GET ASPECT RATIO OF RESIZED IMAGE
                $y_crop = ( ( ( $image->image_src_y / $aspect_ratio ) - 300 ) / 2); //CALCULATE PIXELS TO CROP (T&B) TO GET 240 HEIGHT AFTER RESIZE

                $image->file_new_name_body      = $image_file_name;          //SET THE NEW FILE NAME
                $image->image_resize            = true;                     //DECLARE IMAGE WILL BE RESIZED
                $image->image_x                 = 300;                     //RESIZE WIDTH IN PIXELS
                $image->image_ratio_y           = true;                   //MAINTAIN ASPECT RATIO FOR HEIGHT
                $image->image_crop              = array( $y_crop, 0 );   //CROP THE IMAGE
                $image->process( '../closet/images/thumbs/' );          //START PROCESSING IMAGE, UPLOAD TO DESTINATION

                if( $image->processed ) {
                    echo 'image resized.';
                    $image->clean();
                } else {
                    echo 'error:' . $image->error;
                }
            } break;
            
        case "product":
            
            //GENERATE FILENAME
            $image_file_name = generate_file_name( $_SESSION[ 'store_id' ],
                                                   $name,
                                                   $image_to_upload[ 'name' ],
                                                   false );
            if( $image->uploaded ) {

                $aspect_ratio = $image->image_src_x / 400;                           //GET ASPECT RATIO OF RESIZED IMAGE
                $y_crop = ( ( ( $image->image_src_y / $aspect_ratio ) - 400 ) / 2); //CALCULATE PIXELS TO CROP (T&B) TO GET 240 HEIGHT AFTER RESIZE

                $image->file_new_name_body      = $image_file_name;          //SET THE NEW FILE NAME
                $image->image_resize            = true;                     //DECLARE IMAGE WILL BE RESIZED
                $image->image_x                 = 400;                     //RESIZE WIDTH IN PIXELS
                $image->image_ratio_y           = true;                   //MAINTAIN ASPECT RATIO FOR HEIGHT
                $image->image_crop              = array( $y_crop, 0 );   //CROP THE IMAGE
                $image->process( '../closet/images/products/' );        //START PROCESSING IMAGE, UPLOAD TO DESTINATION

                if( $image->processed ) {
                    echo 'image resized.';
                    $image->clean();
                } else {
                    echo 'error:' . $image->error;
                }
            } break;
        }
    
    return $image->file_dst_name;
    
}

function get_footer_stores( $db ) {
    
    $stores = array();
    
    $query = "SELECT * FROM store";

    $result = mysqli_query( $db, $query )
            or die( mysqli_error( $db ) . '<br>' . $query );

    while ( $row = mysqli_fetch_assoc( $result ) ) {
     
        $store = array(
            'store_name' => $row[ 'store_name' ],
            'store_id'   => $row[ 'store_id' ]
        );
        
        array_push( $stores, $store );
        
    }
    
    return $stores;
    
}