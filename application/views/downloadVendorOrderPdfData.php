<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    .pdfTbl{
        font-family:Arial, Helvetica, sans-serif; margin:auto; margin-top: 30px;
        text-shadow: none;
        border-collapse: collapse;
        width: 100%;
        
    }

    .pdfTbl, .pdfTbl th, .pdfTbl td {
        border: 0.5px solid #ddd;
        font-size: 7px;
        text-shadow: unset;
    }
    
    .alignRight{
        text-align:right;
    }
    .alignLeft{
        text-align:left;
    }
    .bold{
        font-weight: bold;
    }
    .tblHead{
        font-size: 12px;
        width: 100%;
    }
    table.tblHead tr td{
        padding: 10px 0;
        margin: 10px 0;
        text-align: left;
    }
    .mr-0{
        margin:0;
    }
    .ReceiptTitle{
        font-size:8px;
        text-decoration: underline;
        line-height: 6px;
    }
    .ReceiptHead{
        font-size:10px;
        line-height: 4px;
    }
    .ReceiptAddress{
        font-size:7px;
        line-height: 6px;
    }
    .ReceiptInfo{
        font-size:7 px;
    }
    .ReceiptInfo p{
        padding:1mm;
    }
    .tdHeight{
        max-height: 80mm;
    }
    @page {
  grid: "top" 5em
        "running-header" 2em
        "bottom";
  chains: top bottom;
}
</style>

<!-- <div style="margin-bottom:0px; width: 50%"> -->
<table width="100%" class="" cellpadding="13" cellspacing="0" border="0">
<tr>
<td width="50%">
    <table width="100%" autosize="1" class="" cellpadding="0" cellspacing="0" border="0" align="center">

        <tbody>
            <tr>        
                <td align="right"> <p style="line-height: 3px; font-size:8px">For Accounts Use</p></td>
            </tr>
            <tr>
                <td>
                     <p class="ReceiptTitle">Goods Receipt Voucher - Raw Material</p>
                    <h2 class="ReceiptHead">KISALAYA HERBALS LIMITED</h2>
                    <p class="ReceiptAddress">Plot No.548, Sector III, Industrial Area, Pithampur, Dhar (M.P.)</p>
                </td>
            </tr>
       
        </tbody>
    </table>
    <table autosize="1" class="ReceiptInfo" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tbody>
            <tr><td colspan="2"></td></tr>
            <tr>        
                <td width="70%">GRV No. : <?php echo $vendorOrderData[0]->grv_no; ?></td>
                <td >Date : <?php echo ConvertDate($vendorOrderData[0]->transaction_date, '', 'd-m-Y'); ?></td>
            </tr>
            
        </tbody>
    </table>
    <table autosize="1" class="ReceiptInfo" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tbody>
            <tr>        
                <td style="line-height:2;" colspan="2">Name of The Vendor : <?php  echo ucfirst($vendorOrderData[0]->vendor_name); ?></td>
            </tr>
            <tr>
                <td style="line-height:2;" width="70%">Invoice No. <?php echo $vendorOrderData[0]->order_no; ?></td>
                <td>Date : <?php echo ConvertDate($vendorOrderData[0]->bill_date, '', 'd-m-Y'); ?></td>
            </tr>
        </tbody>
    </table>

     <table autosize="1" class="pdfTbl" width="100%" cellpadding="2" cellspacing="0" border="0">
        <thead >
            <tr bgcolor="#f5f5f5">
                <th width="35%"><p style="padding: 6px;"><strong>Name of the Item</strong></p></th>
                <th width="15%"><strong>Quantity</strong></th>
                <th width="14%"><strong>Rate</strong></th>
                <th width="16%"><strong>Difference</strong></th>
                <th width="20%"><strong>Remarks</strong></th>
            </tr>
        </thead>
        <tbody>
          <?php $totalPrice = 0;
          foreach($vendorOrderData as $vod){ ?>
            <tr>
                <td width="35%"><?php echo $vod->raw_material_name;?></td>
                <td width="15%"><?php echo $vod->qty;?></td>
                <td width="14%"><?php echo $vod->price;?></td>
                <td width="16%"><?php echo $vod->shortage_excess;?></td>
                <td width="20%"><?php echo $vod->note; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <table class="pdfTbl" width="100%" cellpadding="10" cellspacing="0" border="0">
        <tbody>
            <tr>
                <td colspan="2" style="border:none" width="60%">Security</td>
                <td width="20%" style="border:none">Store Supervisor</td>
                <td width="20%" style="border:none"></td>
            </tr>
            <tr>
                <td height="53" colspan="2" >1. Yield- </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="53" colspan="2" >2. Content-</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="53" colspan="2" >3. Other-</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="53" colspan="2" >4. TLC-</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</td>
<td width="50%">
    <table autosize="1"  width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
        <tbody>
            <tr>        
                <td align="right"> <p style="line-height: 3px; font-size:8px">For Office Lab Use</p></td>
            </tr>
            <tr>
                <td>
                     <p class="ReceiptTitle">Goods Receipt Voucher - Raw Material</p>
                    <h2 class="ReceiptHead">KISALAYA HERBALS LIMITED</h2>
                    <p class="ReceiptAddress">Plot No.548, Sector III, Industrial Area, Pithampur, Dhar (M.P.)</p>
                </td>
            </tr>
       
        </tbody>
    </table>
    <table autosize="1"  class="ReceiptInfo" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tbody>
            <tr><td colspan="2"></td></tr>
            <tr>        
                <td width="70%">GRV No. : <?php echo $vendorOrderData[0]->grv_no; ?></td>
                <td width="30%">Date : <?php echo ConvertDate($vendorOrderData[0]->transaction_date, '', 'd-m-Y'); ?></td>
            </tr>
            
        </tbody>
    </table>
    <table autosize="1"  class="ReceiptInfo" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tbody>
            <tr>        
                <td style="line-height:2;" colspan="2">Name of The Vendor : <?php  echo ucfirst($vendorOrderData[0]->vendor_name); ?></td>
            </tr>
            <tr>
                <td style="line-height:2;" width="70%">Invoice No. <?php echo $vendorOrderData[0]->order_no; ?></td>
                <td style="line-height:2;" width="30%">Date : <?php echo ConvertDate($vendorOrderData[0]->bill_date, '', 'd-m-Y'); ?></td>
            </tr>
        </tbody>
    </table>
    <table autosize="1"  class="pdfTbl" width="100%" cellpadding="2" cellspacing="0" border="0">
        <thead >
            <tr bgcolor="#f5f5f5">
                <th width="35%"><p style="padding: 6px;"><strong>Name of the Item</strong></p></th>
                <th width="15%"><strong>Quantity</strong></th>
                <th width="14%"><strong>Rate</strong></th>
                <th width="16%"><strong>Difference</strong></th>
                <th width="20%"><strong>Remarks</strong></th>
            </tr>
        </thead>
        <tbody>
          <?php $totalPrice = 0;
          foreach($vendorOrderData as $vod){ ?>
            <tr>
                <td width="35%"><?php echo $vod->raw_material_name;?></td>
                <td width="15%"><?php echo $vod->qty;?></td>
                <td width="14%"><?php echo $vod->price;?></td>
                <td width="16%"><?php echo $vod->shortage_excess;?></td>
                <td width="20%"><?php echo $vod->note; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <table autosize="1" class="pdfTbl" width="100%" cellpadding="10" cellspacing="0" border="0">
        <tbody>
            <tr>
                <td colspan="2" style="border:none" width="60%">Security</td>
                <td width="20%" style="border:none">Store Supervisor</td>
                <td width="20%" style="border:none"></td>
            </tr>
            <tr>
                <td height="53" colspan="2" >1. Yield-</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="53" colspan="2" >2. Content-</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="53" colspan="2" >3. Other-</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="53" colspan="2" >4. TLC-</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</td>
</tr>
</table>
<!-- </div> -->

<!-- <br pagebreak="true"/> -->
<!-- <div style="margin-bottom:0px; width: 50%"></div> -->
<br pagebreak="true"/>
<table width="100%" class="" cellpadding="13" cellspacing="0" border="0">
<tr>
<td width="50%">
    <table width="100%" autosize="1" class="" cellpadding="0" cellspacing="0" border="0" align="center">
        <tbody>
            <tr>        
                <td align="right"> <p style="line-height: 3px; font-size:8px">For Office Use</p></td>
            </tr>
            <tr>
                <td>
                     <p class="ReceiptTitle">Goods Receipt Voucher - Raw Material</p>
                    <h2 class="ReceiptHead">KISALAYA HERBALS LIMITED</h2>
                    <p class="ReceiptAddress">Plot No.548, Sector III, Industrial Area, Pithampur, Dhar (M.P.)</p>
                </td>
            </tr>
       
        </tbody>
    </table>
    <table autosize="1" class="ReceiptInfo" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tbody>
            <tr><td colspan="2"></td></tr>
            <tr>        
                <td width="70%">GRV No. : <?php echo $vendorOrderData[0]->grv_no; ?></td>
                <td >Date : <?php echo ConvertDate($vendorOrderData[0]->transaction_date, '', 'd-m-Y'); ?></td>
            </tr>
            
        </tbody>
    </table>
    <table autosize="1" class="ReceiptInfo" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tbody>
            <tr style="line-height:2;">        
                <td colspan="2">Name of The Vendor : <?php  echo ucfirst($vendorOrderData[0]->vendor_name); ?></td>
            </tr>
            <tr style="line-height:2;">
                <td width="70%">Invoice No. <?php echo $vendorOrderData[0]->order_no; ?></td>
                <td>Date : <?php echo ConvertDate($vendorOrderData[0]->bill_date, '', 'd-m-Y'); ?></td>
            </tr>
        </tbody>
    </table>

    <table autosize="1" class="pdfTbl" width="100%" cellpadding="2" cellspacing="0" border="0">
        <thead >
            <tr bgcolor="#f5f5f5">
                <th width="35%"><p style="padding: 6px;"><strong>Name of the Item</strong></p></th>
                <th width="15%"><strong>Quantity</strong></th>
                <th width="14%"><strong>Rate</strong></th>
                <th width="16%"><strong>Difference</strong></th>
                <th width="20%"><strong>Remarks</strong></th>
            </tr>
        </thead>
        <tbody>
          <?php $totalPrice = 0;
          foreach($vendorOrderData as $vod){ ?>
            <tr>
                <td width="35%"><?php echo $vod->raw_material_name;?></td>
                <td width="15%"><?php echo $vod->qty;?></td>
                <td width="14%"><?php echo $vod->price;?></td>
                <td width="16%"><?php echo $vod->shortage_excess;?></td>
                <td width="20%"><?php echo $vod->note; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <table class="pdfTbl" width="100%" cellpadding="10" cellspacing="0" border="0">
        <tbody>
            <tr>
                <td colspan="2" style="border:none" width="60%">Security</td>
                <td width="20%" style="border:none">Store Supervisor</td>
                <td width="20%" style="border:none"></td>
            </tr>
            <tr>
                <td height="53" colspan="2" >1. Yield- </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="53" colspan="2" >2. Content-</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="53" colspan="2" >3. Other-</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="53" colspan="2" >4. TLC-</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</td>
<td width="50%">
    <table autosize="1"  width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
        <tbody>
            <tr>        
                <td align="right"> <p style="line-height: 3px; font-size:8px">For Factory Lab Use</p></td>
            </tr>
            <tr>
                <td>
                     <p class="ReceiptTitle">Goods Receipt Voucher - Raw Material</p>
                    <h2 class="ReceiptHead">KISALAYA HERBALS LIMITED</h2>
                    <p class="ReceiptAddress">Plot No.548, Sector III, Industrial Area, Pithampur, Dhar (M.P.)</p>
                </td>
            </tr>
       
        </tbody>
    </table>
    <table autosize="1"  class="ReceiptInfo" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tbody>
            <tr><td colspan="2"></td></tr>
            <tr>        
                <td width="70%">GRV No. : <?php echo $vendorOrderData[0]->grv_no; ?></td>
                <td width="30%">Date : <?php echo ConvertDate($vendorOrderData[0]->transaction_date, '', 'd-m-Y'); ?></td>
            </tr>
            
        </tbody>
    </table>
    <table autosize="1"  class="ReceiptInfo" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tbody>
            <tr>        
                <td style="line-height:2;" colspan="2">Name of The Vendor : <?php  echo ucfirst($vendorOrderData[0]->vendor_name); ?></td>
            </tr>
            <tr>
                <td style="line-height:2;" width="70%">Invoice No. <?php echo $vendorOrderData[0]->order_no; ?></td>
                <td style="line-height:2;" width="30%">Date : <?php echo ConvertDate($vendorOrderData[0]->bill_date, '', 'd-m-Y'); ?></td>
            </tr>
        </tbody>
    </table>
    <table autosize="1"  class="pdfTbl" width="100%" cellpadding="2" cellspacing="0" border="0">
        <thead >
            <tr bgcolor="#f5f5f5">
                <th width="35%"><p style="padding: 6px;"><strong>Name of the Item</strong></p></th>
                <th width="15%"><strong>Quantity</strong></th>
                <th width="14%"><strong>Rate</strong></th>
                <th width="16%"><strong>Difference</strong></th>
                <th width="20%"><strong>Remarks</strong></th>
            </tr>
        </thead>
        <tbody>
          <?php $totalPrice = 0;
          foreach($vendorOrderData as $vod){ ?>
            <tr>
                <td width="35%"><?php echo $vod->raw_material_name;?></td>
                <td width="15%"><?php echo $vod->qty;?></td>
                <td width="14%"><?php echo $vod->price;?></td>
                <td width="16%"><?php echo $vod->shortage_excess;?></td>
                <td width="20%"><?php echo $vod->note; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <table autosize="1" class="pdfTbl" width="100%" cellpadding="10" cellspacing="0" border="0">
        <tbody>
            <tr>
                <td colspan="2" style="border:none" width="60%">Security</td>
                <td width="20%" style="border:none">Store Supervisor</td>
                <td width="20%" style="border:none"></td>
            </tr>
            <tr>
                <td height="53" colspan="2" >1. Yield-</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="53" colspan="2" >2. Content-</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="53" colspan="2" >3. Other-</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td height="53" colspan="2" >4. TLC-</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</td>
</tr>
</table>