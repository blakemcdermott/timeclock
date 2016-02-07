<?php
function printFieldData($field){
foreach($field as $key => $value){

	if(!is_array($value)){
	echo $key." => ".$value."<br />";
	} else {
		echo $key." => <br />";
			foreach($value as $xkey => $xvalue){
				foreach($xvalue as $ykey => $yvalue){
				echo ''.$ykey." => ".$yvalue."<br />";
				}
		
			}
		
	}
}

}

function serializedArray($value){
				$x=0;
				foreach($value as $ykey => $yvalue){
					if($x < 1){
				echo $ykey."=".$yvalue;
				} else {
				echo "&".$ykey."=".$yvalue;
				}
				$x++;
				}
			
}

function removeCommas($array){
	foreach($array as $key => $value){
	$cleanData[$key] = preg_replace('/\,/', "", $value);
	}
	return $cleanData;
}

# This function will return data from standard variables or single keys/values from an array
function rt($data){
	if(!is_array($data)){
return $data;		
	} else {
$xvalue = $data;
			foreach($xvalue as $ykey => $yvalue){
				return $yvalue;
				}
	}
}

# Code Benchmark Functions
function startTimer(){
	$t = microtime(true);
	$micro = sprintf("%06d",($t - floor($t)) * 1000000);
	$d = new DateTime( date('H:i:s.'.$micro,$t) );
	$start =  $d->format("H:i:s.u");
	return $start;
}
function stopTimer($stop){
	$t = microtime(true);
	$micro = sprintf("%06d",($t - floor($t)) * 1000000);
	$d = new DateTime( date('H:i:s.'.$micro,$t) );
	$stop =  $d->format("H:i:s.u");
	return $stop;
}

function countTimer ($start, $stop){
	
	list($startseconds, $startmicroseconds) = explode(".", $start);
	list($stopseconds, $stopmicroseconds) = explode(".", $stop);

	$hours = $stophours - $starthours;
	$minutes = $stopminutes - $startminutes;
	$seconds = $stopseconds - $startseconds;
	$microseconds = $stopmicroseconds - $startmicroseconds;
	$dif = $seconds . "." . $microseconds;
	echo "Page Results (". $dif." seconds)<br />\n";
	
}

class formBuilder {
	
var $formName;
var $fieldCount = 0;
var $fieldJsCount = 0;
var $attrJsCount = 0;
var $fieldName;
var $fieldType;
var $sectionTitle;
var $numberCell;
var $inputCell;
var $inputBox;
var $labelCell;
var $fieldSize;
var $selectOptions;

private $countFields;
private $layoutType;
private $repeatAxis;
public $fields, $fieldjs, $fieldattrjs;




	
	function __construct($formName,$submitTo,$defaultSize=10) {
	$this->formName = $formName;
	$this->fieldSize = $defaultSize;
	echo '<form action="'.$submitTo.'" id="'.$formName.'" method="post" >';
	
	}

			### Helper function ###
			
			
			function fieldCounter(){
			$newCount = $this->fieldCount + 1;
			$this->fieldCount = $newCount;
			}
			
			function fieldJsCounter(){
			$newCount = $this->fieldJsCount + 1;
			$this->fieldJsCount = $newCount;
			}
			
			function attrJsCounter(){
			$newCount = $this->attrJsCount + 1;
			$this->attrJsCount = $newCount;
			}
			
			function fieldStore($name,$dataType,$setid,$type,$fieldSize){
				$this->fieldName = $name;
				$id = ( $setid == TRUE )? $setid : $this->inputIdBuilder($name);
						
			$fields = array(
						   "id" => $id,
						   "name" => $name,
						   "type" => $type,
						   "format" => $dataType,
						   "size" => $fieldSize
						   );
			$this->fields[$this->fieldName] = $fields ;
			
			}
			
			function fieldAttrStore($array, $function, $attribute){
				$x=1;
			foreach($array as $key => $value){
			$options[$x] = array ($value => $key);
				$x++;
			}
			
			$this->fields[$this->fieldName]["options"] = $options;
			$this->fieldattrjs[$this->attrJsCount] = $this->jqueryBuilder($array, $function, $attribute);
			}
			
			function jqueryBuilder($array, $function, $attribute){
			$this->attrJsCounter();
			switch($attribute){	
			case "options":	
			array_shift($array);
				switch($function){
				case "linked":
				$x=1;
				foreach($array as $key => $value){
					if($x == 1){
					$openJquery =	'$("#'.$this->fields[$this->fieldName]["id"].'").change(function(){'."\n";
					$openJquery .= 'var option = $(this).val();'."\n";
					$options[$x] = $openJquery;
					$x++;
					}
				
				$options[$x]["event"] = '$("#'.$value.'").hide();'."\n";
				$options[$x]["condition"] = 'if(option == "'.$value.'"){$("#'.$value.'").fadeIn();}'."\n";
				$x++;
				}
				$options[$x] = ';});'."\n";			
				break;
				
				}
			return $options;
			break;
			
			}
			
			}
			
			public function jqueryPrinter($select){
				switch($select){
					### jQuery all code print out
					case "all":
					try{
			foreach($this->fieldattrjs as $key => $value){
				if(!is_array($key)){
				foreach($value as $xkey => $xvalue){
					if(!is_array($xvalue)){
						echo $xvalue;
					}	else{
					foreach($xvalue as $ykey => $yvalue){
						echo $yvalue;
						}
						}
					}
				} 
			}			
			} catch (Exception $e) {var_dump($e->getMessage());}
					break;
					### jQuery event code print out
					case "event":
					try{
					foreach($this->fieldattrjs as $field => $attrJs){
						foreach($attrJs as $attrJsNum => $jsCode){
							if(is_array($jsCode)){
								foreach($jsCode as $type => $typeCode){
									if($type == "event"){
									echo $typeCode; 
									}
								}
							}
						}
					
					}
					} catch (Exception $e) {var_dump($e->getMessage());}
					break;
				}
			}
						
			function sectionTitle($title,$cssClass="title"){
			$titleHtml = '<p class="'.$cssClass.'">'.$title.'</p>';
			$this->sectionTitle = $titleHtml;
			echo $this->sectionTitle."\n";
			
			}
			
			function numberCell($cssClass="numberCol"){
				
				if ( $this->countFields ) {
					switch($this->layoutType){
						case "div":
							$numberHtml = '<div class="'.$cssClass.'">'.$this->fieldCount.'</div>';
							$this->numberCell = $numberHtml;
							echo $this->numberCell."\n";
						break;
						case "table":
							$numberHtml = '<td class="'.$cssClass.'">'.$this->fieldCount.'</td>';
							$this->numberCell = $numberHtml;
							echo $this->numberCell."\n";
						break;
					
					}
					
				}
			}
			
			function labelCell($cssClass="labelCol") {
				
				switch($this->layoutType){
						case "div":
							$labelHtml ='<div class="'.$cssClass.'">'.$this->fields[$this->fieldName]['name'].'</div>';
							$this->labelCell = $labelHtml;
							echo $this->labelCell."\n";
						break;
						case "table":
							$labelHtml ='<td class="'.$cssClass.'">'.$this->fields[$this->fieldName]['name'].'</td>';
							$this->labelCell = $labelHtml;
							echo $this->labelCell."\n";
						break;
					
				}
				
				
			
			}
			
					function inputIdBuilder($name) {
					$string = strtolower(trim($name));
					$patterns[0] = '/\s+/';
					$patterns[1] = '/\W+/';
					$replacements[0] = '_';
					$replacements[1] = '';
					$id = preg_replace($patterns, $replacements, $string);
					return $id;
					}
			
			function inputCell($fieldSize=12,$cssClass="inputCol"){
			if($this->fields[$this->fieldName]["format"] == "money"){ /* $money = "<span style=\"color:#00d966;\">$</span> ";	*/ }	
			switch($this->fields[$this->fieldName]["type"]){
			case "text":
				switch($this->layoutType){
						case "div":
							$inputDivHtml = '<div class="'.$cssClass.'">'.$money.$this->input($fieldSize).'</div>';
							$this->inputCell = $inputDivHtml;
							echo $this->inputCell."\n";
							echo '<br class="clear"/>'."\n";
						break;
						case "table":
							$inputHtml = '<td class="'.$cssClass.'">'.$money.$this->input($fieldSize).'</td>';
							$this->inputCell = $inputHtml;
							echo $this->inputCell."\n";
						break;
					
				}
			break;
			
			case "select";
				switch($this->layoutType){
						case "div":
							$inputDivHtml = '<div class="'.$cssClass.'">'."\n";
							$this->inputCell = $inputDivHtml;
						break;
						case "table":
							$inputHtml = '<td class="'.$cssClass.'">'."\n";
							$this->inputCell = $inputHtml;
						break;
					
				}
			break;
			
			}
			}
			
			function input($fieldSize){
				
				switch($this->fields[$this->fieldName]["type"]){
				case "text":
					### Data formating - only format standard input fields for now
					switch($this->fields[$this->fieldName]["format"]){
					case "int":
					# some code				
					break;
					case "decimal":
					# some code				
					break;
					case "money":
					$this->fieldJsCounter();
					$js = "$(\"#".$this->fields[$this->fieldName]["id"]."\").change(function(){\n";
					$js .= "var number = number_format($(this).val(), 2, '.');\n";
					$js .= "$(this).val(number);});\n";
					
					$fields = $field_js;
					$this->fieldjs[$this->fieldJsCount]["js"] = $js;
					break;
					case "varchar":
					# some code				
					break;
					}
										
				$inputHtml = '<input type="'.$this->fields[$this->fieldName]["type"].'" size="'.$fieldSize.'" name="'.$this->fields[$this->fieldName]["id"].'" id="'.$this->fields[$this->fieldName]["id"].'"  />';
				$this->inputBox = $inputHtml;
				return $this->inputBox;
				break;
					
				case "select":
				
				break;
				}
				
				
			}
	
					### Main calling functions ###
					private $firstField;
					  private $secondField;
					
					  public function __get($property) {
						if (property_exists($this, $property)) {
						  return $this->$property;
						}
					  }
					
					  public function __set($property, $value) {
						if (property_exists($this, $property)) {
						  $this->$property = $value;
						}
					
						return $this;
					  }
					
					function linkElement($data){
						if(is_array($data)){
						
						}
						
					}
					function openGroup($groupid){
					echo '<span id="'.$this->inputIdBuilder($groupid).'">'."\n";
					}
					function closeGroup(){
					echo '</span>'."\n";
					}
										
					function openSection($title,$id,$width=250,$repeatAxis="y",$cssClass="formbuilder-form"){
					$sectionGroup = '<span id="'.$id.$this->inputIdBuilder($title).'">';
						switch($this->layoutType){
							case "div":
								$sectionHead = '<div class="'.$cssClass.' '.$title.'" style="width:'.$width.'px;">';
								echo $sectionGroup."\n";
								echo $sectionHead."\n";
								$this->sectionTitle($title);
							break;
							case "table":
							echo ( $repeatAxis == "y" ) ? "<tr>" : "" ;
								$sectionHead = '<td class="'.$cssClass.' '.$title.'" >';
								echo $sectionGroup."\n";
								echo $sectionHead."\n";
								$this->sectionTitle($title);
							break;
						
						}
					
					
					}
					
					function newField($name,$dataType="",$id="",$type="text",$fieldSize=12){
					$this->fieldType = $type;
					$this->fieldCounter();
					$this->fieldStore($name,$dataType,$id,$type,$fieldSize);
					
					$this->numberCell();	
						switch($this->layoutType){
							case "div":
								echo '<div class="number-cell" >';
								$this->labelCell();
								$this->inputCell($fieldSize);
								echo ($this->fieldType != "select") ? "</div>" : "" ;
							break;
							case "table":
								echo '<td class="number-cell" >';
								$this->labelCell();
								$this->inputCell($fieldSize);
								echo "</td>";
							break;
						
						}
					
					}
					
					function selectOptions($array,$function="unlinked",$attribute="options"){
					$this->fieldAttrStore($array,$function,$attribute);
					
					$selectHtml = "\n<select name=\"".$this->fields[$this->fieldName]["id"]."\" id=\"".$this->fields[$this->fieldName]["id"]."\" size=\"1\">\n";
					$this->selectOptions = $array;
					foreach($array as $key => $value){
					$selectHtml .= "<option value=\"".$value."\">".$key."</option>\n";
					}
					$selectHtml .= "</select>";
					$this->inputCell .= $selectHtml;
					
						switch($this->layoutType){
							case "div":
								$this->inputCell .= '</div>'."\n";
								$this->inputCell .= '<br class="clear"/>'."\n";
								echo $this->inputCell;
								echo "</div>";
							break;
							case "table":
								$this->inputCell .= '</td>'."\n";
								echo $this->inputCell;
							break;
						
						}
					
					}
					
					function closeSection(){
						switch($this->layoutType){
							case "div":
								echo "</div>\n";
								echo "</span>\n";
							break;
							case "table":
								echo "</td>\n";
								echo ( $repeatAxis == "y" ) ? "</tr>" : "" ;
								echo "</span>\n";
							break;
						
						}
						
						
						
					}
					
					public function printJs_old(){
					echo "<script type=\"text/javascript\">";
					echo "$(document).ready(function(){";
					for($x=1; $x <= $this->fieldCount; $x++){
					print_r($this->fields[$x][js]); 
					}
					echo "});";
					echo "</script>";
					}
					
					public function printJs(){
					echo "<script type=\"text/javascript\">";
					echo "$(document).ready(function(){";
					for($x=1; $x <= $this->fieldJsCount; $x++){
					print_r($this->fieldjs[$x]["js"]); 
					}
					$this->jqueryPrinter("event");
					$this->jqueryPrinter("all");
					echo "});";
					echo "</script>";
					}
					
					
					function formButton($name,$id=""){
						$id = $id.$this->inputIdBuilder($name);
						
							if($id == "submit"){
							$buttonHtml = '<input type="submit" name="'.$id.'" id="'.$id.'" value="'.$name.'" />';
							echo $buttonHtml."\n";
							}
							else {
							$buttonHtml = '<input type="button" name="'.$id.'" id="'.$id.'" value="'.$name.'" />';
							echo $buttonHtml."\n";
							}
					}
					
					function closeForm(){
					echo "</form>\n";
					}


}

?>