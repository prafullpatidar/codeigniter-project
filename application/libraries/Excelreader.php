<?php
/**
 * ExcelreaderFS Class
 *
 * with the help of this library you can create excel file
 *
 * @package     Library
 * @subpackage  Excel reader
 * @category    Excel reader
 * @author      Dev Team
 * @link        -
 */
include 'Excel/PHPExcel/IOFactory.php';
include 'Excel/PHPExcel/Writer/Excel2007.php';
include 'Excel/PHPExcel/Cell/AdvancedValueBinder.php';
class Excelreader
{	
     /**
     * createExcel function
     * create the excel file 
     *
     * @param   string $param1 fileName
     * @param   array $param2 arrayData
     * @param   array $param3 listColumn
     * @param   string $param4 title
     * @return  void
     */
		function createExcel($fileName,$arrayData,$listColumn,$title){
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		// Set properties
		$objPHPExcel->getProperties()->setCreator("Azure");
		$objPHPExcel->getProperties()->setTitle($title);
		$objPHPExcel->getProperties()->setSubject($title);
		$objPHPExcel->getProperties()->setDescription("This is xlsx file have use data import in Azure with given header information.");
		$objPHPExcel->getActiveSheet()->setTitle($title);
		
		$colArray = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
		$objPHPExcel->setActiveSheetIndex(0);
		$col = 0;
		if(!empty($arrayData)){
			foreach($arrayData as $keys=>$values){
				foreach($values as $index=>$list){
					$exp = explode('/',$list);
					if(count($exp) == 3 && $exp[2] > 1900){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index,$keys,$list);
						PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
						$objPHPExcel->getActiveSheet()->getStyle($colArray[$index].$keys)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
					}else{
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index,$keys,$list);
					}
				}
			}
			
			foreach($listColumn as $list){
				if($list['format'] == 'number'){
					for($i=1;$i<=count($arrayData);$i++){
						$objPHPExcel->getActiveSheet()->getStyle($colArray[$list['column']].$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					//$objPHPExcel->getActiveSheet()->getStyle($list['column'])->getNumberFormat()->setFormatCode('#,##0.00');
					}
				}
				
				if($list['format'] == 'header'){
					for($i=0;$i<count($arrayData[$list['row']]);$i++){
						if($list['border'] == 'THIN'){
							$border = PHPExcel_Style_Border::BORDER_DASHED;
							$style = PHPExcel_Style_Fill::FILL_SOLID;
						}else{
							$border = PHPExcel_Style_Border::BORDER_DASHED;
							$style = '';
						}
						
						$objPHPExcel->getActiveSheet()->getStyle($colArray[$i].$list['row'])->applyFromArray(
								array(
										'fill' => array(
												'style' => $border,
												'type' => $style,
												'color' => array('rgb' => $list['color'])
										),
										'font' => array(
												'bold' => $list['font'],
												'color' => array('rgb' => $list['font-color'])
										)
								)
						);
					}
				}
			}

		}
		
		// Save Excel 2007 file
		//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		//$objWriter->save('CSV/'.$fileName, __FILE__);
		foreach (range('A', 'Z') as $letra) {            
            $objPHPExcel->getActiveSheet()->getColumnDimension($letra)->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(1);

        }

		$fileType = 'Excel5';
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
		//$objWriter->save('/var/www/azure_dashboard/CSV/'.$fileName);
		$objWriter->save(FCPATH.'/uploads/sheets'.$fileName);;
		/*
		 * For given download option
		 * header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		   header('Content-Disposition: attachment; filename="'.$fileName.'"');
		   header("Cache-control: private");
		*/
		
		/*
		 * for 2007
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save('temp/'.$fileName, __FILE__);
		
		//$objWriter->save('php://output');
		//$objWriter->save('/var/www/sgsaas/uploads/'.str_replace('.php','.xlsx', __FILE__));
		//$objWriter->save('/var/www/kumolawdev/temp/'.$fileName);
		//$objWriter->save($_SERVER['DOCUMENT_ROOT'].'/kumolaw/temp/'.$fileName);
		 */
	}
	
		function createExcelWithFormat($fileName,$arrayData,$listColumn,$title,$backgroudColor=''){
	
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
	
		// Set properties
		$objPHPExcel->getProperties()->setCreator("Azure");
		$objPHPExcel->getProperties()->setTitle($title);
		$objPHPExcel->getProperties()->setSubject($title);
		$objPHPExcel->getProperties()->setDescription("This is xlsx file have use data import in Azure with given header information.");
		$objPHPExcel->getActiveSheet()->setTitle($title);
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');
		
		if($backgroudColor != ''){
			$objPHPExcel->getDefaultStyle()->applyFromArray(
					array(
							'fill' => array(
									'type'  => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => $backgroudColor)
							),
					)
			);
		}
	
		$colArray = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
		$objPHPExcel->setActiveSheetIndex(0);
		$col = 0;
		if(!empty($arrayData)){
			foreach($arrayData as $keys=>$values){
				foreach($values['data'] as $index=>$list){
					$objPHPExcel->getActiveSheet()->setCellValue($colArray[$index].$keys,$list);
					$objPHPExcel->getActiveSheet()->getStyle($colArray[$index].$keys)->getAlignment()->setWrapText(true);
				}
				if(!empty($values['format'])){
					if(count($values['format']) > 0){
					foreach($values['format'] as $list){
						if(!empty($list['format'])){
							
						if($list['format'] == 'style'){
							$style = array(
									'font'  => $list['font']
							);
							foreach($list['cell'] as $cells){
								$objPHPExcel->getActiveSheet()->getStyle($cells.$keys)->applyFromArray($style);
							}
						}
						
						if($list['format'] == 'rowheight'){
							$objPHPExcel->getActiveSheet()->getRowDimension($keys)->setRowHeight($list['rowheight']);
						}
						
						if($list['format'] == 'cellcolor'){
							foreach($list['cellcolor'] as $cellcolor){
								if($cellcolor['border'] == 'THIN'){
									$border = PHPExcel_Style_Border::BORDER_THIN;
									$style = PHPExcel_Style_Fill::FILL_SOLID;
								}else{
									$border = PHPExcel_Style_Border::BORDER_THICK;
									$style = '';
								}
								$style = array(
										'alignment' => array(
												'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
										),
										'fill' => array(
												'type' => PHPExcel_Style_Fill::FILL_SOLID,
												'color' => array('rgb' => $cellcolor['color'])
										),
										'borders' => array(
												'bottom'     => array(
														'type' => PHPExcel_Style_Fill::FILL_SOLID,
														'style' => $border,
														'color' => array(
																'rgb' => '000000'
														)
												),
												'right'     => array(
														'type' => PHPExcel_Style_Fill::FILL_SOLID,
														'style' => $border,
														'color' => array(
																'rgb' => '000000'
														)
												),'left'     => array(
														'type' => PHPExcel_Style_Fill::FILL_SOLID,
														'style' => $border,
														'color' => array(
																'rgb' => '000000'
														)
												),'top'     => array(
														'type' => PHPExcel_Style_Fill::FILL_SOLID,
														'style' => $border,
														'color' => array(
																'rgb' => '000000'
														)
												),)
								);
								$objPHPExcel->getActiveSheet()->getStyle($cellcolor['cell'].$keys)->applyFromArray($style);
							}
						}
						}
					}
					}
				}
			}
			foreach($listColumn as $list){
				if($list['format'] == 'number'){
					foreach($list['number'] as $number){
						$objPHPExcel->getActiveSheet()->getStyle($number['cell'])->getNumberFormat()->setFormatCode($number['decimal']);
					}
				}
	
				if($list['format'] == 'mergeRow'){
					foreach($list['mergeRow'] as $mergeRow){
						$objPHPExcel->getActiveSheet()->mergeCells($mergeRow['cell']);
						if(!empty($mergeRow['borders'])){
							$border = $mergeRow['borders'];
						}else{
							$border = array();
						}
						$style = array(
								'alignment' =>$mergeRow['alignment'],
								'font'  => $mergeRow['font'],
								'borders'=>$border
						);
						$objPHPExcel->getActiveSheet()->getStyle($mergeRow['cell'])->applyFromArray($style);
					}
				}
	
				if($list['format'] == 'cellwidth'){
					foreach($list['cellwidth'] as $cellwidth){
						$expCellwidth  = explode(':',$cellwidth);
						$objPHPExcel->getActiveSheet()->getColumnDimension($expCellwidth[0])->setWidth($expCellwidth[1]);
					}
				}
	
				if($list['format'] == 'cellAlign'){
					foreach($list['cellArray'] as $cells){
						$style = array('alignment' => $cells['alignment']);
						$objPHPExcel->getActiveSheet()->getStyle($cells['cell'])->applyFromArray($style);
					}
				}
				
				if($list['format'] == 'wraptext'){
					foreach($list['cellArray'] as $cells){
						$objPHPExcel->getActiveSheet()->getStyle($cells['cell'])->getAlignment()->setWrapText(true);
					}
				}
				
				if($list['format'] == 'cellcolor'){
					foreach($list['cellcolor'] as $cellcolor){
						if($cellcolor['border'] == 'THIN'){
							$border = PHPExcel_Style_Border::BORDER_THIN;
							$style = PHPExcel_Style_Fill::FILL_SOLID;
						}else{
							$border = PHPExcel_Style_Border::BORDER_THICK;
							$style = '';
						}
						$style = array(
								'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
								),
								'fill' => array(
										'type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => $cellcolor['color'])
								),
								'borders' => array(
										'bottom'     => array(
												'type' => PHPExcel_Style_Fill::FILL_SOLID,
												'style' => $border,
												'color' => array(
														'rgb' => '000000'
												)
										),
										'right'     => array(
												'type' => PHPExcel_Style_Fill::FILL_SOLID,
												'style' => $border,
												'color' => array(
														'rgb' => '000000'
												)
										),'left'     => array(
												'type' => PHPExcel_Style_Fill::FILL_SOLID,
												'style' => $border,
												'color' => array(
														'rgb' => '000000'
												)
										),'top'     => array(
												'type' => PHPExcel_Style_Fill::FILL_SOLID,
												'style' => $border,
												'color' => array(
														'rgb' => '000000'
												)
										),)
						);
						$objPHPExcel->getActiveSheet()->getStyle($cellcolor['cell'])->applyFromArray($style);
					}
				}
	
				if($list['format'] == 'header'){
					for($i=0;$i<count($arrayData[$list['row']]);$i++){
						if($list['border'] == 'THIN'){
							$border = PHPExcel_Style_Border::BORDER_DASHED;
							$style = PHPExcel_Style_Fill::FILL_SOLID;
						}else{
							$border = PHPExcel_Style_Border::BORDER_DASHED;
							$style = '';
						}
	
						$objPHPExcel->getActiveSheet()->getStyle($colArray[$i].$list['row'])->applyFromArray(
								array(
										'fill' => array(
												'style' => $border,
												'type' => $style,
												'color' => array('rgb' => $list['color'])
										),
										'font' => array(
												'bold' => $list['font'],
												'color' => array('rgb' => $list['font-color'])
										)
								)
						);
					}
				}
			}
	
		}
	
		// Save Excel 2007 file
		//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		//$objWriter->save('CSV/'.$fileName, __FILE__);
		$fileType = 'Excel5';
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
		//$objWriter->save('/var/www/azure_dashboard/CSV/'.$fileName);
		$objWriter->save('./CSV/'.$fileName);
		//$objWriter->save('/var/www/azure_dashboard/CSV'.$fileName);
		/*
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$fileName.'"');
		header("Cache-control: private");
		
		
		// for 2007
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save('temp/'.$fileName, __FILE__);
	
		//$objWriter->save('php://output');
		//$objWriter->save('/var/www/sgsaas/uploads/'.str_replace('.php','.xlsx', __FILE__));
		//$objWriter->save('/var/www/kumolawdev/temp/'.$fileName);
		//$objWriter->save($_SERVER['DOCUMENT_ROOT'].'/kumolaw/temp/'.$fileName);
		*/
	}
	
	function downloadExcel($fileName,$arrayData,$listColumn,$title){
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
	
		// Set properties
		$objPHPExcel->getProperties()->setCreator("Azure");
		$objPHPExcel->getProperties()->setTitle($title);
		$objPHPExcel->getProperties()->setSubject($title);
		$objPHPExcel->getProperties()->setDescription("This is xlsx file have use data import in Azure with given header information.");
		$objPHPExcel->getActiveSheet()->setTitle($title);
	
		$colArray = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
		$objPHPExcel->setActiveSheetIndex(0);
		$col = 0;
		if(!empty($arrayData)){
			foreach($arrayData as $keys=>$values){
				foreach($values as $index=>$list){
					$exp = explode('/',$list);
					if(count($exp) == 3 && $exp[2] > 1900){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index,$keys,$list);
						PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
						$objPHPExcel->getActiveSheet()->getStyle($colArray[$index].$keys)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
					}else{
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index,$keys,$list);
					}
				}
			}
				
			foreach($listColumn as $list){
				if($list['format'] == 'number'){
					for($i=1;$i<=count($arrayData);$i++){
						$objPHPExcel->getActiveSheet()->getStyle($colArray[$list['column']].$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
						//$objPHPExcel->getActiveSheet()->getStyle($list['column'])->getNumberFormat()->setFormatCode('#,##0.00');
					}
				}

                if($list['format'] == 'percent')
                {
                    foreach($list['cellArray'] as $cells)
                    {
                        $objPHPExcel->getActiveSheet()->getStyle($cells)->getNumberFormat()->applyFromArray( 
                            array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00)
                        );
                    }                                    
                }
				
				if($list['format'] == 'mergeRow'){
					foreach($list['mergeRow'] as $mergeRow){
						$objPHPExcel->getActiveSheet()->mergeCells($mergeRow['cell']);
						$style = array(
								'alignment' =>$mergeRow['alignment'],
								'font'  => $mergeRow['font']
						);
						$objPHPExcel->getActiveSheet()->getStyle($mergeRow['cell'])->applyFromArray($style);
					}
				}
				
				if($list['format'] == 'cellwidth'){
					foreach($list['cellwidth'] as $cellwidth){
						$expCellwidth  = explode(':',$cellwidth);
						$objPHPExcel->getActiveSheet()->getColumnDimension($expCellwidth[0])->setWidth($expCellwidth[1]);
					}
				}
				
				if($list['format'] == 'cellAlign'){
					foreach($list['cellArray'] as $cells){
						$style = array('alignment' => $cells['alignment']);
						$objPHPExcel->getActiveSheet()->getStyle()->applyFromArray($style);
					}
				}
				
				if($list['format'] == 'wraptext'){
					foreach($list['cellArray'] as $cells){
						$objPHPExcel->getActiveSheet()->getStyle($cells['cell'])->getAlignment()->setWrapText(true);
					}
				}


				
				if($list['format'] == 'cellcolor'){
					foreach($list['cellcolor'] as $cellcolor){
						if($cellcolor['border'] == 'THIN'){
							$border = PHPExcel_Style_Border::BORDER_THIN;
							$style = PHPExcel_Style_Fill::FILL_SOLID;
						}else{
							$border = PHPExcel_Style_Border::BORDER_THICK;
							$style = '';
						}
						$style = array(
								'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
								),
								// 'fill' => array(
								// 		'type' => PHPExcel_Style_Fill::FILL_SOLID,
								// 		'color' => array('rgb' => $cellcolor['color'])
								// ),
								'borders' => array(
									'allborders' =>
									      array(
												'type' => PHPExcel_Style_Fill::FILL_SOLID,
												'style' => $border,
												'color' => array(
														'rgb' => '4F2270'
												)
										),
										// 'bottom'     => array(
										// 		'type' => PHPExcel_Style_Fill::FILL_SOLID,
										// 		'style' => $border,
										// 		'color' => array(
										// 				'rgb' => '000000'
										// 		)
										// ),
										// 'right'     => array(
										// 		'type' => PHPExcel_Style_Fill::FILL_SOLID,
										// 		'style' => $border,
										// 		'color' => array(
										// 				'rgb' => '000000'
										// 		)
										// ),'left'     => array(
										// 		'type' => PHPExcel_Style_Fill::FILL_SOLID,
										// 		'style' => $border,
										// 		'color' => array(
										// 				'rgb' => '000000'
										// 		)
										// ),'top'     => array(
										// 		'type' => PHPExcel_Style_Fill::FILL_SOLID,
										// 		'style' => $border,
										// 		'color' => array(
										// 				'rgb' => '000000'
										// 		)
										// )
									)
						);
						$objPHPExcel->getActiveSheet()->getStyle($cellcolor['cell'])->applyFromArray($style);
						
					}
				}
				
				if($list['format'] == 'header'){
					for($i=0;$i<count($arrayData[$list['row']]);$i++){
						if($list['border'] == 'THIN'){
							$border = PHPExcel_Style_Border::BORDER_DASHED;
							$style = PHPExcel_Style_Fill::FILL_SOLID;
						}else{
							$border = PHPExcel_Style_Border::BORDER_DASHED;
							$style = '';
						}
	
						$objPHPExcel->getActiveSheet()->getStyle($colArray[$i].$list['row'])->applyFromArray(
								array(
										'fill' => array(
												'style' => $border,
												'type' => $style,
												'color' => array('rgb' => $list['color'])
										),
										'font' => array(
												'bold' => $list['font'],
												'color' => array('rgb' => $list['font-color'])
										)
								)
						);
					}
				}

				if($list['format'] == 'rowheight'){
					$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight($list['rowheight']);
                    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth($list['colwidth']);
				}
				if($list['format'] == 'cellFontStyle'){
					foreach ($list['cellArray'] as $cells) {
						$objPHPExcel->getActiveSheet()->getStyle($cells)->applyFromArray($list['styleArray']);
					}
				}
				if($list['addImage'] == '1'){
					$objDrawing = new PHPExcel_Worksheet_Drawing();
					$objDrawing->setPath(getcwd().'/assets/images/company_logo.png');
					($list['height'])?$objDrawing->setHeight($list['height']):$objDrawing->setHeight(150);
					$objDrawing->setOffsetX(100);                            
                    // $objDrawing->setOffsetY(50); 
					$objDrawing->setCoordinates($list['coordinates']);
					$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());              
			   }

		   }
	
		}
            
			/*
				//Example start
				// Add some data to the second sheet, resembling some different data types
					$objPHPExcel->createSheet();

				// Add some data to the second sheet, resembling some different data types
					$objPHPExcel->setActiveSheetIndex(1);
					$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');
				// Rename 2nd sheet
					$objPHPExcel->getActiveSheet()->setTitle('Second sheet');

				//End example

			*/
		
	
		// Save Excel 2007 file
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save(FCPATH.'uploads/sheets/'.$fileName);

		 $fileType = 'Excel5';
		 //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
		//$objWriter->save('/var/www/azure_dashboard/CSV/'.$fileName);
		 //$objWriter->save(FCPATH.'uploads/sheets/'.$fileName);

		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$fileName.'"');
		header("Cache-control: private");
	
		/*
		* for 2007
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objWriter->save('temp/'.$fileName, __FILE__);
	
		//$objWriter->save('php://output');
		//$objWriter->save('/var/www/sgsaas/uploads/'.str_replace('.php','.xlsx', __FILE__));
		//$objWriter->save('/var/www/kumolawdev/temp/'.$fileName);
		//$objWriter->save($_SERVER['DOCUMENT_ROOT'].'/kumolaw/temp/'.$fileName);
		*/
		}
		        
       	function multiDownloadExcel($fileName,$arrayData,$listColumn,$title){
                //error_reporting(E_ALL);
		//ini_set('display_errors', 1);
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
	
		// Set properties
		$objPHPExcel->getProperties()->setCreator("franchiseSoft");
		$objPHPExcel->getProperties()->setTitle('Merged File');
		$objPHPExcel->getProperties()->setSubject('Merged File');
		$objPHPExcel->getProperties()->setDescription("This is xlsx file have use data import in Franchisesoft with given header information.");                
                
                for($i = 0; $i<count($arrayData);$i++)
                {
                    if($i != 0)     
                    { $objPHPExcel->createSheet(); }
                    
                    $colArray = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
                    $objPHPExcel->setActiveSheetIndex($i);
                    $col = 0;
                    if(!empty($arrayData[$i]))
                    {
			foreach($arrayData[$i] as $keys=>$values){
				foreach($values as $index=>$list){
					$exp = explode('/',$list);
					if(count($exp) == 3 && $exp[2] > 1900){
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index,$keys,$list);
						PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );
						$objPHPExcel->getActiveSheet()->getStyle($colArray[$index].$keys)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
					}else{
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($index,$keys,$list);
					}
				}
			}
				
			foreach($listColumn[$i] as $list){
				if($list['format'] == 'number'){
					for($i=1;$i<=count($arrayData[$i]);$i++){
						$objPHPExcel->getActiveSheet()->getStyle($colArray[$list['column']].$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
						//$objPHPExcel->getActiveSheet()->getStyle($list['column'])->getNumberFormat()->setFormatCode('#,##0.00');
					}
				}
				
				if($list['format'] == 'mergeRow'){
					foreach($list['mergeRow'] as $mergeRow){
						$objPHPExcel->getActiveSheet()->mergeCells($mergeRow['cell']);
						$style = array(
								'alignment' =>$mergeRow['alignment'],
								'font'  => $mergeRow['font']
						);
						$objPHPExcel->getActiveSheet()->getStyle($mergeRow['cell'])->applyFromArray($style);
					}
				}
				
				if($list['format'] == 'cellwidth'){
					foreach($list['cellwidth'] as $cellwidth){
						$expCellwidth  = explode(':',$cellwidth);
						$objPHPExcel->getActiveSheet()->getColumnDimension($expCellwidth[0])->setWidth($expCellwidth[1]);
					}
				}
				
				if($list['format'] == 'cellAlign'){
					foreach($list['cellArray'] as $cells){
						$style = array('alignment' => $cells['alignment']);
						$objPHPExcel->getActiveSheet()->getStyle($cells['cell'])->applyFromArray($style);
					}
				}
				
				if($list['format'] == 'wraptext'){
					foreach($list['cellArray'] as $cells){
						$objPHPExcel->getActiveSheet()->getStyle($cells['cell'])->getAlignment()->setWrapText(true);
					}
				}                                
                                
				
				if($list['format'] == 'cellcolor'){
					foreach($list['cellcolor'] as $cellcolor){
						if($cellcolor['border'] == 'THIN'){
							$border = PHPExcel_Style_Border::BORDER_THIN;
							$style = PHPExcel_Style_Fill::FILL_SOLID;
						}else{
							$border = PHPExcel_Style_Border::BORDER_THICK;
							$style = '';
						}
						$style = array(

								'alignment' => array(
										'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
								),
								'fill' => array(
										'type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => $cellcolor['color'])
								),
								'borders' => array(
										'bottom'     => array(
												'type' => PHPExcel_Style_Fill::FILL_SOLID,
												'style' => $border,
												'color' => array(
														'rgb' => '000000'
												)
										),
										'right'     => array(
												'type' => PHPExcel_Style_Fill::FILL_SOLID,
												'style' => $border,
												'color' => array(
														'rgb' => '000000'
												)
										),'left'     => array(
												'type' => PHPExcel_Style_Fill::FILL_SOLID,
												'style' => $border,
												'color' => array(
														'rgb' => '000000'
												)
										),'top'     => array(
												'type' => PHPExcel_Style_Fill::FILL_SOLID,
												'style' => $border,
												'color' => array(
														'rgb' => '000000'
												)
										),)
						);

						$objPHPExcel->getActiveSheet()->getStyle($cellcolor['cell'])->applyFromArray($style);
					}
				}
				
				if($list['format'] == 'header'){
					for($i=0;$i<count($arrayData[$i][$list['row']]);$i++){
						if($list['border'] == 'THIN'){
							$border = PHPExcel_Style_Border::BORDER_DASHED;
							$style = PHPExcel_Style_Fill::FILL_SOLID;
						}else{
							$border = PHPExcel_Style_Border::BORDER_DASHED;
							$style = '';
						}
	
						$objPHPExcel->getActiveSheet()->getStyle($colArray[$i].$list['row'])->applyFromArray(
								array(
										'fill' => array(
												'style' => $border,
												'type' => $style,
												'color' => array('rgb' => $list['color'])
										),
										'font' => array(
												'bold' => $list['font'],
												'color' => array('rgb' => $list['font-color'])
										)
								)
						);
					}
				}
                                
                                if($list['format'] == 'percent')
                                {
                                    foreach($list['cellArray'] as $cells)
                                    {
                                        $objPHPExcel->getActiveSheet()->getStyle($cells)->getNumberFormat()->applyFromArray( 
                                            array('code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00)
                                        );
                                    }                                    
                                }
			}
	
		}
                $objPHPExcel->getActiveSheet()->setTitle($title[$i]);
                }
/*
	//Example start
	// Add some data to the second sheet, resembling some different data types
		$objPHPExcel->createSheet();

	// Add some data to the second sheet, resembling some different data types
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');
	// Rename 2nd sheet
		$objPHPExcel->getActiveSheet()->setTitle('Second sheet');

	//End example

*/		
		$fileType = 'Excel5';
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);		
		$objWriter->save('./uploads/export/'.$fileName);

		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$fileName.'"');
		header("Cache-control: private");
    }
	
	   	function readExcel($fileName,$ext){
		if($ext == 'xls'){
			$fileType = 'Excel5';
		} else if($ext == 'xlsx'){
			$fileType = 'Excel2007';
		}else{
			return array();
		}
		
		//	$inputFileType = 'Excel2007';
		//	$inputFileType = 'Excel2003XML';
		//	$inputFileType = 'OOCalc';
		//	$inputFileType = 'SYLK';
		//	$inputFileType = 'Gnumeric';
		//	$inputFileType = 'CSV';
		$objReader = PHPExcel_IOFactory::createReader($fileType);
		$objPHPExcel = $objReader->load($fileName);
		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		return $sheetData;
	}
	
		function highestColumn($fileName,$ext){
		if($ext == 'xls'){
			$fileType = 'Excel5';
		} else if($ext == 'xlsx'){
			$fileType = 'Excel2007';
		}else{
			return array();
		}
		$objReader = PHPExcel_IOFactory::createReader($fileType);
		$objPHPExcel = $objReader->load($fileName);
		$highestColumm = $objPHPExcel->setActiveSheetIndex(0)->getHighestDataColumn();
		return $highestColumm;
	}
	
		function writeExcel($fileName,$arrayData,$listColumn){
		$fileType = 'Excel5';
		//	$inputFileType = 'Excel2007';
		//	$inputFileType = 'Excel2003XML';
		//	$inputFileType = 'OOCalc';
		//	$inputFileType = 'SYLK';
		//	$inputFileType = 'Gnumeric';
		//	$inputFileType = 'CSV';
		$objReader = PHPExcel_IOFactory::createReader($fileType);
		$objPHPExcel = $objReader->load($fileName);
		$colArray = array('A1','B1','C1','D1','E1','F1','G1','H1','I1','J1','K1','L1','M1','N1','O1','P1','Q1','R1','S1','T1','U1','V1','W1','X1','Y1','Z1','AA1','AB1','AC1','AD1','AE1','AF1','AG1','AH1','AI1','AJ1','AK1','AL1','AM1','AN1','AO1','AP1','AQ1','AR1','AS1','AT1','AU1','AV1','AW1','AX1','AY1','AZ1');
		$objPHPExcel->setActiveSheetIndex(0);
		$col = 0;
		if(!empty($arrayData)){
			foreach($arrayData as $fields){
				$objPHPExcel->getActiveSheet()->setCellValue($colArray[$col], $fields);
				$col++;
			}
			foreach($listColumn as $list){
					$objValidation = $objPHPExcel->getActiveSheet()->getCell($list['column'].'2')->getDataValidation();
					$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
					$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
					$objValidation->setAllowBlank(false);
					$objValidation->setShowInputMessage(true);
					$objValidation->setShowErrorMessage(true);
					$objValidation->setShowDropDown(false);
					$objValidation->setErrorTitle('Input');
					$objValidation->setError('Value is not in list.');
					$objValidation->setPromptTitle('Insert value from this list');
					$objValidation->setPrompt($list['list']);
			}
		}
		//exit();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $fileType);
		$objWriter->save($fileName);
	}
}
?>