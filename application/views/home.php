<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo $this->config->item('title');?></title>
   <meta name="description" content="1North">
    <meta name="keywords" content="1North">
    <meta name="author" content="1North">
    <link href="<?php echo  base_url('assets/images/favicon.png')?>" rel="icon">
  <link rel="stylesheet" href="<?php echo base_url('assets/vendor/main.css')?>" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
      var base_url = '<?php echo base_url();?>';
    </script>
</head>
<body>

    <div class="preloader" id="preloader" style="display:none;">
    <div class="spinner"></div>
  </div>


  <section class="values-section">
  <div class="logo">
    <img src="<?php echo base_url('assets/images/company_logo.png')?>" alt="Logo">
  </div>
  <div class="underline"></div>
  <div class="values-grid">
    <div class="value-card">
      <div class="icon">
        <i class="fa-solid fa-utensils" style="color: #126d6c;"></i>
      </div>
      <h2>Catering</h2>
      <button type="button" onclick="showLoginModel('captain,cook,shipping_company,vendor,catering_manager,asst_purchase_manager')">Log In</button>
    </div>
    <div class="value-card">
      <div class="icon">
        <i class="fa-solid fa-file-invoice-dollar" style="color: #126d6c;"></i>
      </div>
      <h2>Purchase</h2>
      <button type="button" onclick="showLoginModel('purchase_executive')">Log In</button>
    </div>
    <div class="value-card">
      <div class="icon">
        <i class="fa-solid fa-people-group" style="color: #126d6c;"></i>
      </div>
      <h2>Crew</h2>
      <button>Log In</button>
    </div>
    <div class="value-card">
      <div class="icon">
        <i class="fa-solid fa-notes-medical" style="color: #126d6c;"></i>
      </div>
      <h2>QHSE</h2>
      <button>Log In</button>
    </div>
    <div class="value-card">
      <div class="icon">
        <i class="fa-solid fa-gears" style="color: #126d6c;"></i>
      </div>
      <h2>PMS</h2>
      <button>Log In</button>
    </div>
    <div class="value-card">
      <div class="icon">
        <i class="fa-solid fa-calculator"style="color: #126d6c;"></i>
      </div>
      <h2>Accounts</h2>
      <button>Log In</button>
    </div>
    <div class="value-card">
      <div class="icon">
        <i class="fa-solid fa-circle-user" style="color: #126d6c;"></i>
      </div>
      <h2>HR</h2>
      <button>Log In</button>
    </div>
    <div class="value-card">
      <div class="icon">
        <i class="fa-solid fa-user-tie" style="color: #126d6c;"></i>
      </div>
      <h2>Admin</h2>
      <button type="button" onclick="showLoginModel('super_admin')">Log In</button>
    </div>
  </div>
  <footer style="background: #236369; padding: 50px 0; color: white;">
    <div class="container">
      <!-- Main flex row -->
      <div class="d-flex justify-content-between align-items-center flex-wrap">
  
        <!-- Left: Connect With Us -->
        <div class="footer-left" style="min-width: 200px;">
          <h3 style="color: white; margin-bottom: 10px;">Connect With Us</h3>
          <div class="d-flex gap-2">
            <a href="#" class="btn btn-outline-light"><i class="bi bi-phone"></i></a>
            <a href="#" class="btn btn-outline-light"><i class="bi bi-globe"></i></a>
            <a href="#" class="btn btn-outline-light"><i class="bi bi-envelope"></i></a>
            <a href="https://www.linkedin.com/company/one-north-ships/" class="btn btn-outline-light"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
  
        <!-- Center: 1North Title -->
        <div class="footer-center text-center">
          <h2 style="color: white; font-family: 'Arial', sans-serif; font-weight: bold; margin: 0;">
            1North
          </h2>
        </div>
  
        <!-- Right: Image -->
        <div class="footer-right">
          <img src="<?php echo base_url('assets/images/certification.jpeg')?>" alt="Certification" style="height: 60px; object-fit: contain;">
        </div>
  
      </div>
    </div>
  
    <!-- Bottom copyright section -->
<div style="background: white; text-align: center; padding: 20px 0; margin: 20px 50px 0; border-radius: 3px;">
  <div class="container">
    <h3><p style="margin: 0; color: #000;">&copy; 2024 All Rights Reserved By <strong>One North Ships</strong>.</p></h3>
  </div>
</div>
  </footer>

  <!-- Modal HTML -->
    <div class="modal fade modal-primary custom-modal2" id="centeredModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true" >
      <div class="modal-dialog modal-dialog-centered" style="width:100%"> <!-- Vertically centered -->
        <div class="modal-content">
          <div class="modal-header" style="background-color: #006d77;color: white;">
            <h5 class="modal-title" id="modalTitle">Login</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="modal_content">
          </div>
<!--           <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div> -->
        </div>
      </div>
    </div>
 </section>

 <script>
  function showLoginModel(id,second_id)
    { 
      $.ajax({
        beforeSend: function(){
            $("#preloader").show();
        },
        type: "POST",
        url: base_url + 'user/new_login',
        cache:false,
        data: {'role_code':id,'second_id':second_id},
        success: function(msg){
            $("#preloader").hide();
            var obj = jQuery.parseJSON(msg);
            if(obj.status=='100'){
                $('#centeredModal').modal({backdrop: 'static', keyboard: false,show:true});
                $('#centeredModal').modal('show');
                $('#modal_content').html(obj.data);
            }else{
                location.reload();
            }
        }
      });
  }


  function submitLoginForm(){
        var $data = new FormData($('#login_form')[0]);
         $.ajax({
            beforeSend: function(){
                $("#preloader").show();
            },
            type: "POST",
            url: base_url + 'user/new_login',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg){
                $("#preloader").hide();
                var obj = jQuery.parseJSON(msg);
                if(obj.status=='100'){
                    $('#centeredModal').modal('show');
                    $('#modal_content').html(obj.data);
                }else{
                    window.location.href = base_url + 'user/user_dashboard';
                }
            }
        });
    }
</script>

</body>
</html>
