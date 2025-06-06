<?php
checkUserSession();
$user_session_data = getSessionData();
?>
<script>
$(document).ready(function(){
    $('.sidebar-menu > .submenu').hover(function(){
        $('.submenu').parent('ul.sidebar-menu').addClass('ul-hover');
        })
    $('.sidebar-menu > .submenu').mouseleave(function(){
        $('.submenu').parent('ul.sidebar-menu').removeClass('ul-hover');
        })
        
    $( "ul.sidebar-menu" ).children('li').on('click',function(){
        var temAtt = $(this).attr('class');
        $('.menu-level-1').removeClass('menu-level-1');
        if(temAtt.search("menu-level-1") == -1){
            $(this).addClass('menu-level-1');
        }
    });
   
    $( "ul.sidebar-menu > li > ul" ).children('li').on('click',function(){
        var temAtt = $(this).attr('class');
        $('.menu-level-2').removeClass('menu-level-2');
        if(temAtt.search("menu-level-2") == -1){
            $(this).addClass('menu-level-2');
        }
        $( "ul.sidebar-menu > li" ).removeClass('menu-level-1');
    });

    $( "ul.sidebar-menu > li > ul > li > ul" ).children('li').on('click',function(){
        var temAtt = $(this).attr('class');
        $('.menu-level-3').removeClass('menu-level-3');
        if(temAtt.search("menu-level-3") == -1){
            $(this).addClass('menu-level-3');
        }
        $( "ul.sidebar-menu > li > ul li" ).removeClass('menu-level-2');
    });
    
})
</script>

<?php
$manage_shipping_company = checkLabelByTask('manage_shipping_company');
$manage_ships = checkLabelByTask('manage_ships');
$manage_crew_member = checkLabelByTask('manage_crew_member');
$manage_vendor = checkLabelByTask('manage_vendor');
$manage_agent = checkLabelByTask('manage_agent');
$manage_product_category = checkLabelByTask('manage_product_category');
$manage_product = checkLabelByTask('manage_product');
$manage_product_group = checkLabelByTask('manage_product_group');
$manage_extra_meal = checkLabelByTask('manage_extra_meal');
$manage_condemned_stock = checkLabelByTask('manage_condemned_stock');
$victualing_report = checkLabelByTask('victualing_report');
$manage_transaction = checkLabelByTask('manage_transaction');
$manage_sales = checkLabelByTask('manage_sales');
$manage_purchase = checkLabelByTask('manage_purchase');
$manage_user = checkLabelByTask('manage_user');
$manage_role = checkLabelByTask('manage_role');
$company_360_view = checkLabelByTask('company_360_view');
$meat_consumption_report = checkLabelByTask('meat_consumption_report');
$purchase_order_list = checkLabelByTask('purchase_order_list');
$manage_newsletter = checkLabelByTask('manage_newsletter');
$manage_food_menu = checkLabelByTask('manage_food_menu');
$manage_food_recipe = checkLabelByTask('manage_food_recipe');
$manage_nutrition_report = checkLabelByTask('manage_nutrition_report');
?>

<aside id="sidebar-left" class="sidebar-circle">
    <!-- /.sidebar-content --> 
    <ul id="tour-9" class="sidebar-menu" >
        <?php 
        if($user_session_data->code == 'captain' || $user_session_data->code == 'cook' || $user_session_data->code == 'shipping_company'){
               $view_360_url = base_url().'shipping/shippingCompanyDetails/'.base64_encode($user_session_data->shipping_company_id);
         }
         else{
           $view_360_url = base_url().'shipping/shippingCompanyDetails?cmi=MzI=';
         }
        ?>

        <li class="submenu <?php echo (isset($active) && ($active) == 'DASH' ) ? 'active' : '' ?>"> <a href="<?php echo base_url().'user/user_dashboard?cmi=MzI=';?>"> <span class="icon"><i class="fas fa-home"></i></span> <span class="text">Dashboard</span> </a>
        </li> 
        
        <?php
         if($user_session_data->code=='captain' || $user_session_data->code=='cook'){
            if($manage_crew_member){
          ?>
           <li class="submenu <?php echo (isset($active) && ($active) == 'CML' ) ? 'active' : '' ?>"> <a href="<?php echo base_url().'shipping/crewEnteriesList?cmi=MzI=';?>"> <span class="icon"><i class="fa fa-address-book" aria-hidden="true"></i></span> <span class="text">Crew Members</span> </a></li>
         <?php }
           }
        ?> 

        <?php 
         if($company_360_view){
            ?>
         <li class="submenu <?php echo (isset($active) && ($active) == '360C' ) ? 'active' : '' ?>"> <a href="<?php echo $view_360_url;?>"> <span class="icon"><i class="fa fa-compass"></i></span> <span class="text">360 Company</span> </a></li>     
        <?php }
        ?>

        <?php 
        if($manage_shipping_company || $manage_ships){
            ?>
        <li class="submenu <?php echo (isset($active) && ($active) == 'CL' || ($active) == 'SP' ) ? 'active' : '' ?> "><a class="" href="javascript:void(0);"><span class="icon"><i class="fa fa-ship"></i>
        </span><span class="text">Shipping</span><span class="arrow <?php echo (isset($active) && ($active) == 'CL' || ($active) == 'SP' || ($active) == '360C') ? 'open' : '' ?>"></span></a>
         <ul>
          <?php 
           if($manage_shipping_company){
            ?>  
            <li class="<?php echo (isset($active) && ($active) == 'CL' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'shipping/index?cmi=MzI=';?>"><span class="icon">  <i class="glyphicon"></i></span><span class="text">Shipping Company</span></a> </li>
            <?php 
           }
            if($manage_ships){
                ?>
            <li class="<?php echo (isset($active) && ($active) == 'SP' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'shipping/manageShips?cmi=MzI=';?>"><span class="icon">  <i class="glyphicon"></i></span><span class="text">Vessel List</span></a> </li>
            <?php 
              }
            ?>
        </ul>
        </li>
    <?php } 

     if($manage_vendor){
    ?>
        <li class="submenu <?php echo (isset($active) && ($active) == 'VL' || ($active) == 'VO' ) ? 'active' : '' ?> "><a class="" href="javascript:void(0);"><span class="icon"><i class="fas fa-user-friends"></i>
        </span><span class="text">Vendor</span><span class="arrow <?php echo (isset($active) && ($active) == 'VL' || ($active) == 'VO' ) ? 'open' : '' ?>"></span></a>
        <ul>
        <li class="<?php echo (isset($active) && ($active) == 'VL' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'vendor/index?cmi=MzI=';?>"><span class="icon">  <i class="glyphicon"></i></span><span class="text">Vendor List</span></a> </li>
        </ul>
        </li>
        <?php 
       }
    ?>

      <?php 
       if($manage_agent){
      ?>
      <li class="submenu <?php echo (isset($active) && ($active) == 'AG' || ($active) == 'AG' ) ? 'active' : '' ?> ">
          <a class="" href="javascript:void(0);"><span class="icon"><i class="fas fa-user-friends"></i>
        </span><span class="text">Agent</span><span class="arrow <?php echo (isset($active) && ($active) == 'AG' || ($active) == 'AG' ) ? 'open' : '' ?>"></span></a>
        <ul>
          <li class="<?php echo (isset($active) && ($active) == 'AG' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'user/agent_list?cmi=MzI=';?>"><span class="icon">  <i class="glyphicon"></i></span><span class="text">Agent List</span></a> </li>
        </ul>
      </li>
     <?php } 
     ?>

    <?php
    if($manage_product || $manage_product_category || $manage_product_group)
    {
    ?>
        <li class="submenu <?php echo (isset($active) && ($active == 'PC') || ($active == 'PD' ) || ($active == 'PI' )) ? 'active' : '' ?> "><a class="" href="javascript:void(0);"><span class="icon"><i class="fas fa-leaf"></i>
        </span><span class="text">Product</span><span class="arrow <?php echo (isset($active) && ($active == 'PC' || $active == 'PD' || $active == 'PI'  )  ) ? 'open' : '' ?>"></span></a>
        <ul>
            <?php 
            if($manage_product_category){
             ?>
             <li class="<?php echo (isset($active) && ($active) == 'PC' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'product/product_category?cmi=MzI='?>"><span class="icon">  <i class="glyphicon"></i></span><span class="text">Product Category</span></a> </li>
           <?php }

            if($manage_product){
                ?>
            <li class="<?php echo (isset($active) && ($active) == 'PD' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'product/product?cmi=MzI=';?>"><span class="icon">  <i class="glyphicon"></i></span><span class="text">Product List</span></a> </li>
            <?php }

            if($manage_product_group){
             ?>
              <li class="<?php echo (isset($active) && ($active) == 'PI' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'product/productGroup?cmi=MzI=';?>"><span class="icon">  <i class="glyphicon"></i></span><span class="text">Product Group</span></a> </li>
            <?php }
            ?>            
        </ul>
        </li>
        <?php
          }

         if($manage_extra_meal || $manage_condemned_stock || $victualing_report || $meat_consumption_report || $purchase_order_list || $manage_newsletter || $manage_food_menu || $manage_food_recipe || $manage_nutrition_report){
          ?>
          
          <li class="submenu <?php echo (isset($active) && ($active == 'EM') || ($active == 'CSR' ) || ($active == 'VCR' ) || ($active == 'POL' ) || ($active == 'MR' ) || ($active == 'NEWS') || ($active == 'FM') || ($active == 'NR') || ($active == 'FR')) ? 'active' : '' ?> "><a class="" href="javascript:void(0);"><span class="icon"><i class="fas fa-file"></i>
        </span><span class="text">Reports</span><span class="arrow <?php echo (isset($active) && ($active == 'EM' || $active == 'CSR' || $active == 'VCR'  || $active=='POL' || $active=='MR' || $active == 'NEWS' || $active == 'FM' || $active == 'NR' || $active == 'FR')  ) ? 'open' : '' ?>"></span></a>
         <ul>
            <?php
            if($manage_nutrition_report){ 
            ?>
             <li class="<?php echo (isset($active) && ($active) == 'NR' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'report/nutrition_report';?>"><span class="icon">  <i class="glyphicon"></i></span><span class="text">Nutrition Report</span></a> </li>

            <?php
                }
            if($manage_newsletter){ 
            ?>
                <li class="<?php echo (isset($active) && ($active) == 'NEWS' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'news?cmi=MzI=';?>"><span class="icon">  <i class="glyphicon"></i></span><span class="text">News</span></a> </li>
         <?php
            }

           if($manage_food_menu){
            ?>

            <li class="<?php echo (isset($active) && ($active) == 'FM' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'food_menu?cmi=MzI=';?>"><span class="icon">  <i class="glyphicon"></i></span><span class="text">Food Menu</span></a> </li>


           <?php }             
            if($manage_food_recipe){
           ?> 
           
           <li class="<?php echo (isset($active) && ($active) == 'FR' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'food_menu/food_recipe';?>"><span class="icon">  <i class="glyphicon"></i></span><span class="text">Food Recipe</span></a> </li>

         <?php
            } 
            if($purchase_order_list){
         ?>
          <li class="<?php echo (isset($active) && ($active) == 'POL' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url();?>report/purchase_order_list"><span class="icon">  <i class="glyphicon"></i></span><span class="text">Purchase Orders</span></a> </li>
          <?php
            }
          if($manage_extra_meal){ 
          ?>
          <li class="<?php echo (isset($active) && ($active) == 'EM' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url();?>shipping/extra_meals_report"><span class="icon">  <i class="glyphicon"></i></span><span class="text">Extra Meals</span></a> </li>
         <?php } 
          if($manage_condemned_stock){
            ?>
          <li class="<?php echo (isset($active) && ($active) == 'CSR' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url();?>Report/condemnedStockReport" ><span class="icon">  <i class="glyphicon"></i></span><span class="text">Condemned Stock</span></a> </li>
          <?php }
           if($victualing_report){   
           ?>
          <li class="<?php echo (isset($active) && ($active) == 'VCR' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'shipping/victualling_summary_report?CMI=1'?>" ><span class="icon">  <i class="glyphicon"></i></span><span class="text">Victualing Summary</span></a> </li> 
          <?php } 
           if($meat_consumption_report){
          ?> 
           <li class="<?php echo (isset($active) && ($active) == 'MR' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'report/meat_report?CMI=1'?>" ><span class="icon">  <i class="glyphicon"></i></span><span class="text">Meat consumption</span></a> </li> <?php
            } 
           ?>          
        </ul>
        </li>
          <?php 
        }

       if($manage_transaction || $manage_sales || $manage_purchase){   
        ?>
         <li class="submenu <?php echo (isset($active) && ($active) == 'VI' || ($active) == 'CI' ||  ($active) == 'TH' ) ? 'active' : '' ?> "><a class="" href="javascript:void(0);"><span class="icon"><i class="fas fa-comments-dollar"></i>
        </span><span class="text">Financial Transactions</span><span class="arrow <?php echo (isset($active) && ($active) == 'VI' || ($active) == 'CI' ||  ($active) == 'TH') ? 'open' : '' ?>"></span></a>
        <ul>

            <?php if($manage_purchase){?>
            <li class="<?php echo (isset($active) && ($active) == 'VI' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'vendor/vendor_invoice?cmi=MzI=';?>"><span class="icon">  <i class="glyphicon"></i></span><span class="text">Purchase List</span></a> </li>
            <?php }
            if($manage_sales){?>

            <li class="<?php echo (isset($active) && ($active) == 'CI' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'report/companyInvoices?cmi=MzI=';?>"><span class="icon">  <i class="glyphicon"></i></span><span class="text">Sale List</span></a> </li>
            <?php }
            if($manage_transaction){?>
            <li class="<?php echo (isset($active) && ($active) == 'TH' ) ? 'active' : '' ?>"><a class=""  href="<?php echo base_url().'report/transaction_history?cmi=MzI=';?>"><span class="icon">  <i class="glyphicon"></i></span><span class="text">Transaction List</span></a> </li>
            <?php } ?>
         </ul>
         </li>
         <?php }
         if($manage_user || $manage_role){
         ?>
          <li class="submenu <?php echo (isset($active) && ($active) == 'UM' || ($active) == 'RM') ? 'active' : '' ?> "><a class="" href="javascript:void(0);"><span class="icon"><i class="fas fa-tools"></i>
        </span><span class="text">Admin</span><span class="arrow <?php echo (isset($active) && ($active) == 'UM' || ($active) == 'RM' ) ? 'open' : '' ?>"></span></a>
        <ul>
        <?php 
            if($manage_user){
                ?>
            <li class="<?php echo (isset($active) && ($active) == 'UM' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'user/user_list/'.base64_encode($user_session_data->user_id);?>"><span class="icon">  <i class="glyphicon"></i></span><span class="text">User List</span></a> </li>
           <?php } 
            if($manage_role){?>
            <li class="<?php echo (isset($active) && ($active) == 'RM' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'user/role_manager';?>"><span class="icon">  <i class="glyphicon"></i></span><span class="text">Roles & Permissions</span></a> </li>
            <?php }
            ?>
        </ul>
        </li> 
        <?php 
           } 
            
         if($user_session_data->code=='vendor'){
          ?>
           <li class="submenu <?php echo (isset($active) && ($active) == 'OL' ) ? 'active' : '' ?>"> <a href="<?php echo base_url().'vendor/vendor_order?cmi=MzI=';?>"> <span class="icon"><i class="fa fa-shopping-cart"></i></span> <span class="text">RFQ List</span> </a></li> 
           <li class="submenu <?php echo (isset($active) && ($active) == 'PO' ) ? 'active' : '' ?>"> <a href="<?php echo base_url().'vendor/vendor_po?cmi=MzI=';?>"> <span class="icon"><i class="fa fa-tasks"></i></span> <span class="text">PO List</span> </a></li>
            <li class="<?php echo (isset($active) && ($active) == 'VI' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'vendor/vendor_invoice?cmi=MzI=';?>"><span class="icon"><i class="fa fa-tags" aria-hidden="true"></i></span><span class="text">Invoice List</span></a></li>
            <li class="<?php echo (isset($active) && ($active) == 'IV' ) ? 'active' : '' ?>"><a class="" href="<?php echo base_url().'vendor/invoice_transaction?cmi=MzI=';?>"><span class="icon"><i class="fas fa-comments-dollar"></i></span><span class="text">Transaction List</span></a></li>
          <?php 
           } 
        ?>
    </ul>
    <!-- /.sidebar-footer --> 
    <!--/ End left navigation - footer --> 
</aside>

<!-- /#sidebar-left --> 
<!--------- Scroll menu ----------->
<script>
$(document).ready(function(){
  // Add smooth scrolling to all links
  $(".submenu a").on('click', function(event) {

    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {
      // Prevent default anchor click behavior
      event.preventDefault();

      // Store hash
      var hash = this.hash;
      

      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
      $('html #sidebar-left, body #sidebar-left').animate({
        scrollTop: $(hash).offset().top
      }, 1000, function(){
   
        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;


    if(hash != ''){
        // Show the hash if it's set
       

        // Clear the hash in the URL
        location.hash = '';
    }
        
        
      });
    } // End if
  });
});


</script>        
        