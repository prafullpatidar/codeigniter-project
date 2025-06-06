<style type="text/css">
  .text_content{
        margin-top: 15px;
    margin-bottom: 15px;
    font-style: italic;
    font-family: system-ui;
    font-weight : bold;
  }

  .comment_container {
      display: flex;
      flex-direction: column; /* Stack elements vertically */
      align-items: flex-start; /* Align the label and textarea class="form-control" name="" id="" to the left */
      gap: 10px; /* Space between the elements */
    }

    .image-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 50px;
    }

    #image {
        width: 100%;
        cursor: pointer;
    }

    #image1 {
        width: 100%;
        cursor: pointer;
    }

    #image2 {
        width: 100%;
        cursor: pointer;
    }

    /* Full screen container */
    .full-screen-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
    }

    .full-screen-container img {
        width: 80%;
        max-width: 1000px;
    }
</style>

<?php
if(empty($data)){
 echo '<div align="center" style="font-weight:bold;font-size:15px;">No Data Available</div>';

}else{
?>

<div class="viewRcpt double-table">
 <form class="form-horizontal form-bordered" name="feedback_form" id="feedback_form" enctype="multipart/form-data">
<div class="row flex-row">
<div>
  <img class="" src="<?php echo base_url();?>/assets/images/company_logo.png" alt="brand logo"/ width="50" style="margin-left:10px;"></div>
<div style="width:100%" class="text-center"><h2 class="rcptTitle text-center">Feedback</h2></div>
  </div>
<div class="text_content">
  Dear Master, Chief steward, or Chief Cook,
<br>
You have received Provisions from One North Ships.
We would like you to help in increasing the efficiency of our services and products, so kindly access the quality of the items delivered and let us know the areas of improvements.
</div>
<table width="100%" border="1">
  <tr>
    <td colspan="3" rowspan="6">
      <h3>One North Ships</h3><p>
      Connecting the World <br />
      info@onenorthships.com / www.onenorthships.com</p></td>
    <td><strong>Vessal:</strong></td>
    <td colspan="2"><?php echo $data['ship_name'];?></td>
  </tr>
  <tr>
    <td><strong>Imo No :</strong></td>
    <td colspan="2"><?php echo $data['imo_no'];?></td>
  </tr>
  <tr>
    <td><strong>Date of Supply:</strong></td>
    <td colspan="2"><?php echo ConvertDate($data['delivery_date'],'','d-m-Y') ;?></td>
  </tr>
  <tr>
    <td><strong>PO No:</strong></td>
    <td colspan="2"><?php echo $data['po_no'];?></td>
  </tr>
  <tr>
    <td><strong>Suppliers:</strong></td>
    <td colspan="2"><?php echo $data['agent_name'];?></td>
  </tr>
  <tr>
    <td><strong>Port of Delivery:</strong></td>
    <td colspan="2"><?php echo $data['delivery_port'];?></td>
  </tr>

</table>

<div class="text_content">
  Kindly deliver your considerable suggestion on following: <br>

  Please indicate below: (5 = Very good, 4 = Good, 3 = Average, 2 = Bad 1 = Very bad)

</div>

<div class="sip-table">
<table class="header-fixed-new table-text-ellipsis table-layout-fixed table">
 <thead class="t-header">
  <tr>
    <th width="40%"><strong>#</strong></th>
    <th width="20%"></th>
    <th width="20%"></th>
    <th width="20%"></th>
  </tr>
</thead>
<tbody>
  <tr>
    <td>Quality of Fresh Provision</td>
    <td><?= $data['fresh_provision'] ?></td>
    <td><?= $data['fp_comment'] ?></td>
    <td><?= (!empty($data['fp_img'])) ? ' <div class="image-container"><img  src="'.base_url().'uploads/work_order_pdfs/'.$data['fp_img'].'" alt="Image" id="image"></div>' : '' ?></td>
  </tr>
  <tr>
    <td>Quality of Dry & Frozen Provision</td>
    <td><?= $data['dry_provision'] ?></td>
    <td><?= $data['dp_comment'] ?></td>
    <td><?= (!empty($data['dp_img'])) ? ' <div class="image-container"><img  src="'.base_url().'uploads/work_order_pdfs/'.$data['dp_img'].'" alt="Image" id="image1"></div>' : '' ?></td>
  </tr>
  <tr>
    <td>Quality of Packing/Marking Provision</td>
    <td><?= $data['marking_provision'] ?></td>
    <td><?= $data['mp_comment'] ?></td>
    <td><?= (!empty($data['mp_img'])) ? ' <div class="image-container"><img src="'.base_url().'uploads/work_order_pdfs/'.$data['mp_img'].'" alt="Image" id="image2"></div>' : '' ?></td>
  </tr>
  <tr>
    <td>Suppliers/representatives appearance onboard</td>
    <td><?= $data['supplier_onboard'] ?></td>
    <td></td>
    <td></td>
  </tr>
   <tr>
    <td>Overall Performance</td>
    <td><?= $data['overall_performance'] ?></td>
    <td></td>
    <td></td>
  </tr>
</tbody>
</table>
</div>
<br>
  <div class="comment_container">
    <label for="comment">Comment</label>
    <p><?= $data['comment'] ?></p>
  </div>
</div>

<!-- Full screen container -->
    <div class="full-screen-container" id="fullScreenContainer">
        <img src="" alt="Full Screen Image" id="fullScreenImage">
    </div>

    <div class="form-footer">
  <div class="pull-right">
      <a target="_blank" class="btn btn-success btn-slideright mr-5" href="<?php echo base_url().'/shipping/download_feedback_pdf/'.base64_encode($data['delivery_note_id']);?>">Download</a>
  </div>
  <div class="clearfix"></div>
</div><!-- /.form-footer -->
</div>
  <?php }?>
 <script>
        $(document).ready(function () {
            // When the image is clicked
            $("#image").click(function () {
                var imgSrc = $(this).attr("src");
                // Set the full-screen image source to the clicked image's source
                $("#fullScreenImage").attr("src", imgSrc);
                // Show the full-screen container
                $("#fullScreenContainer").fadeIn();
            });

             $("#image1").click(function () {
                var imgSrc = $(this).attr("src");
                // Set the full-screen image source to the clicked image's source
                $("#fullScreenImage").attr("src", imgSrc);
                // Show the full-screen container
                $("#fullScreenContainer").fadeIn();
            });

            $("#image2").click(function () {
                var imgSrc = $(this).attr("src");
                // Set the full-screen image source to the clicked image's source
                $("#fullScreenImage").attr("src", imgSrc);
                // Show the full-screen container
                $("#fullScreenContainer").fadeIn();
            });

            // When the full-screen container is clicked, close the full-screen view
            $("#fullScreenContainer").click(function () {
                $(this).fadeOut();
            });
        });
    </script>