        <footer>
            
            <?php $stores = get_footer_stores( $db ); ?>
            
            <section id="footer-content">
            
                <div class="footer-column">
                
                    <ul>
                        <li id="logo"><a href="index.php">CLOSET!</a></li>
                        <li><a href="gallery.php?type=product&gender=male">Shop Men</a></li>
                        <li><a href="gallery.php?type=product&gender=female">Shop Women</a></li>
                    </ul>
                    
                </div>
                
                <div class="footer-column">
                
                    <h3>Stores</h3>
                    
                    <ul>
                    
                        <?php
                        
                        foreach( $stores as $store ) {
                            echo "<li><a href='store.php?id={$store[ 'store_id' ]}'>{$store[ 'store_name' ]}</a></li>";
                        }
                        
                        ?>
                    
                    </ul>
                    
                </div>
                
                <div class="footer-column">
                
                    
                
                </div>
            
            </section>
            
            <?php
//            echo '<pre>';
//            print_r( $_SESSION );
//            echo '</pre>';
//            
//            echo '<pre>';
//            print_r( $_GET );
//            echo '</pre>';
//            
//            echo '<pre>';
//            print_r( $_POST );
//            echo '</pre>';
            ?>
            
        </footer>
        
    </body>
</html>