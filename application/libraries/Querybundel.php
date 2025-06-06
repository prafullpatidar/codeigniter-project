<?php
class Querybundel {
    public $user = "SELECT u.*, r.code,v.vendor_id,r.role_name,s.ship_id FROM user u
        INNER JOIN user_role ur ON ur.user_id=u.user_id
        INNER JOIN role r ON r.role_id=ur.role_id 
        LEFT JOIN vendor v on v.user_id = u.user_id 
        LEFT JOIN ships s on s.captain_user_id = u.user_id or s.cook_user_id = u.user_id                   
        WHERE 1 #WHERE# AND u.user_name = ? AND u.password = ? AND u.status != '0' ";

    public $getUserByPassportId = "SELECT u.*, r.code FROM user u
                    INNER JOIN user_role ur ON ur.user_id=u.user_id
                    INNER JOIN role r ON r.role_id=ur.role_id                
                    WHERE 1 AND u.passport_id = ? AND u.status != '0' ";

    public $getCrewByPassportId = "SELECT scm.* from ship_crew_members as scm                
        INNER JOIN crew_member_entries cme on cme.crew_member_entries_id = scm.crew_member_entries_id
                    WHERE 1 AND scm.identity_number = ? AND scm.ship_id = ? AND month(cme.entry_date) = ? AND year(cme.entry_date) = ? ";

    public $getFoodHabitsByCrewMemberId = "SELECT cfh.* from crew_food_habits as cfh                
                    WHERE 1 AND cfh.crew_member_id = ? ";

    public $queryforgetalluser = "SELECT u.*,concat(u.first_name,' ',u.last_name) as name,r.role_name as role,v.currency,v.vendor_pdf,v.payment_term,v.vendor_id,v.bank_name,v.holder_name,v.ac_number,v.ifsc_code,v.bank_address,v.swift_code,v.ibn_number from user u 
                LEFT JOIN user_role ur on ur.user_id = u.user_id
                LEFT JOIN role r on r.role_id = ur.role_id 
                LEFT JOIN vendor v on v.user_id = u.user_id
                WHERE 1  ##WHERE## 
                ##ORDERBY## ##LIMIT## ";
    
    public $Countqueryforgetalluser = "SELECT count(*) as count from user u 
    LEFT JOIN user_role ur on ur.user_id = u.user_id
                LEFT JOIN role r on r.role_id = ur.role_id 
                LEFT JOIN vendor v on v.user_id = u.user_id
                WHERE 1   ##WHERE##";
    
    public $CountgetAllProductCategory = 'select count(*) as count from product_category pc
          LEFT JOIN product_group pg on pg.product_group_id = pc.product_group_id  
    left join user u on u.user_id = pc.added_by WHERE 1 ##WHERE##';
    
    public $getAllProductCategory = "Select pc.*,concat(u.first_name,' ',u.last_name) as creator_name,pg.name as group_name from product_category pc 
      LEFT JOIN product_group pg on pg.product_group_id = pc.product_group_id
      left join user u on u.user_id = pc.added_by WHERE 1  ##WHERE##  ##ORDER## ##LIMIT## ";
    
    public $CountgetAllProduct = "Select count(*) as count from product p
     LEFT JOIN product_category pc on pc.product_category_id = p.product_category_id
               LEFT JOIN nutritional n on n.product_id = p.product_id
    Where 1 ##WHERE## ";
    
    public $getAllProduct = "Select p.*,pc.category_name,pc.sequence,p.product_category_id,pg.name as group_name,pg.product_group_id,n.calories,n.protein,n.fat,n.saturated_fat,n.cholesterol,n.sodium,n.potassium,n.carbohydrates,n.iron,n.calcium from product p 
     LEFT JOIN product_category pc on pc.product_category_id = p.product_category_id
     LEFT JOIN product_group pg on pg.product_group_id = pc.product_group_id
    LEFT JOIN nutritional n on n.product_id = p.product_id
     Where 1 ##WHERE## ##ORDER## ##LIMIT##";

    public $CountgetAllCompany = "SELECT count(*) as count FROM shipping_company c WHERE 1  ##WHERE## ";
   
    public $QuerygetAllCompany = "SELECT c.* FROM shipping_company c WHERE
     1 ##WHERE## ##ORDER## ##LIMIT##";
    
    public $queryforgetalluserRoles = "SELECT r.*,concat(u.first_name,' ',u.last_name) as user_name from role r 
     LEFT JOIN user u on u.user_id = r.created_by 
     WHERE 1 ##WHERE## ##ORDER## ##LIMIT##";

    public $queryforCountuserRoles = "SELECT count(*) as count from role r
     LEFT JOIN user u on u.user_id = r.created_by  
    WHERE 1 ##WHERE## ";
   
    public $QuerygetAllships = "SELECT s.*,concat(u.first_name,' ',u.last_name) as user,sc.name as shipping_company_name,sc.customer_id,sc.payment_term,sc.address as company_address,GROUP_CONCAT(swc.product_category_id) as product_categories,GROUP_CONCAT(DISTINCT smp.product_id) as product_ids  
        from ships s
        LEFT JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id
        LEFT JOIN shipwise_category swc on swc.ship_id = s.ship_id
        LEFT JOIN shipwise_misc_product smp on smp.ship_id = s.ship_id
        LEFT JOIN user u on u.user_id = s.added_by where 1 ##WHERE## 
        GROUP BY s.ship_id
        ##ORDER## ##LIMIT##";

    public $queryGetShipDetails = 'Select s.*,capt.email as capt_email,capt.user_name as capt_user_name ,ck.email as ck_email,ck.user_name as ck_user_name from ships s 
        LEFT JOIN user capt on capt.user_id = s.captain_user_id
        LEFT JOIN user ck on ck.user_id = s.cook_user_id
       WHERE 1 ##WHERE##';    
   
    public $CountgetAllships = 'SELECT count(*) as count FROM ships s
        LEFT JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id
        LEFT JOIN user u on u.user_id = s.added_by where 1 ##WHERE##
        ';
   
    public $CountgetAllvendors = " SELECT count(*) as count from vendor v INNER JOIN user u on u.user_id = v.user_id WHERE 1 AND u.is_deleted IS NULL ##WHERE## ##ORDER## ##LIMIT## ";

    public $getAllvendors = " SELECT u.*,concat(u.first_name,' ',u.last_name) as vendor_name,v.vendor_id,v.currency,v.vendor_pdf,u.email from vendor v INNER JOIN user u on u.user_id = v.user_id WHERE 1 AND u.is_deleted IS NULL ##WHERE## ##ORDER## ##LIMIT## ";
   

    public $queryGetAllGroupCount = "SELECT count(*) as count
            FROM `product_group` pg
            WHERE 1 ##WHERE##";

    public $queryGetGroup = "SELECT pg.* FROM `product_group` pg WHERE 1 ##WHERE## ##ORDER## ##LIMIT##";         
    public $queryforgetUserRolesByCode = 'SELECT * FROM role WHERE code = ? ';     

    public $queryforgetUserRolesById = 'SELECT * FROM role WHERE role_id = ? ';   
 
    public $count_ports = 'SELECT count(*) as count from ship_ports sp
         LEFT join user u on u.user_id = sp.added_by
         LEFT JOIN port_agent pa on pa.agent_id = sp.agent_id
     where 1 and sp.is_deleted != 1 ##WHERE##';

    public $get_all_port = 'SELECT sp.*, concat(u.first_name," ",u.last_name) as added_by,pa.name as agent_name from ship_ports sp 
         LEFT join user u on u.user_id = sp.added_by
         LEFT JOIN port_agent pa on pa.agent_id = sp.agent_id
    where 1 and sp.is_deleted != 1 ##WHERE## ##ORDER## ##LIMIT##';


    public $queryGetNextPortByID = 'SELECT sp.name,sp.date FROM ship_ports sp where 1 AND sp.ship_id = ? AND sp.date > date(NOW()) order by sp.date asc LIMIT 0,1';


    public $CountgetAllShipStockOrder ="select count(*) as count from ship_order so  
          LEFT JOIN user u on u.user_id = so.created_by WHERE 1 ##WHERE##";
    
    public $QuerygetAllShipStockOrder =" select so.*,concat(u.first_name,' ',u.last_name) as user_name,r.code as created_by_code,sp.name as port_name,sp.date as arrive_date,pa.status as agent_status
        FROM ship_order so
        LEFT JOIN user u on u.user_id = so.created_by   
        LEFT JOIN user_role ur on ur.user_id = u.user_id  
        LEFT JOIN role r on r.role_id = ur.role_id  
        LEFT JOIN ship_ports sp on sp.port_id = so.port_id
        LEFT JOIN port_agent pa on pa.agent_id = sp.agent_id
        WHERE 1 
        ##WHERE## ##ORDER## ##LIMIT## ";


    public $queryGetShipStock = ' SELECT st.*,month(st.stock_date) as month, year(st.stock_date) as year FROM ship_stock st where 1 #WHERE# AND st.delivery_note_id IS NULL AND st.ship_id = ? ';


    public $querygetconsumedstock = " SELECT p.item_no,p.product_id,p.product_name,p.unit,pc.product_category_id,c.total_stock,c.unit_price, c.used_stock,c.stock_detail_id,c.last_total_stock,c.last_used_stock,c.consumed,c.last_consumed,c.available_stock,pc.category_name,pg.name as group_name FROM current_stock_details c
           INNER JOIN product p on p.product_id = c.product_id
           INNER JOIN product_category pc on pc.product_category_id = p.product_category_id
           LEFT JOIN product_group pg on pg.product_group_id = pc.product_group_id
           WHERE 1 ##WHERE## Order By pc.sequence ASC,p.item_no ASC";
    

    public $queryGetCurrentStockDetails = ' select * from current_stock_details  where ship_id = ? AND product_id = ?'; 


    public $querygetProductForOrderStock = "Select p.*,csd.total_stock,csd.used_stock from product p 
      LEFT JOIN product_category pc on pc.product_category_id = p.product_category_id
      LEFT JOIN current_stock_details csd on csd.product_id = p.product_id 
      Where 1 ##WHERE##";



   public $querygetRfqItem = 'select so.*,concat(sp.name,"(",sp.country,")") as port_name,sp.name as porrt_name, sp.country,pa.name as agent_name,pa.email,pa.phone,pa.country as agent_country,s.ship_name,pa.country_code from ship_order so 
       left join ship_ports sp on sp.port_id = so.port_id
       left join port_agent pa on pa.agent_id = sp.agent_id
       left join ships s on s.ship_id = so.ship_id
       where 1 ##WHERE##';  

    
    public $countgetAllworkOrders = 'select count(*) as count from work_order wo 
         left join vendor v on v.vendor_id = wo.vendor_id
         left join user vu on vu.user_id = v.user_id 
         left join user u on u.user_id = wo.created_by
         left join ship_order so on so.ship_order_id = wo.ship_order_id 
         left join ships s on s.ship_id = wo.ship_id
         where 1 ##WHERE##';
    
    public $querygetAllworkOrders = 'select wo.*,concat(u.first_name," ",u.last_name) as created_by,concat(vu.first_name," ",vu.last_name) as vendor_name,so.rfq_no,s.ship_name from work_order wo 
         left join vendor v on v.vendor_id = wo.vendor_id
         left join user vu on vu.user_id = v.user_id 
         left join user u on u.user_id = wo.created_by 
         left join ship_order so on so.ship_order_id = wo.ship_order_id
         left join ships s on s.ship_id = wo.ship_id
         where 1 ##WHERE## ##ORDER## ##LIMIT##'; 


    public $querygetworkOrdersByID = 'SELECT wo.work_order_id,wo.po_no,concat(vu.first_name," ",vu.last_name) as vendor_name,wo.order_date,wo.delivery_date as delivery_date,wo.invoice_no,s.ship_name,s.imo_no,v.currency,wo.json_data,wo.reqsn_date,wo.due_date,wo.ship_order_id,v.payment_term,sc.customer_id,so.requisition_type,wo.remark,vu.address as vendor_address,vu.phone as vendor_phone,wo.created_on,wo.total_price,vu.email as vendor_email,vq.vendor_id,pa.name as agent_name,pa.email as agent_email,concat(pa.country_code,"",pa.phone) as agent_phone,pa.country as agent_country,sp.name as delivery_port,sp.country,vu.user_id as vendor_user_id
                 from work_order wo 
                 left join ship_order so on so.ship_order_id = wo.ship_order_id 
                 left join ships s on s.ship_id = wo.ship_id 
                 LEFT JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id
                 left join vendor_quotation vq on vq.ship_order_id = wo.ship_order_id
                 INNER join vendor_quote_approvals vqa on vqa.vendor_quote_id = vq.vendor_quote_id
                 left join vendor v on v.vendor_id = vq.vendor_id
                 left join user vu on vu.user_id = v.user_id 
                 left join user u on u.user_id = wo.created_by
                 LEFT JOIN ship_ports sp on sp.port_id = so.port_id
                 LEFT JOIN port_agent pa on pa.agent_id = sp.agent_id
                 WHERE 1 ##WHERE##';      


     public $queryGetQuoteVendor =  'SELECT concat(u.first_name," ",u.last_name) as vendor_name, v.vendor_id,vo.vendor_quote_id,vqd.product_id,vqd.quantity,vqd.price,vqd.unit_price,p.product_category_id,pc.parent_category_id,p.product_name,p.item_no,p.unit,v.user_id as vendor_user_id FROM vendor_quotation vo 
        LEFT JOIN vendor_quotation_details vqd on vqd.vendor_quote_id = vo.vendor_quote_id
               LEFT JOIN product p on p.product_id = vqd.product_id
               LEFT JOIN product_category pc on pc.product_category_id = p.product_category_id
         LEFT JOIN vendor v on v.vendor_id = vo.vendor_id LEFT JOIN user u on u.user_id = v.user_id WHERE 1  ##WHERE## ORDER BY pc.sequence ASC';            

      public $getOrderProduct = ' SELECT p.item_no,p.product_name,p.product_id,p.unit,p.product_category_id,sod.quantity, pc.category_name FROM ship_order so
               LEFT JOIN ship_order_details sod on sod.ship_order_id = so.ship_order_id            
               LEFT JOIN product p on p.product_id = sod.product_id
               LEFT JOIN product_category pc on pc.product_category_id = p.product_category_id
            WHERE 1 ##WHERE## ORDER BY pc.sequence ASC ';    
      
      public $queryGetQuoteDetails = 'SELECT vqa.*,vq.json_data as quote_json,so.rfq_no,vq.vendor_id FROM vendor_quote_approvals vqa 
       LEFT JOIN vendor_quotation vq on vq.vendor_quote_id = vqa.vendor_quote_id
       LEFT JOIN ship_order so on so.ship_order_id = vqa.ship_order_id 
       WHERE 1 ##WHERE##';


     public $querygetPODetails = ' SELECT w.*,s.ship_name,s.imo_no,concat(u.first_name," ",u.last_name) as vendor_name,u.email as vendor_email,so.requisition_type,u1.email as captain_email,concat(u1.first_name," ",u1.last_name) as captain_name,v.user_id as vendor_user_id,s.captain_user_id,sp.name as delivery_port  FROM work_order w  
      LEFT JOIN ships s on s.ship_id = w.ship_id
      LEFT JOIN vendor v on v.vendor_id = w.vendor_id
      left join user u on u.user_id = v.user_id
      LEFT JOIN ship_order so on so.ship_order_id = w. ship_order_id
      LEFT join user u1 on u1.user_id = s.captain_user_id
      LEFT JOIN ship_ports sp on sp.port_id = so.port_id
     WHERE 1 ##WHERE##';  

     public $getDeliveryNoteNo = ' SELECT dn.note_no,dn.delivery_note_id From delivery_note dn 
     LEFT JOIN company_invoice ci ON ci.delivery_note_id = dn.delivery_note_id
     WHERE 1 AND ci.company_invoice_id IS NOT NULL AND dn.status = 2 AND dn.is_used= 0 AND dn.ship_id = ?'; 

     
    public $queryGetDeliveryNoteDetail = " SELECT dn.*,wo.json_data as work_order_json,sc.customer_id,s.ship_name,s.imo_no,sp.name as delivery_port,wo.po_no,wo.reqsn_date,sc.payment_term,v.currency
        FROM delivery_note dn 
        LEFT JOIN work_order wo on wo.work_order_id = dn.work_order_id
        LEFT JOIN vendor_quotation vq ON vq.ship_order_id = wo.ship_order_id
        LEFT JOIN vendor_quote_approvals vqa ON vqa.vendor_quote_id = vq.vendor_quote_id
        LEFT JOIN vendor v on v.vendor_id = vq.vendor_id
        LEFT join ships s on s.ship_id = wo.ship_id 
        LEFT JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id
        LEFT JOIN ship_order so on so.ship_order_id = wo.ship_order_id
        LEFT JOIN ship_ports sp on sp.port_id = so.port_id
        WHERE 1 ##WHERE## ";


    public $queryGetCurrentStock = 'SELECT cs.*,p.product_id,p.product_name,p.unit,pg.name as group_name FROM current_stock_details cs 
              LEFT JOIN product p on p.product_id = cs.product_id
              LEFT join product_category pc on pc.product_category_id = p.product_category_id
              LEFT JOIN product_group pg on pg.product_group_id = pc.product_group_id
               WHERE 1 ##WHERE##'; 
     
    public $queryGetCompanyInvoice =  'SELECT ci.*,s.ship_name,s.imo_no,sc.name as company_name,sc.address as company_address,sc.phone as company_phone,wo.reqsn_date,wo.po_no,sc.payment_term,sc.customer_id,so.requisition_type,v.currency,wo.delivery_date,s.ship_type,s.captain_user_id,sp.name as delivery_port,month(dn.date) as month,year(dn.date) as year FROM company_invoice ci 
         LEFT JOIN delivery_note dn on dn.delivery_note_id = ci.delivery_note_id
         LEFT JOIN work_order wo on wo.work_order_id = dn.work_order_id
         LEFT JOIN ship_order so on so.ship_order_id = wo.ship_order_id 
         LEFT JOIN vendor_quote_approvals vqa on vqa.ship_order_id = so.ship_order_id
         LEFT JOIN vendor_quotation vq on vq.vendor_quote_id = vqa.vendor_quote_id
         LEFT JOIN vendor v on v.vendor_id = vq.vendor_id
         LEFT JOIN ships s on s.ship_id = ci.ship_id
         LEFT JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id
         LEFT JOIN ship_ports sp on sp.port_id = so.port_id
        WHERE 1 ##WHERE##';

    public $countDeliveryNotes = 'select count(*) as count from delivery_note dn 
          LEFT JOIN work_order wo on wo.work_order_id = dn.work_order_id
          left join user u on u.user_id = dn.added_by where 1 ##WHERE##';
    
    public $querygetDeliveryNotes = 'select dn.*,concat(u.first_name," ",u.last_name) as user_name,wo.po_no from delivery_note dn 
         LEFT JOIN work_order wo on wo.work_order_id = dn.work_order_id
          left join user u on u.user_id = dn.added_by 
    where 1 ##WHERE## ##ORDER## ##LIMIT##'; 

    public $countInvoices = 'SELECT COUNT(*) as count FROM company_invoice inv 
         LEFT JOIN delivery_note dn ON dn.delivery_note_id = inv.delivery_note_id
         LEFT JOIN work_order wo on wo.work_order_id = dn.work_order_id
         LEFT JOIN vendor_quote_approvals vqa on vqa.ship_order_id = wo.ship_order_id
         LEFT JOIN vendor_quotation vq on vq.vendor_quote_id = vqa.vendor_quote_id
         LEFT JOIN vendor v on v.vendor_id = vq.vendor_id
         LEFT JOIN user u ON u.user_id = inv.created_by
         LEFT JOIN user u1 ON u1.user_id = v.user_id  
           LEFT JOIN ships s on s.ship_id = inv.ship_id
         LEFT JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id
         WHERE 1 ##WHERE## ';
    
    public $querygetInvoices = 'select inv.*,concat(u.first_name," ",u.last_name) as user_name,wo.po_no,v.currency,concat(u1.first_name," ",u1.last_name) as vendor_name,wo.note_no,s.ship_name,sc.name as company_name
         FROM company_invoice inv 
         LEFT JOIN delivery_note dn ON dn.delivery_note_id = inv.delivery_note_id
         LEFT JOIN work_order wo on wo.work_order_id = dn.work_order_id
         LEFT JOIN vendor_quote_approvals vqa on vqa.ship_order_id = wo.ship_order_id
         LEFT JOIN vendor_quotation vq on vq.vendor_quote_id = vqa.vendor_quote_id
         LEFT JOIN vendor v on v.vendor_id = vq.vendor_id 
         LEFT JOIN user u ON u.user_id = inv.created_by 
         LEFT JOIN user u1 ON u1.user_id = v.user_id 
         LEFT JOIN ships s on s.ship_id = inv.ship_id
         LEFT JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id
         WHERE 1 ##WHERE## ##ORDER## ##LIMIT##';  

    
    public $countStocks = 'select COUNT(*) as count
         FROM ship_stock st 
         LEFT JOIN user u ON u.user_id = st.created_by 
         WHERE 1 ##WHERE##  
         ';

    public $querygetStocks = 'select st.*,concat(u.first_name," ",u.last_name) as user_name,sum(std.unit_price*std.quantity) as total_amount,sum(std.quantity) as total_items
         FROM ship_stock st 
         INNER JOIN ship_stock_details std ON std.ship_stock_id = st.ship_stock_id
         LEFT JOIN user u ON u.user_id = st.created_by 
         WHERE 1 ##WHERE##  GROUP BY st.ship_stock_id ##ORDER## ##LIMIT##';

    public $querygetStocksNew = 'select st.*,concat(u.first_name," ",u.last_name) as user_name,dn.note_no,ci.price as invoice_actual_price,dn.date as delivery_date
         FROM ship_stock st 
         LEFT JOIN delivery_note dn on dn.delivery_note_id = st.delivery_note_id
         LEFT JOIN company_invoice ci on ci.delivery_note_id = dn.delivery_note_id
         LEFT JOIN user u ON u.user_id = st.created_by 
         WHERE 1 ##WHERE## ##ORDER## ##LIMIT##';

   
    public $queryGetStockDetail =  'SELECT sst.*,cm.invoice_discount,month(sst.stock_date) as month,year(sst.stock_date) as year FROM ship_stock sst LEFT JOIN delivery_note dn on dn.delivery_note_id = sst.delivery_note_id
            left join company_invoice cm on cm.delivery_note_id = dn.delivery_note_id
        WHERE 1 ##WHERE##';

    public $countConsumedStocks = 'SELECT COUNT(*) as count FROM (select COUNT(ct.consumed_stock_id)
         FROM consumed_stock ct 
         INNER JOIN consumed_stock_details ctd ON ctd.consumed_stock_id = ct.consumed_stock_id
         LEFT JOIN user u ON u.user_id = ct.added_by 
         WHERE 1 ##WHERE##  
         GROUP BY ct.consumed_stock_id ) as t1 
         ';

    public $querygetConsumedStocks = 'select ct.*,concat(u.first_name," ",u.last_name) as user_name,ctd.product_id,csd.unit_price,ctd.quantity,sum(ctd.quantity*csd.unit_price) as total_amount,sum(ctd.quantity) as total_items
         FROM consumed_stock ct 
         INNER JOIN consumed_stock_details ctd ON ctd.consumed_stock_id = ct.consumed_stock_id
         INNER JOIN current_stock_details csd ON csd.ship_id = ct.ship_id and csd.product_id = ctd.product_id
         LEFT JOIN user u ON u.user_id = ct.added_by 
         WHERE 1 ##WHERE##  
         GROUP BY ct.consumed_stock_id
         ##ORDER## ##LIMIT##';

    public $querygetConsumedStocksNew = 'select ct.*,concat(u.first_name," ",u.last_name) as user_name
         FROM consumed_stock ct 
         LEFT JOIN user u ON u.user_id = ct.added_by 
         WHERE 1 ##WHERE##  
         ##ORDER## ##LIMIT##';

    public $queryGetConsumedStockDetail =  'SELECT ppc.category_name as parent_category_name,pc.category_name,pc.product_category_id, pg.name as group_name,p.product_name, ctd.* ,(csd.unit_price*ctd.quantity) as price,p.item_no,p.unit,ct.json_data
         FROM consumed_stock ct 
         LEFT JOIN consumed_stock_details ctd ON ctd.consumed_stock_id = ct.consumed_stock_id
         INNER JOIN current_stock_details csd ON csd.ship_id = ct.ship_id and csd.product_id = ctd.product_id
         LEFT JOIN product p on p.product_id = ctd.product_id
         LEFT JOIN product_category pc on pc.product_category_id = p.product_category_id
         LEFT JOIN product_category ppc on ppc.product_category_id = pc.parent_category_id
         LEFT JOIN product_group pg on pg.product_group_id = pc.product_group_id
         LEFT JOIN user u on u.user_id = ct.added_by  WHERE 1 
         ##WHERE## 
         ';
    public $queryGetConsumedStockDetailNew =  'SELECT * 
         FROM consumed_stock ct WHERE 1 ##WHERE##';

          
   public $queryGetExtraMealsDT = 'SELECT em.*,emd.* FROM extra_meals em LEFT JOIN extra_meal_details emd on emd.extra_meal_id = em.extra_meal_id  WHERE 1 ##WHERE##';
   
   public $queryGetExtraMeals = 'SELECT em.*,concat(u.first_name," ",u.last_name) as user_name ,s.ship_name FROM extra_meals em LEFT JOIN user u on u.user_id = em.added_by left join ships s on s.ship_id = em.ship_id WHERE 1 ##WHERE## ##ORDER## ##LIMIT## ';
 
   public $countgetAllExtraMeals = 'SELECT count(*) as count FROM extra_meals em 
                                   LEFT JOIN user u on u.user_id = em.added_by 
                                   left join ships s on s.ship_id = em.ship_id 
                                   WHERE 1 ##WHERE## ';   

    public $queryGetDeliveryNoteDetailPDF = " SELECT dr.*,dn.json_data as line_data,wo.po_no,sp.name as delivery_port,wo.delivery_date,dn.note_no,wo.reqsn_date,v.currency,s.ship_name,s.imo_no,sc.customer_id,so.requisition_type,sc.payment_term,sc.name as company_name,dn.date FROM delivery_receipt dr 
                LEFT JOIN delivery_note dn on dn.delivery_note_id = dr.delivery_note_id
                LEFT JOIN work_order wo on wo.work_order_id = dn.work_order_id 
                LEFT JOIN vendor_quotation vq ON vq.ship_order_id = wo.ship_order_id
                LEFT JOIN vendor_quote_approvals vqa ON vqa.vendor_quote_id = vq.vendor_quote_id
                LEFT JOIN vendor v on v.vendor_id = vq.vendor_id
                LEFT JOIN ships s on s.ship_id = dn.ship_id
                LEFT JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id
                LEFT JOIN ship_order so on so.ship_order_id = wo.ship_order_id
                LEFT JOIN ship_ports sp on sp.port_id = so.port_id
                WHERE 1 ##WHERE##";
         
    public $queryDNByWorkOrderId = ' SELECT dn.delivery_note_id FROM delivery_note dn where 1 ##WHERE##';

    public $querygetInvoiceDetailById = 'select inv.*,s.ship_name  from company_invoice inv
        LEFT JOIN ships s on s.ship_id = inv.ship_id
     WHERE 1 ##WHERE##';

    public $querygetEmailTemplateByCode = "SELECT em.* FROM email_template em WHERE 1 AND em.status = 1 
    ##WHERE## ";
    
    public $getUserRoleTask = " SELECT rt.role_id,rt.task_id,t.code,u.user_id FROM role_task rt 
        LEFT JOIN task t on t.task_id = rt.task_id
        LEFT JOIN user_role ur on ur.role_id = rt.role_id
        LEFT JOIN role r on r.role_id = ur.role_id
        LEFT JOIN user u on u.user_id = ur.user_id WHERE 1 AND u.user_id = ? ";
    
    public $queryGetDeliveryNoteData = ' SELECT dn.*,concat(u.first_name," ",u.last_name) as vendor_name,u.email,u.phone,wo.po_no,wo.work_order_id,v.vendor_id,wo.invoice_no,v.currency,wo.reqsn_date,v.currency,sc.payment_term,sc.customer_id,so.requisition_type,s.ship_name,s.imo_no,sc.name as company_name,sc.address as company_address,s.ship_id,so.requisition_type,sp.name as delivery_port FROM delivery_note dn 
        LEFT JOIN work_order wo on wo.work_order_id = dn.work_order_id
        LEFT JOIN ship_order so on so.ship_order_id = wo.ship_order_id
        LEFT JOIN vendor_quote_approvals  vqa on vqa.ship_order_id = so.ship_order_id
        LEFT JOIN vendor_quotation vq on vq.vendor_quote_id = vqa.vendor_quote_id
        LEFT JOIN vendor v on vq.vendor_id = v.vendor_id
        LEFT JOIN ships s on s.ship_id = dn.ship_id
        LEFT JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id
        LEFT JOIN ship_ports sp on sp.port_id = so.port_id
        LEFT JOIN port_agent pa on pa.agent_id = sp.agent_id
        LEFT JOIN user u on u.user_id = v.user_id WHERE 1 ##WHERE## ';

    public $queryForMasterQuoteReview =  ' SELECT so.ship_order_id,so.rfq_no,vq.json_data,vqa.vendor_quote_id,so.json_data as ship_order_json,vq.inc_price,vq.dec_price,vq.price_remark,vq.lead_time,so.no_of_day,so.no_of_people,concat(sp.name,"(",sp.country,")") as port_name,so.requisition_type
        FROM ship_order so 
        LEFT JOIN ship_ports sp on sp.port_id = so.port_id
        LEFT JOIN vendor_quote_approvals vqa on vqa.ship_order_id = so.ship_order_id
        LEFT JOIN vendor_quotation vq on vq.vendor_quote_id = vqa.vendor_quote_id 
        WHERE 1 ##WHERE##';

    public $QuerygetProductCategoriesByShipId = "SELECT swc.product_category_id 
         from shipwise_category swc
         where 1 ##WHERE## ";

    public $getAllProductCategoryNew = "Select pc.* from product_category pc 
      inner join shipwise_category swc on swc.product_category_id = pc.product_category_id   
      WHERE 1  
      ##WHERE##  ORDER BY pc.sequence ASC";
    
    public $queryGetVendorQuotation = "SELECT vq.*,u.user_name,u.email,concat(u.first_name,' ',u.last_name) as vendor_name
         from vendor_quotation vq
         INNER JOIN vendor v ON v.vendor_id = vq.vendor_id
         INNER JOIN user u ON u.user_id = v.user_id
         where 1 ##WHERE## ";


   public $querygetVendorDataByWorkOrder = " SELECT wo.*,s.ship_name,s.imo_no,u.user_name,u.email,concat(u.first_name,' ',u.last_name) as vendor_name from work_order wo
      INNER JOIN ship_order so ON so.ship_order_id = wo.ship_order_id
       INNER JOIN vendor_quote_approvals vqa ON vqa.ship_order_id = wo.ship_order_id
       INNER JOIN vendor_quotation vq ON vq.vendor_quote_id = vqa.vendor_quote_id
       INNER JOIN vendor v ON v.vendor_id = vq.vendor_id 
       INNER JOIN ships s ON s.ship_id = wo.ship_id
       INNER JOIN user u ON u.user_id = v.user_id
       INNER JOIN delivery_note dn ON dn.work_order_id = wo.work_order_id
       where 1 ##WHERE## ";

  public $querygetSelectedQuote="select vqa.* from vendor_quote_approvals vqa where 1 ##WHERE##";


  public $querygetAllCrew = "SELECT scm.*,s.ship_name,s.imo_no,cme.e_sign,cme.created_date
                             from ship_crew_members as scm
                             inner join crew_member_entries cme on cme.crew_member_entries_id = scm.crew_member_entries_id
                             inner join ships s on s.ship_id = scm.ship_id
                             WHERE 1 
                             ##WHERE## ##ORDERBY## ##LIMIT## ";
    
  public $countQuerygetAllCrew = "SELECT count(scm.crew_members_id) as count 
      from ship_crew_members as scm 
      inner join ships s on s.ship_id = scm.ship_id 
      WHERE 1  ##WHERE##";


  public $querygetEminvoiceData = "SELECT em.*,s.ship_name,sc.name as company_name,sc.address as company_address,sc.phone as company_phone,s.victualling_rate FROM extra_meals em
                  LEFT JOIN ships s on s.ship_id=em.ship_id 
                  LEFT JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id WHERE 1 AND em.extra_meal_id = ?";

  public $querygetEmInvoiceById = "SELECT mi.*,s.ship_name,sc.name as company_name,sc.address as company_address,sc.phone as company_phone,s.victualling_rate FROM company_month_invoice mi
                  LEFT JOIN ships s on s.ship_id=mi.ship_id 
                  LEFT JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id WHERE 1 AND mi.extra_meal_id = ? ";

  public $querygetCookAndCaptain = " SELECT s.ship_name,s.cook_user_id,s.captain_user_id, concat(u.first_name,' ',u.last_name) as cook_name,concat(u1.first_name,' ',u1.last_name) as captain_name FROM ships s  
                   left join user u on u.user_id = s.cook_user_id 
                   left join user u1 on u1.user_id = s.captain_user_id where 1 ##WHERE##";                                          
 
 public $querygetAllFoodHabits = "SELECT cfh.*,scm.ship_id,scm.given_name,scm.rank,scm.gender,scm.date_of_birth,scm.nationality,month(cme.entry_date) as month,year(cme.entry_date) as year
                             from crew_food_habits cfh
                             inner join ship_crew_members scm on scm.crew_members_id = cfh.crew_member_id 
                             inner join crew_member_entries cme on cme.crew_member_entries_id = scm.crew_member_entries_id
                             inner join ships s on s.ship_id = scm.ship_id
                             WHERE 1 
                             ##WHERE## ##ORDERBY## ##LIMIT## ";
    
public $countQuerygetAllFoodHabits = "SELECT count(cfh.crew_food_habits_id) as count 
                                  from crew_food_habits cfh
                                  inner join ship_crew_members scm on scm.crew_members_id = cfh.crew_member_id 
                                  inner join ships s on s.ship_id = scm.ship_id 
                                  WHERE 1  ##WHERE##";

public $querygetAllCrewEntries = "SELECT cme.*,s.ship_id,s.ship_name,s.imo_no,concat(u.first_name,' ',u.last_name)  as imported_by
         from crew_member_entries as cme
         inner join ships s on s.ship_id = cme.ship_id
         left join user u on u.user_id = cme.created_by
         WHERE 1 
         ##WHERE## ##ORDERBY## ##LIMIT## ";
    
  public $countQuerygetAllCrewEntries = "SELECT count(cme.crew_member_entries_id ) as count 
              from crew_member_entries as cme 
              inner join ships s on s.ship_id = cme.ship_id 
              left join user u on u.user_id = cme.created_by
                                  WHERE 1  ##WHERE##";
  
  public $getDuplicateCrewEntry = "SELECT cme.crew_member_entries_id
                                   FROM crew_member_entries cme               
                                   WHERE 1 AND cme.ship_id = ? AND month(cme.entry_date)  = ? AND year (cme.entry_date)  = ? ";
   


  public $countgetAllSummaryReport = "select count(*) as count from victualing_summary vs LEFT JOIN user u on u.user_id = vs.created_by 
 left join ships s on s.ship_id = vs.ship_id
  WHERE 1 ##WHERE##";
  
  public $querygetAllSummaryReport = "select vs.*,concat(u.first_name,' ',u.last_name) as user_name,s.ship_name from victualing_summary vs LEFT JOIN user u on u.user_id = vs.created_by left join ships s on s.ship_id = vs.ship_id WHERE 1  ##WHERE## ##ORDER## ##LIMIT##"; 

  public $queryAllCondemnedStockReportData = "SELECT cr.*,concat(u.first_name,' ',u.last_name) as user_name,s.ship_name,s.imo_no 
        FROM condemned_report cr
        LEFT JOIN condemned_report_details crd ON crd.condemned_report_id = cr.condemned_report_id
        LEFT JOIN ships s on s.ship_id = cr.ship_id 
        LEFT JOIN user u on u.user_id = cr.created_by 
        WHERE 1 ##WHERE##
        GROUP BY cr.condemned_report_id 
        ##ORDERBY## ##LIMIT##";
   
  public $countQuerygetAllCondemnedStockReportData = 'SELECT COUNT(*) as count FROM (SELECT count( DISTINCT cr.condemned_report_id) as count 
        FROM condemned_report cr
        LEFT JOIN condemned_report_details crd ON crd.condemned_report_id = cr.condemned_report_id
        LEFT JOIN ships s on s.ship_id = cr.ship_id 
        LEFT JOIN user u on u.user_id = cr.created_by 
        WHERE 1 ##WHERE##
        GROUP BY cr.condemned_report_id  ) as t1       
        ';
  
  public $queryGetAssignedShiptoCaptain = 'SELECT s.ship_name,s.imo_no,s.ship_id FROM `ships` s
           WHERE captain_user_id = ? ';
  
  public $queryGetAssignedShiptoCook = 'SELECT s.ship_name,s.imo_no,s.ship_id FROM `ships` s
           WHERE cook_user_id = ? ';

  public $CountgetallvendorOrder = 'select count(*) as count from vendor_quotation vq  
   left join ship_order so on so.ship_order_id = vq.ship_order_id
   left join user u on u.user_id = vq.added_by
      left join ships s on s.ship_id = so.ship_id
  WHERE 1 ##WHERE##';

  public $getallvendorOrder = 'select vq.*,so.rfq_no,vq.status,so.json_data as order_data,concat(u.first_name," ",u.last_name) as user_name,vq.json_data as vendor_data,so.status as stage,s.ship_name,concat(sp.name,"(",sp.country,")") as port_name from vendor_quotation vq 
   left join ship_order so on so.ship_order_id = vq.ship_order_id
   left join ship_ports sp on sp.port_id = so.port_id
   left join user u on u.user_id = vq.added_by
   left join ships s on s.ship_id = so.ship_id
  WHERE 1 ##WHERE## ##ORDER## ##LIMIT##';
  

  public $countVendorInvoices = "SELECT count(*) as count from vendor_invoice vi 
      LEFT JOIN work_order wo on wo.work_order_id = vi.work_order_id 
      LEFT JOIN vendor v on v.vendor_id = vi.vendor_id
      LEFT JOIN user u on u.user_id = v.user_id
      LEFT JOIN ships s on s.ship_id = wo.ship_id
      LEFT JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id
      LEFT JOIN user u1 on u1.user_id = vi.created_by WHERE 1 ##WHERE##";

  public $querygetVendorInvoices = " SELECT vi.*,concat(u.first_name,' ',u.last_name) as vendor_name,concat(u1.first_name,' ',u1.last_name) as user_name,wo.po_no,s.ship_name,sc.name as company_name from vendor_invoice vi 
      LEFT JOIN work_order wo on wo.work_order_id = vi.work_order_id 
      LEFT JOIN vendor v on v.vendor_id = vi.vendor_id
      LEFT JOIN user u on u.user_id = v.user_id
      LEFT JOIN ships s on s.ship_id = wo.ship_id
      LEFT JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id
      LEFT JOIN user u1 on u1.user_id = vi.created_by WHERE 1 ##WHERE## ##ORDER## ##LIMIT## ";

  public $queryGetVendorInvoiceDetails = "SELECT vi.*,wo.created_on as order_date,s.ship_name,wo.delivery_port from vendor_invoice vi 
      LEFT JOIN work_order wo on wo.work_order_id = vi.work_order_id 
      LEFT JOIN vendor v on v.vendor_id = vi.vendor_id
      LEFT JOIN user u on u.user_id = v.user_id
      LEFT JOIN user u1 on u1.user_id = vi.created_by 
      LEFT JOIN ships s on s.ship_id = wo.ship_id
      WHERE 1 ##WHERE##";
 
  public $countAllInvoiceTransaction = " SELECT count(*) as count FROM invoice_transaction t 
       Left Join company_invoice sci on sci.company_invoice_id = t.company_invoice_id
       LEFT JOIN delivery_note dn on dn.delivery_note_id = sci.delivery_note_id
       LEFT JOIN work_order wo on wo.work_order_id = dn.work_order_id
      LEFT JOIN vendor_quote_approvals vqa on vqa.ship_order_id = wo.ship_order_id
       LEFT JOIN vendor_quotation vq on vq.vendor_quote_id = vqa.vendor_quote_id
       LEFT JOIN vendor v on v.vendor_id = vq.vendor_id
       LEFT JOIN user u on u.user_id = v.user_id
        Left Join ships s on s.ship_id = sci.ship_id 
        Left Join shipping_company sc on sc.shipping_company_id = s.shipping_company_id 
        Left Join vendor_invoice vi on vi.vendor_invoice_id = t.vendor_invoice_id
        LEFT JOIN work_order wo1 on wo1.work_order_id = vi.work_order_id
         LEFT JOIN vendor_quote_approvals vqa1 on vqa1.ship_order_id = wo1.ship_order_id
       LEFT JOIN vendor_quotation vq1 on vq1.vendor_quote_id = vqa1.vendor_quote_id
        LEFT JOIN vendor v1 on v1.vendor_id = vq1.vendor_id
        LEFT JOIN user u1 on u1.user_id = v1.user_id
        Left Join ships s1 on s1.ship_id = wo1.ship_id 
        Left Join shipping_company sc1 on sc1.shipping_company_id = s1.shipping_company_id WHERE 1 AND t.is_verified = 1 ##WHERE## ";

  public $queryGetAllInvoiceTransaction = " 
      SELECT t.*,IFNULL(sc.name,sc1.name) as company_name,IFNULL(sci.invoice_no,vi.invoice_no) as invoice_no,IFNULL(wo.po_no,wo1.po_no) as po_no,IFNULL(concat(u.first_name,' ',u.last_name),concat(u1.first_name,' ',u1.last_name)) as vendor_name,IFNULL(s.ship_name,s1.ship_name) as ship_name FROM invoice_transaction t 
       Left Join company_invoice sci on sci.company_invoice_id = t.company_invoice_id
       LEFT JOIN delivery_note dn on dn.delivery_note_id = sci.delivery_note_id
       LEFT JOIN work_order wo on wo.work_order_id = dn.work_order_id
      LEFT JOIN vendor_quote_approvals vqa on vqa.ship_order_id = wo.ship_order_id
       LEFT JOIN vendor_quotation vq on vq.vendor_quote_id = vqa.vendor_quote_id
       LEFT JOIN vendor v on v.vendor_id = vq.vendor_id
       LEFT JOIN user u on u.user_id = v.user_id
        Left Join ships s on s.ship_id = sci.ship_id 
        Left Join shipping_company sc on sc.shipping_company_id = s.shipping_company_id 
        Left Join vendor_invoice vi on vi.vendor_invoice_id = t.vendor_invoice_id
        LEFT JOIN work_order wo1 on wo1.work_order_id = vi.work_order_id
         LEFT JOIN vendor_quote_approvals vqa1 on vqa1.ship_order_id = wo1.ship_order_id
       LEFT JOIN vendor_quotation vq1 on vq1.vendor_quote_id = vqa1.vendor_quote_id
        LEFT JOIN vendor v1 on v1.vendor_id = vq1.vendor_id
        LEFT JOIN user u1 on u1.user_id = v1.user_id
        Left Join ships s1 on s1.ship_id = wo1.ship_id 
        Left Join shipping_company sc1 on sc1.shipping_company_id = s1.shipping_company_id WHERE 1 AND t.is_verified = 1 ##WHERE## ##ORDER## ##LIMIT## ";    
  
   public $getInvoiceTransHistoryById = " SELECT t.* from invoice_transaction t WHERE 1 ##WHERE##";
  
   public $getCountVendorTrans = "SELECT count(*) as count FROM invoice_transaction t  
      Left Join vendor_invoice vi on vi.vendor_invoice_id = t.vendor_invoice_id
      LEFT JOIN work_order wo on wo.work_order_id = vi.work_order_id
      Left Join ships s on s.ship_id = wo.ship_id 
      WHERE 1 ##WHERE##";
      
   public $getVendorTransData = " SELECT t.*,vi.invoice_no,wo.po_no,s.ship_name FROM invoice_transaction t  
      Left Join vendor_invoice vi on vi.vendor_invoice_id = t.vendor_invoice_id
      LEFT JOIN work_order wo on wo.work_order_id = vi.work_order_id
      Left Join ships s on s.ship_id = wo.ship_id 
      WHERE 1 ##WHERE## ##ORDER## ##LIMIT## ";

   public $querygetCompanyPendingAmount = " SELECT ci.company_invoice_id,sc.name as company_name,SUM((ci.total_price - if(ci.received_amount,ci.received_amount,0))) as amount,s.ship_name
         FROM company_invoice ci  
         INNER JOIN ships s on s.ship_id = ci.ship_id
         INNER JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id
         WHERE 1 ##WHERE## ##GROUP##";

   public $querygetCompanyReceivedAmount = "SELECT t.invoice_transaction_id, sc.name as company_name,SUM(t.amount) as amount,s.ship_name from invoice_transaction t 
         INNER JOIN company_invoice ci on ci.company_invoice_id = t.company_invoice_id
         INNER JOIN ships s on s.ship_id = ci.ship_id
         INNER JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id
        WHERE 1 ##WHERE## ##GROUP##";       

   public $querygetVendorDueAmount = " SELECT vi.vendor_invoice_id,sc.name as company_name,sum((vi.amount - if(vi.paid_amount,vi.paid_amount,0))) as due_amount,concat(u.first_name,' ',u.last_name) as vendor_name,s.ship_name from vendor_invoice vi 
      INNER JOIN work_order wo on wo.work_order_id = vi.work_order_id 
      INNER JOIN vendor v on v.vendor_id = vi.vendor_id
      INNER JOIN user u on u.user_id = v.user_id
      INNER JOIN ships s on s.ship_id = wo.ship_id
      INNER JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id
      WHERE 1 ##WHERE## ##GROUP##";

      public $querygetVendorPaidAmount = " SELECT vi.vendor_invoice_id,sc.name as company_name,sum(t.amount) as paid_amount,concat(u.first_name,' ',u.last_name) as vendor_name,s.ship_name from invoice_transaction t
      INNER JOIN vendor_invoice vi on vi.vendor_invoice_id = t.vendor_invoice_id 
      INNER JOIN work_order wo on wo.work_order_id = vi.work_order_id 
      INNER JOIN vendor v on v.vendor_id = vi.vendor_id
      INNER JOIN user u on u.user_id = v.user_id
      INNER JOIN ships s on s.ship_id = wo.ship_id
      INNER JOIN shipping_company sc on sc.shipping_company_id = s.shipping_company_id
      WHERE 1 and t.is_verified=1 ##WHERE## ##GROUP##";   


  public $querygetShipwiseProduct = "select p.*,smp.ship_id from shipwise_misc_product smp 
      INNER JOIN product p on p.product_id = smp.product_id 
      WHERE 1 ##WHERE## ORDER BY p.item_no ASC";                  

  public $querygetNotification = 'SELECT n.*,s.shipping_company_id from notification n 
  LEFT JOIN ships s on  s.ship_id = n.ship_id
  where 1 ##WHERE## ##ORDER## ##LIMIT##';

  public $queryCountNotification = 'SELECT count(*) as count from notification n where 1 ##WHERE##  ';      

  public $querygetLogActivity = "SELECT l.*,concat(u.first_name,' ',u.last_name) as user_name,u.img_url from log_activity l 
     left join user u on u.user_id = l.added_by where 1 ##WHERE## ##ORDER## ##LIMIT## "; 

  public $querygetCountLogActivity = "SELECT count(*) as count from log_activity l  left join user u on u.user_id = l.added_by where 1 ##WHERE## ";

  public $getqueryLogActivityByID = "SELECT l.* from log_activity l WHERE 1 and l.log_id = ?"; 

  public $CountPortAgents= "SELECT count(*) as count from port_agent pa where 1 ##WHERE## ";

  public $querygetPortAgents= "SELECT pa.*,concat(pa.country_code,pa.phone) as new_phone,concat(u.first_name,' ',u.last_name,' (',r.role_name,') ') as user_name from port_agent pa
      LEFT JOIN user u on u.user_id = pa.added_by
      LEFT JOIN user_role ur on ur.user_id = u.user_id
      LEFT JOIN role r on r.role_id = ur.role_id 
  where 1 ##WHERE## ##ORDER##  ##LIMIT##";

  public $querydeleteVendorQuoteDetails = 'DELETE FROM vendor_quotation_details WHERE vendor_quote_id IN (SELECT vendor_quote_id FROM vendor_quotation WHERE ship_order_id = ? )';

  public $querygetAllCounty = 'SELECT c.* FROM country c where c.status=1 ORDER BY c.name ASC ';

  public $queryGetSerialNo = " SELECT sn.serial_number from serial_number sn where 1 ##WHERE##";

  public $querygetAllEmailRoles = ' SELECT * from email_role_type ert where 1 and  ert.email_template_id = ?';

  public $querygetUserByRoleID = 'SELECT u.*,concat(u.first_name," ",u.last_name) as user_name,ur.role_id from user_role ur 
   left join user u on u.user_id = ur.user_id 
   where 1 and u.status = 1 and ur.role_id = ?';


  public $querygetAllEmailLogs = 'SELECT el.*,u.email from email_logs el
   LEFT JOIN user u on u.user_id = el.user_id 
   WHERE 1 ##WHERE## ##LIMIT##'; 


  public $querygetNotifyTemplateByCode = "SELECT nt.* FROM notification_template nt WHERE 1 AND nt.status = 1 
    ##WHERE## ";

  public $querygetAllNotifyRoles = ' SELECT * from notification_role_type nrt where 1 and  nrt.notification_template_id = ?';

  public $querygetShipDetailsByUserId = 'SELECT s.* FROM user u
   LEFT JOIN ships s on s.captain_user_id = u.user_id or s.cook_user_id = u.user_id WHERE 1 and u.user_id = ?';

  public $querygetVictualingTrans = 'SELECT st.*,sp.name as delivery_port,dn.added_on as delivery_date FROM ship_stock st 
         LEFT JOIN delivery_note dn on dn.delivery_note_id = st.delivery_note_id
         LEFT JOIN work_order wo on wo.work_order_id = dn.work_order_id
         LEFT JOIN ship_order so on so.ship_order_id = wo.ship_order_id
         LEFT JOIN ship_ports sp on sp.port_id = so.port_id
         WHERE 1 ##WHERE##'; 

  // public $querygetstockbygroup = "SELECT pg.*,SUM(c.available_stock) as qty FROM product_group pg 
  //       LEFT JOIN product_category pc on pc.product_group_id = pg.product_group_id
  //       LEFT JOIN product p on p.product_category_id = pc.product_category_id
  //       LEFT JOIN current_stock_details c on c.product_id = p.product_id
  //       WHERE 1 ##WHERE## GROUP BY pg.product_group_id " ; 


  public $querygetstockbygroup = "SELECT pg.*,SUM(msd.available_stock) as qty FROM product_group pg 
        INNER JOIN product_category pc on pc.product_group_id = pg.product_group_id
        INNER JOIN product p on p.product_category_id = pc.product_category_id
        INNER JOIN monthly_stock_details msd on msd.product_id = msd.product_id
        INNER JOIN month_stock ms on ms.month_stock_id = msd.month_stock_id
        WHERE 1 ##WHERE## GROUP BY pg.product_group_id " ;        

  public $queryGetAllTaskByGroup = 'SELECT t.*,m.name as module_name,m.module_id,sm.name as sub_module_name,rt.task_id as assigned_task_id from task t 
  INNER JOIN sub_module sm on sm.sub_module_id = t.sub_module_id  
  INNER JOIN module m on m.module_id = sm.module_id
  LEFT JOIN role_task rt on rt.task_id = t.task_id AND rt.role_id = ?
  WHERE 1 ##WHERE## group by t.task_id order by #ORDERBY# m.name asc,sm.name asc,t.name asc';             

 public $querygetreportvc = "SELECT vs.*,s.ship_name from victualing_summary vs left join ships s on s.ship_id = vs.ship_id WHERE 1  ##WHERE## Order By vs.year ASC,vs.month ASC";

 public $querygetMonthStockValue = "SELECT msv.* FROM month_stock_value msv WHERE 1  ##WHERE## "; 

 // public $querygetCountMonthMeatReport = "SELECT count(*) as count FROM month_stock_value m 
 // LEFT JOIN ships s on s.ship_id = m.ship_id
 // WHERE 1  ##WHERE## "; 
 
 // public $querygetMonthMeatReport = "SELECT m.*,s.ship_name FROM month_stock_value m
 //   LEFT JOIN ships s on s.ship_id = m.ship_id
 //  WHERE 1  ##WHERE## ##ORDER## ##LIMIT##"; 


    public $querygetCountMonthMeatReport = "SELECT count(*) as count FROM month_stock ms
       LEFT JOIN ships s on s.ship_id = ms.ship_id
        WHERE 1  ##WHERE## "; 
 
    public $querygetMonthMeatReport = "SELECT ms.*,s.ship_name FROM month_stock ms
       LEFT JOIN ships s on s.ship_id = ms.ship_id
      WHERE 1  ##WHERE## ##ORDER## ##LIMIT##"; 


    public $querygetmonthlyStockDetails = " SELECT ms.*,msd.*,p.item_no,p.product_id,p.product_name,p.unit,pc.product_category_id,pc.category_name,pg.name as group_name FROM month_stock ms 
          INNER JOIN monthly_stock_details msd on msd.month_stock_id = ms.month_stock_id 
          LEFT JOIN product p on p.product_id = msd.product_id
          LEFT JOIN product_category pc on pc.product_category_id = p.product_category_id 
          LEFT JOIN product_group pg on pg.product_group_id = pc.product_group_id
          WHERE 1 ##WHERE## ORDER BY pc.sequence ASC,p.item_no ASC"; 


    public $queryGetStockYear = " SELECT DISTINCT(ms.year) FROM month_stock ms WHERE 1 and ms.ship_id = ? ";

    public $queryGetStockMonth = " SELECT DISTINCT(ms.month) FROM month_stock ms WHERE 1 and ms.ship_id = ?  and ms.year = ?"; 

  
    public $queryGetStockValue = 'SELECT ms.opening_price,ms.closing_price,ms.month,ms.year FROM month_stock ms WHERE 1 and ms.ship_id = ? and ms.month = ? and ms.year = ? ';

    public $queryGetTmpDeliveryReceipt = " SELECT tdr.*,wo.delivery_port,s.imo_no,s.ship_name,dn.date as delivery_date,wo.po_no,wo.agent_name,wo.work_order_id,s.ship_id FROM tmp_delivery_receipt tdr
        INNER JOIN delivery_note dn on dn.delivery_note_id = tdr.delivery_note_id
        INNER JOIN work_order wo on wo.work_order_id = dn.work_order_id
        INNER JOIN ships s on s.ship_id = dn.ship_id
        WHERE 1 and tdr.tmp_delivery_receipt_id = ? ";

    public $deleteTmpDeliveryReceipt = 'DELETE FROM tmp_delivery_receipt WHERE tmp_delivery_receipt_id = ?';

    public $queryGetfeedbackByID = " SELECT f.*,wo.delivery_port,s.imo_no,s.ship_name,dn.date as delivery_date,wo.po_no,wo.agent_name FROM feedback f
        INNER JOIN delivery_note dn on dn.delivery_note_id = f.delivery_note_id
        INNER JOIN work_order wo on wo.work_order_id = dn.work_order_id
        INNER JOIN ships s on s.ship_id = dn.ship_id
        WHERE 1 AND f.delivery_note_id = ?";
        
 
    public $querygetAllNews = 'SELECT n.*,concat(u.first_name," ",u.last_name) as user_name FROM newsletter n 
    LEFT JOIN user u on u.user_id = n.added_by 
    WHERE 1  ##WHERE## ##ORDER## ##LIMIT## ';

    public $countgetAllNews = 'SELECT count(*) as count FROM newsletter n 
    LEFT JOIN user u on u.user_id = n.added_by 
    WHERE 1  ##WHERE##';


    public $querygetAllFoodMenu = 'SELECT fm.*,concat(u.first_name," ",u.last_name) as user_name FROM food_menu fm 
    LEFT JOIN user u on u.user_id = fm.added_by 
    WHERE 1  ##WHERE## ##ORDER## ##LIMIT## ';

     public $countgetAllFoodMenu = 'SELECT count(*) as count FROM food_menu fm 
    LEFT JOIN user u on u.user_id = fm.added_by 
    WHERE 1  ##WHERE##';

    public $querygetNutritionById = 'SELECT pc.category_name,ms.month,ms.year,ms.ship_id, 
    SUM(msd.total_stock) as total_stock,
    SUM(msd.available_stock) as total_available_stock,
    (SUM(msd.total_stock) - SUM(msd.available_stock)) as total_consumed,
    (SUM(msd.total_stock) - SUM(msd.available_stock)) * (n.calories / 100) AS total_calories,
    (SUM(msd.total_stock) - SUM(msd.available_stock)) * (n.protein / 100) AS total_protein,
    (SUM(msd.total_stock) - SUM(msd.available_stock)) * (n.fat / 100) AS total_fat,
    (SUM(msd.total_stock) - SUM(msd.available_stock)) * (n.saturated_fat / 100) AS total_saturated_fat,
    (SUM(msd.total_stock) - SUM(msd.available_stock)) * (n.cholesterol / 100) AS total_cholesterol,
    (SUM(msd.total_stock) - SUM(msd.available_stock)) * (n.sodium / 100) AS total_sodium,
    (SUM(msd.total_stock) - SUM(msd.available_stock)) * (n.potassium / 100) AS total_potassium,
    (SUM(msd.total_stock) - SUM(msd.available_stock)) * (n.carbohydrates / 100) AS total_carbohydrates,
    (SUM(msd.total_stock) - SUM(msd.available_stock)) * (n.iron / 100) AS total_iron,
    (SUM(msd.total_stock) - SUM(msd.available_stock)) * (n.calcium / 100) AS total_calcium
     FROM month_stock ms LEFT JOIN monthly_stock_details msd on msd.month_stock_id = ms.month_stock_id LEFT JOIN product p on p.product_id = msd.product_id LEFT JOIN nutritional n on n.product_id = p.product_id LEFT JOIN product_category pc on pc.product_category_id = p.product_category_id WHERE msd.month_stock_id = ? GROUP BY p.product_category_id';


    public $deleteFoodHabit = 'DELETE FROM crew_food_habits WHERE crew_members_id = ?';  


    public $countgetAllFoodRecipe = " SELECT count(*) as count from food_recipe fr 
        LEFT JOIN user u on u.user_id = fr.added_by 
    Where 1 ##WHERE## ";
    
    public $querygetAllFoodRecipe = " SELECT fr.*,concat(u.first_name,' ',u.last_name) as user from food_recipe fr 
        LEFT JOIN user u on u.user_id = fr.added_by
     Where 1 ##WHERE## ##ORDER## ##LIMIT##";

 }
 
 ?>