<style type="text/css">
    body {
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f0f0f0;
}

#image-container {
    position: relative;
}

#image-container img {
    max-width: 100%;
    max-height: 100%;
}

#overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 999;
}

#thumbnail-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 0px;
}

.thumbnail {
    max-width: 100px;
    cursor: pointer;
    margin: 0 10px;
}
</style>
<div class="animated fadeIn" id="stock_form">
    <div class="row">
    <div class="col-md-12">
        <div class="">
        <form class="form-horizontal form-bordered" name="addEditstock" enctype="multipart/form-data" id="addEditstock" method="post">
                <div class="no-padding rounded-bottom">
                <div class="form-body">
                <span class="port_name"></span>
                <div id="abc" class="sip-table" role="grid">
                <?php echo form_error('vendor_quote_id','<p class="error" style="color:#ff0000;display: inline;">','</p>')?> 

<!--                 <table class="w-100 mb-1 no-border user-name-t">
                    <thead>
                    <tr class="head">
                    </tr>
                    </thead>
                </table> -->
                <div class="table-responsive vq-table">
               <table class="table vendor-table header-fixed-new table-text-ellipsis table-layout-fixed vendor_quote">
                <thead class="t-header">
                    <tr class="head">
                    </tr>
                <tr class="row_name">
                  <th width="15%">Item No.</th>
                  <th width="40%">Description</th>
                  <th width="10%">Unit</th>
                  <th width="10%">RFQ QTY</th>
                </tr>
                </thead>
                    <tbody class="item_data">
                      
                    </tbody>
               </table>
</div>
          </div>               
              
    <input type="hidden" name="id" id="ship_order_id" value="<?php echo $dataArr['id'];?>">  
    <input type="hidden" name="second_id" id="second_id" value="<?php echo $dataArr['second_id'];?>">         

    <input type="hidden" value="save" name="actionType">
                    </div><!-- /.form-body -->
                    <div class="clearfix"></div>
                    <div class="form-footer">
                        <div class="pull-right">
                            <?php 
                             if(empty($dataArr['second_id'])){
                            ?>
                              <a class="btn btn-danger btn-slideright mr-5" href="#" data-dismiss="modal">Cancel</a>
                             <button type="button" onclick="submitAjax360Form('addEditstock','shipping/vendor_quatation','98%','order_request_list')" class="btn btn-success btn-slideright mr-5">Submit</button>
<!--                              <button type="button" onclick="submitVendorQuoteForm()" class="btn btn-success btn-slideright mr-5">Submit</button> -->
                         <?php } ?>
                        </div>
                        <div class="clearfix"></div>
                    </div><!-- /.form-footer -->
                </form>
            </div><!-- /.panel-body -->
        </div>
    </div>
</div>
    </div>

<script type="text/javascript">
    
    $(document).ready(function(){
     submitTableForm();
    })  

function submitTableForm(pageId, empty_sess=0)
    {    

        var $data = new FormData($('#addEditstock')[0]);
        $.ajax({
            beforeSend: function(){
                        $("#customLoader").show();
                    },
            type: "POST",
            url: base_url + 'shipping/vendor_quote_list',
            cache:false,
            data: $data,
            processData: false,
            contentType: false,
            success: function(msg)
            {
                $("#customLoader").hide();
                var obj = jQuery.parseJSON(msg);
                $('.item_data').html(obj.dataArr);
                if(obj.header==''){
                  $('.head').addClass('hide');
                }else{
                  $('.head').append(obj.header);                    
                }
                $('.port_name').html(obj.port_name)
                $('.row_name').append(obj.thArr);
            }
        });
        return false;
    }
</script>    