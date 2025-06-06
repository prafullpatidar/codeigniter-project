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

    .swal2-container {
      z-index: 9999 !important; /* Make sure this value is higher than other modals */
    }
</style>
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
    <td>
      <select class="form-control ratings" name="fresh_provision" id="fresh_provision" data-id="fresh_provision">
        <option value="">Select</option>
        <option value="1" <?php echo ($dataArr['fresh_provision']==1) ? 'selected' : '';?>>1</option>
        <option value="2" <?php echo ($dataArr['fresh_provision']==2) ? 'selected' : '';?>>2</option>
        <option value="3" <?php echo ($dataArr['fresh_provision']==3) ? 'selected' : '';?>>3</option>
        <option value="4" <?php echo ($dataArr['fresh_provision']==4) ? 'selected' : '';?>>4</option>
        <option value="5" <?php echo ($dataArr['fresh_provision']==5) ? 'selected' : '';?>>5</option>
      </select>
      <?php echo form_error('fresh_provision','<p class="error" style="display: inline;">','</p>'); ?>
    </td>
    <td class="fresh_provision" <?php echo (!empty($dataArr['fresh_provision']) && $dataArr['fresh_provision']<=3) ? '' : 'style="display:none;"' ;?> ><textarea class="form-control" name="fp_comment" id="fp_comment"><?php echo $dataArr['fp_comment']; ?></textarea>
     <?php echo form_error('fp_comment','<p class="error" style="display: inline;">','</p>'); ?>
   </td>
    <td class="fresh_provision" <?php echo (!empty($dataArr['fresh_provision']) && $dataArr['fresh_provision']<=3) ? '' : 'style="display:none;"' ;?> ><input type="file" name="fp_img" accept="image/*">
     <?php echo form_error('fp_img','<p class="error" style="display: inline;">','</p>'); ?>
   </td>
  </tr>
  <tr>
    <td>Quality of Dry & Frozen Provision</td>
    <td>
      <select class="form-control ratings" name="dry_provision" id="dry_provision" data-id="dry_provision" >
        <option value="">Select</option>
         <option value="1" <?php echo ($dataArr['dry_provision']==1) ? 'selected' : '';?>>1</option>
        <option value="2" <?php echo ($dataArr['dry_provision']==2) ? 'selected' : '';?>>2</option>
        <option value="3" <?php echo ($dataArr['dry_provision']==3) ? 'selected' : '';?>>3</option>
        <option value="4" <?php echo ($dataArr['dry_provision']==4) ? 'selected' : '';?>>4</option>
        <option value="5" <?php echo ($dataArr['dry_provision']==5) ? 'selected' : '';?>>5</option>
      </select>
       <?php echo form_error('dry_provision','<p class="error" style="display: inline;">','</p>'); ?>
    </td>
    <td class="dry_provision" <?php echo (!empty($dataArr['dry_provision']) && $dataArr['dry_provision']<=3) ? '' : 'style="display:none;"' ;?>><textarea class="form-control" name="dp_comment" id="dp_comment"><?php echo $dataArr['dp_comment']; ?></textarea>
    <?php echo form_error('dp_comment','<p class="error" style="display: inline;">','</p>'); ?>
    </td>
    <td class="dry_provision" <?php echo (!empty($dataArr['dry_provision']) && $dataArr['dry_provision']<=3) ? '' : 'style="display:none;"' ;?>><input type="file" name="dp_img" accept="image/*">
     <?php echo form_error('dp_img','<p class="error" style="display: inline;">','</p>'); ?>
   </td>
  </tr>
  <tr>
    <td>Quality of Packing/Marking Provision</td>
    <td>
      <select class="form-control ratings" name="marking_provision" id="marking_provision" data-id="marking_provision">
        <option value="">Select</option>
        <option value="1" <?php echo ($dataArr['marking_provision']==1) ? 'selected' : '';?>>1</option>
        <option value="2" <?php echo ($dataArr['marking_provision']==2) ? 'selected' : '';?>>2</option>
        <option value="3" <?php echo ($dataArr['marking_provision']==3) ? 'selected' : '';?>>3</option>
        <option value="4" <?php echo ($dataArr['marking_provision']==4) ? 'selected' : '';?>>4</option>
        <option value="5" <?php echo ($dataArr['marking_provision']==5) ? 'selected' : '';?>>5</option>
      </select>
       <?php echo form_error('marking_provision','<p class="error" style="display: inline;">','</p>'); ?>
    </td>
    <td class="marking_provision" <?php echo (!empty($dataArr['marking_provision']) && $dataArr['marking_provision']<=3) ? '' : 'style="display:none;"' ;?>><textarea class="form-control" name="mp_comment" id=""><?php echo $dataArr['mp_comment']; ?></textarea>
       <?php echo form_error('mp_comment','<p class="error" style="display: inline;">','</p>'); ?>

    </td>
    <td class="marking_provision" <?php echo (!empty($dataArr['marking_provision']) && $dataArr['marking_provision']<=3) ? '' : 'style="display:none;"' ;?>><input type="file" name="mp_img" accept="image/*">
     <?php echo form_error('mp_img','<p class="error" style="display: inline;">','</p>'); ?></td>
  </tr>
  <tr>
    <td>Suppliers/representatives appearance onboard</td>
    <td>
      <select class="form-control" name="supplier_onboard" id="supplier_onboard">
        <option value="">Select</option>
         <option value="1" <?php echo ($dataArr['supplier_onboard']==1) ? 'selected' : '';?>>1</option>
        <option value="2" <?php echo ($dataArr['supplier_onboard']==2) ? 'selected' : '';?>>2</option>
        <option value="3" <?php echo ($dataArr['supplier_onboard']==3) ? 'selected' : '';?>>3</option>
        <option value="4" <?php echo ($dataArr['supplier_onboard']==4) ? 'selected' : '';?>>4</option>
        <option value="5" <?php echo ($dataArr['supplier_onboard']==5) ? 'selected' : '';?>>5</option>
      </select>
       <?php echo form_error('supplier_onboard','<p class="error" style="display: inline;">','</p>'); ?>
    </td>
  </tr>
   <tr>
    <td>Overall Performance</td>
    <td>
      <select class="form-control" name="overall_performance" id="overall_performance">
        <option value="">Select</option>
        <option value="1" <?php echo ($dataArr['overall_performance']==1) ? 'selected' : '';?>>1</option>
        <option value="2" <?php echo ($dataArr['overall_performance']==2) ? 'selected' : '';?>>2</option>
        <option value="3" <?php echo ($dataArr['overall_performance']==3) ? 'selected' : '';?>>3</option>
        <option value="4" <?php echo ($dataArr['overall_performance']==4) ? 'selected' : '';?>>4</option>
        <option value="5" <?php echo ($dataArr['overall_performance']==5) ? 'selected' : '';?>>5</option>
      </select>
      <?php echo form_error('overall_performance','<p class="error" style="display: inline;">','</p>'); ?>
    </td>
  </tr>
</tbody>
</table>
</div>
<br>
  <div class="comment_container">
    <label for="comment">Comment</label>
    <textarea class="form-control" name="comment" id="comment" rows="4" placeholder="Write your comment here..."><?php echo $dataArr['comment']; ?></textarea>
  </div>

<input type="hidden" name="actionType" value="save">
<input type="hidden" value="<?php echo $dataArr['id'];?>" name="id">

<div class="form-footer">
  <div class="pull-right">
      <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
      <button type="button" onclick="submitAjax360Form('feedback_form','shipping/delivery_feedback','98%','delivery_note_list')" class="btn btn-success btn-slideright mr-5">Submit</button>
      
  </div>
  <div class="clearfix"></div>
</div><!-- /.form-footer -->
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('.ratings').change(function(){
      var id = $(this).data('id');
      var val = $(this).val();
        if(val && val<=3){
          $('.'+id).show();
        }
        else{
          $('.'+id).hide();
        }  
    })
  })

</script>