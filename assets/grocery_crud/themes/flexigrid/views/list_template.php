<?php
	$this->set_css($this->default_theme_path.'/flexigrid/css/flexigrid.css');
	$this->set_js_lib($this->default_javascript_path.'/'.grocery_CRUD::JQUERY);

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
	$this->set_js_lib($this->default_javascript_path.'/common/lazyload-min.js');

	if (!$this->is_IE7()) {
		$this->set_js_lib($this->default_javascript_path.'/common/list.js');
	}

	$this->set_js($this->default_theme_path.'/flexigrid/js/cookies.js');
	$this->set_js($this->default_theme_path.'/flexigrid/js/flexigrid.js');

    $this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.form.min.js');

	$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.numeric.min.js');
	$this->set_js($this->default_theme_path.'/flexigrid/js/jquery.printElement.min.js');

	/** Fancybox */
	$this->set_css($this->default_css_path.'/jquery_plugins/fancybox/jquery.fancybox.css');
	$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.fancybox-1.3.4.js');
	$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.easing-1.3.pack.js');

	/** Jquery UI */
	$this->load_js_jqueryui();

?>
<script type='text/javascript'>
	var base_url = '<?php echo base_url();?>';

	var subject = '<?php echo addslashes($subject); ?>';
	var ajax_list_info_url = '<?php echo $ajax_list_info_url; ?>';
	var unique_hash = '<?php echo $unique_hash; ?>';

	var message_alert_delete = "<?php echo $this->l('alert_delete'); ?>";

</script>
<div id='list-report-error' class='report-div error'></div>
<div id='list-report-success' class='report-div success report-list' <?php if($success_message !== null){?>style="display:block"<?php }?>><?php
if($success_message !== null){?>
	<p><?php echo $success_message; ?></p>
<?php }
?></div>
<div class="flexigrid" style='width: 100%;' data-unique-hash="<?php echo $unique_hash; ?>">
	<div id="hidden-operations" class="hidden-operations"></div>
	<div class="mDiv">
		<div class="ftitle">
			&nbsp;
		</div>
		<div title="<?php echo $this->l('minimize_maximize');?>" class="ptogtitle">
			<span></span>
		</div>
	</div>
	<div id='main-table-box' class="main-table-box">

	<?php if(!$unset_add || !$unset_export || !$unset_print){?>
	<div class="tDiv">
		<?php if(!$unset_add){?>
		<div class="tDiv2">
        	<a href='<?php echo $add_url?>' title='<?php echo $this->l('list_add'); ?> <?php echo $subject?>' class='add-anchor add_button'>
			<div class="fbutton">
				<div>
					<span class="add"><?php echo $this->l('list_add'); ?> <?php echo $subject?></span>
				</div>
			</div>
            </a>
			<div class="btnseparator">
			</div>
		</div>
		<?php }?>
		<div class="tDiv3">
			<?php if(!$unset_export) { ?>
        	<a class="export-anchor" data-url="<?php echo $export_url; ?>" target="_blank">
				<div class="fbutton">
					<div>
						<span class="export"><?php echo $this->l('list_export');?></span>
					</div>
				</div>
            </a>
			<div class="btnseparator"></div>
			<?php } ?>
			<?php if(!$unset_print) { ?>
        	<a class="print-anchor" data-url="<?php echo $print_url; ?>">
				<div class="fbutton">
					<div>
						<span class="print"><?php echo $this->l('list_print');?></span>
					</div>
				</div>
            </a>
			<div class="btnseparator"></div>
			<?php }?>
		</div>
		<div class='clear'></div>
	</div>
	<?php }?>

	<div id='ajax_list' class="ajax_list">
		<?php echo $list_view?>
	</div>
<?php
	if(!$multisearch){
?>
<!-- Original -->

	<?php echo form_open( $ajax_list_url, 'method="post" id="filtering_form" class="filtering_form" autocomplete = "off" data-ajax-list-info-url="'.$ajax_list_info_url.'"'); ?>


	<div class="sDiv quickSearchBox" id='quickSearchBox'>
		<div class="sDiv2">
			<?php echo $this->l('list_search');?>: <input type="text" class="qsbsearch_fieldox search_text" name="search_text" size="30" id='search_text'>
			<select name="search_field" id="search_field">
				<option value=""><?php echo $this->l('list_search_all');?></option>
				<?php foreach($columns as $column){?>
				<option value="<?php echo $column->field_name?>"><?php echo $column->display_as?>&nbsp;&nbsp;</option>
				<?php }?>
			</select>
            <input type="button" value="<?php echo $this->l('list_search');?>" class="crud_search" id='crud_search'>
		</div>
        <div class='search-div-clear-button'>
        	<input type="button" value="<?php echo $this->l('list_clear_filtering');?>" id='search_clear' class="search_clear">
        </div>
	</div>

<!-- Original Ends-->
<?php
	}else{
?>


<!--- Template modification starts--->
	<?php echo form_open( $ajax_list_url, 'method="post" id="filtering_form" data="'.$unique_hash.'" class="filtering_form" autocomplete = "off" data-ajax-list-info-url="'.$ajax_list_info_url.'"'); ?>


	<div class="sDiv quickSearchBox" id='quickSearchBox'>

<!-------------------------- Edit-1 starts -------------------------------------------------------------------------------------------------------------->
		<table>
		   <tr>
			<td>
				Basic Search &nbsp;&nbsp;
			</td>
			<td>
			   <input class="search_type_<?php echo $unique_hash; ?>" type="radio" name="radio_search_type" value="basic" checked="checked"/>
                        </td>
                        <td>
                            &nbsp;&nbsp; || &nbsp;&nbsp;
                        </td>
			<td>
				Advanced Search &nbsp;&nbsp;
			</td>
			<td>
				<input class="search_type_<?php echo $unique_hash; ?>" type="radio" name="radio_search_type" value="advanced"/>
			</td>
		   </tr>
		</table>
		<input type="hidden" class="main_search_type_<?php echo $unique_hash; ?>" name="search_type" />
<!--------------------------- Edit-1 ends -------------------------------------------------------------------------------------------------------------->

		<div class="sDiv2">

		 <div id="basic_search_<?php echo $unique_hash; ?>">
			<?php echo $this->l('list_search');?>: <input type="text" class="qsbsearch_fieldox search_text" name="search_text" size="30" id='search_text'>
			<select name="search_field" id="search_field">
				<option value=""><?php echo $this->l('list_search_all');?></option>
				<?php foreach($columns_for_search as $column){?>
				<option value="<?php echo $column->field_name?>"><?php echo $column->display_as?>&nbsp;&nbsp;</option>
				<?php }?>
			</select>

			 <input  type="button" value="<?php echo $this->l('list_search');?>" class="crud_search" id='crud_search'>
		</div>	
	
<!-------------------------- Edit-2 starts ------------------------------------------------------------------------------------------------------------->
		<div style="display:none;" id="advanced_search_<?php echo $unique_hash; ?>">
			<table>
			  <?php $c=0;  foreach($columns_for_search as $column){
					if(!array_key_exists($column->field_name,$option_type) )continue;
					$class = $other_class = ""; $extra_attr = null;
					if( $option_type[$column->field_name] =="numeric_opts")
					{
						$type  = "numeric"; // Input type to be used
						$other_class = $class = "numeric";
					}
                                       else
					{
						$type = "text"; // Input type to be used
						if($option_type[$column->field_name] == "date_and_time")
						{
							// picker to be used
							$class = "time_inputs"; 
							if(in_array($types[$column->field_name]->type,array('timestamp','datetime')))
							{
							  // datetimepicker to be used
							  $other_class="timepicker_".$column->field_name;
							}else
							{
							  // datepicker to be used
							  $other_class="datepicker_".$column->field_name;
							}
							
							// disable user editing, only allow picker
							 $extra_attr = 'readonly="readonly"';
						}
					}
			?>
			  <tr>
				<!-- Field Name -->
			 	<td><?php echo $column->display_as;?></td>
				
				<!-- Field Operation dropdown -->
			 	<td>
					<select class="option_selection_common <?php echo $class;?>" data="<?php echo $column->field_name;?>" name="ADV_operation_<?php echo $column->field_name;?>"  id="ADV_data_<?php echo $column->field_name;?>">
						<?php echo $options[$option_type[$column->field_name]];?>
					</select>
				</td>
				<!-- User Input field -->
				<td class="cell_of_<?php echo $column->field_name;?>">

					<!-- Single Input -->
					<div class="<?php echo $column->field_name;?>_main">
                                         <!-- allcell data to test-->
					  <input style="display:none;" <?php echo (is_null($extra_attr)?"":$extra_attr); ?> class="<?php echo $other_class;?>   allcelldata_<?php echo $unique_hash; ?>"  name="ADV_data_<?php echo $column->field_name?>" type="<?php echo $type; ?>" />
                                        <!-- end test-->
					</div>

				      <?php if($other_class !=""){?>
					<!-- Double Input -->
					<div style="display:none;" class="<?php echo $column->field_name;?>_sub">
					 <table>
						<tr>
							<td>
							  <input style="display:none;" class="<?php echo $other_class?>_from" <?php echo (is_null($extra_attr)?"":$extra_attr); ?>  name="ADV_data_<?php echo $column->field_name?>_from"/>
						        </td>
							<td>
							   <input style="display:none;" class="<?php echo $other_class?>_to" <?php echo (is_null($extra_attr)?"":$extra_attr); ?>  name="ADV_data_<?php echo $column->field_name?>_to"/>
							</td>
						</tr>
					 </table>			
					</div>
					<?php } ?>
				</td>
				<!-- Logic dropdown with next field -->
				<td>
					<?php if(++$c < count($columns)){ ?>  
						<select  name="ADV_logic_<?php echo $column->field_name;?>"><?php echo $options["logic"];?></select>
					<?php }else { ?>
						&nbsp;&nbsp;
					<?php }?>
				</td>
				<td>
					<?php if(array_key_exists($column->field_name, $field_property) && array_key_exists("help",$field_property[$column->field_name])){ echo $field_property[$column->field_name]["help"]; }else {?>
				&nbsp; &nbsp; <?php };?>
				</td>
			  </tr>
				<?php  }?>
			</table>
			 <input  type="button" value="<?php echo $this->l('list_search');?>" class="crud_search" id='crud_search'>
		</div>
<!------------------------------------------------------ Edit-2 ends ------------------------------------------------------------------------------>
           
		</div> <!-- end of class sDiv2 -->

<!-------------------------- Edit-3 starts ------------------------------------------------------------------------------------------------------------->

<script>
$(document).ready(function(){

var field_property = <?php echo json_encode($field_property);?>;

// Get type of input
$.fn.getType = function(){ return this[0].tagName == "INPUT" ? this[0].type.toLowerCase() : this[0].tagName.toLowerCase(); }

// Date picker for range selection
var dates=function(options)
{

	var from  = options[0],
	    to    = options[1], start = "", end = "",datefmt='yy-mm-dd';

	if(2 in options)
	{
		start  = ("start"      in options[2] && (options[2].start !== null) ? options[2].start.split(" ")[0] : ""   );
		end    = ("end"        in options[2] && (options[2].start !== null) ? options[2].end.split(" ")[0]   : ""   );
		datefmt = ("dateFormat" in options[2] && (options[2].dateFormat !== null) ? options[2].dateFormat : datefmt );
	};


	var from_opts = {
        	dateFormat: datefmt,
       	 	changeMonth: true,
        	changeYear: true,
        	numberOfMonths: 1,
        	yearRange: '1800:2050',
        	onSelect: function (selectedDate) {
            	if (selectedDate) {
                	to.datepicker("option", "minDate", selectedDate);
            	}
        }
       }, to_opts = {
        	dateFormat: datefmt,
        	changeMonth: true,
        	changeYear: true,
        	numberOfMonths: 1,
        	yearRange: '1800:2050',
       		onSelect: function (selectedDate) {
            	if (selectedDate) {
                	from.datepicker("option", "maxDate", selectedDate);
            }
        }
      };

	if(start.length && end.length)
	{
		from_opts.maxDate = new Date(end);
		from_opts.minDate = new Date(start);
		to_opts.maxDate= new Date(end);
		to_opts.minDate= 0;
	}
	
    from.datepicker("destroy");
    to.datepicker("destroy");

    from.datepicker(from_opts).addClass("datepicker_range");
    to.datepicker(to_opts).addClass("datepicker_range");

}

// mysqldate to js date - fmt example : 2015-06-26 09:00:00
var mysqltimestamp2javascript=function(str)
{
	var t = str.split(/[- :]/);
	return new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
}

// Datetimepicker for range selection
var times=function(options)
{

	var startDateTextBox =  options[0],
	    endDateTextBox   =  options[1], istart = "", iend = "",datefmt='yy-mm-dd',timefmt='HH:mm:ss';

	if(2 in options)
	{
		istart  = ("start"      in options[2] && (options[2].start !== null) ? options[2].start : ""   );
		iend    = ("end"        in options[2] && (options[2].start !== null) ? options[2].end   : ""   );
		datefmt = ("dateFormat" in options[2] && (options[2].dateFormat !== null) ? options[2].dateFormat : datefmt );
		timefmt = ("timeFormat" in options[2] && (options[2].timeFormat !== null) ? options[2].timeFormat : timefmt );
	}

	

	var start_opts = {
        			dateFormat: datefmt,
				timeFormat: timefmt,
        			changeMonth: true,
        			changeYear: true,
        			numberOfMonths: 1,
				yearRange: '1800:2050',
				onSelect: function (selectedDateTime)
				{
					var start = $(this).datetimepicker('getDate');
					endDateTextBox.datetimepicker('option', 'minDate', start);
					endDateTextBox.datetimepicker('option', 'minDateTime', new Date(start.getTime()));
				}
	},end_opts = {
        			dateFormat: datefmt,
				timeFormat: timefmt,
        			changeMonth: true,
        			changeYear: true,
        			numberOfMonths: 1,
        			yearRange: '1800:2050',
				onSelect: function (selectedDateTime)
				{
					var end = $(this).datetimepicker('getDate');
					startDateTextBox.datetimepicker('option', 'maxDate', end);
					startDateTextBox.datetimepicker('option', 'maxDateTime', new Date(end.getTime()) );
				}
	};
	
	if(istart.length && iend.length)
	{
		istart = mysqltimestamp2javascript(istart);
		iend   = mysqltimestamp2javascript(iend);

		start_opts.maxDate = new Date(iend),
        	start_opts.minDate = new Date(istart),
		start_opts.minDateTime = new Date(istart.getTime()),
		start_opts.maxDateTime = new Date(iend.getTime());

		end_opts.maxDate = new Date(iend),
        	end_opts.minDate = 0,
		end_opts.minDateTime = 0,
		end_opts.maxDateTime = new Date(iend.getTime());	
	}


	startDateTextBox.datepicker("destroy");
	endDateTextBox.datepicker("destroy");
	startDateTextBox.datetimepicker("destroy");
	endDateTextBox.datetimepicker("destroy");

	startDateTextBox.datetimepicker(start_opts).addClass('datetimepicker_range');
	endDateTextBox.datetimepicker(end_opts).addClass('datetimepicker_range');


}

		// Change in first radion search selection
		$(".search_type_<?php echo $unique_hash; ?>").on("change",function()
		{ 
			// Set hidden input of search type
			$(".main_search_type_<?php echo $unique_hash; ?>").val(this.value);

			// if basic
			if(this.value == "basic")
			{ 
				// show basic division
				$("#basic_search_<?php echo $unique_hash; ?>").show(); 

				// hide advanced division
				$("#advanced_search_<?php echo $unique_hash; ?>").hide();
			}

			// if advanced
			if(this.value == "advanced")
			{ 
				// hide basic division
				$("#basic_search_<?php echo $unique_hash; ?>").hide(); 

				// show advanced division
				$("#advanced_search_<?php echo $unique_hash; ?>").show();
			}
		});
                
                //-----------------------------------------------------------------------------------------------------------------------------------
                //
                var trigger =false;
                $('.allcelldata_'+unique_hash).each(function() {
                                   if(readCookie($(this)[0]['name']+'_'+unique_hash)!==null)
                                   {  
                                       if($(".search_type_"+unique_hash).val()==='basic')
                                       {
                                            //click to change value from basic to advanced
                                            $(".search_type_"+unique_hash).click();
                          
                                       }

                                    $('#'+$(this)[0]['name']).val(readCookie($(this)[0]['name']+'_'+unique_hash).split('_')[0]);

                                   $('[name ='+$(this)[0]['name']+']').val(readCookie($(this)[0]['name']+'_'+unique_hash).split('_').slice(1).join('_'));
                                   $('[name ='+$(this)[0]['name']+']').show();
                                      trigger =true;
                                  }
                        });
               
                        if(trigger)
                        $('#filtering_form').trigger('submit');
     
		// Unique id - unchanged
		var adv_id = "#advanced_search_<?php echo $unique_hash; ?>";


		// Change in first dropdown
		$(adv_id+" .option_selection_common").on("change",function()
		{
			// extract value from data attr - which gives field name
			var attr = $(this).attr("data");

			// if dropdown selected is none
			if(this.value == "none")
			{
				// hide userinput field table cells all inputs
				$(adv_id+" .cell_of_"+attr+" input").hide();
			}else
			{
				// show userinput field table cells all inputs
				$(adv_id+" .cell_of_"+attr+" input").show();
			}

			// if option clicked one is between
			if(this.value == "BETWEEN")
			{	
				// Hide single input division
				$(adv_id+" ."+attr+"_main").hide(); 

				// show double input division
				$(adv_id+" ."+attr+"_sub").show(); 
				
				// show all inputs of double input division
				$(adv_id+" ."+attr+"_sub").show().children().find('input').show();

			
				// If has class time_inputs
				if($(this).hasClass("time_inputs"))
				{
				     // temp array
				     var dopts=[],topts=[];

				     // class with datepicker_fieldname_from exists 
				     if($(adv_id+" .datepicker_"+attr+"_from").length)
				     {
					// if not activated before
					if(!($(adv_id+" .timepicker_"+attr+"_from.datepicker_range").length))
					{
					 	dopts=[
					       		$(adv_id+" .datepicker_"+attr+"_from"),
					       		$(adv_id+" .datepicker_"+attr+"_to"),
					       		( (attr in field_property) ? field_property[attr] : {} )
					      	      ];
						dates(dopts);
					}
				     }

				     // class with timepicker_fieldname_from exists
				     if( $(adv_id+" .timepicker_"+attr+"_from").length)
				     {
					// if not activated before
					if(!($(adv_id+" .timepicker_"+attr+"_from.datetimepicker_range").length))
					{ 
						topts=[
					       		$(adv_id+" .timepicker_"+attr+"_from"),
					       		$(adv_id+" .timepicker_"+attr+"_to"),
					       		( (attr in field_property) ? field_property[attr] : {} )
					      	      ];
						times(topts);
					}
				      }
				}

			}else
			{
				// show single input division
				$(adv_id+" ."+attr+"_main").show(); 

				// hide double input division
				$(adv_id+" ."+attr+"_sub").hide(); 

				// If has class time_inputs
				if($(this).hasClass("time_inputs"))
				{
				     // Setup basic options
				     var dateopts ={
						dateFormat: 'yy-mm-dd',
						changeMonth: true,
        					changeYear: true,
        					numberOfMonths: 1,
						yearRange: '1800:2050'
					},timeopts={
						dateFormat: 'yy-mm-dd',
						timeFormat: 'HH:mm:ss',
						changeMonth: true,
        					changeYear: true,
        					numberOfMonths: 1,
						yearRange: '1800:2050'
					}, other_lists = ["IN","NOT IN","LIKE","NOT LIKE"]; 
			
					// if field_property exists
					if(attr in field_property)	
					{
						var obj = field_property[attr]; 

						if("start" in obj && obj["start"] !== null && "end" in obj && obj["end"] !== null)
						{
							dateopts.minDate = new Date( obj["start"].split(" ")[0]);
							dateopts.maxDate = new Date( obj["end"].split(" ")[0]);
						
							if(obj["start"].split(" ").length == 2 && obj["end"].split(" ").length ==2)
							{
							  var istart = mysqltimestamp2javascript(obj["start"]);
        						  timeopts.minDate = new Date(istart);
							  timeopts.minDateTime = new Date(istart.getTime());	

							  var iend   = mysqltimestamp2javascript(obj["end"]);	
							  timeopts.maxDate = new Date(iend);
							  timeopts.maxDateTime = new Date(iend.getTime());
							}
						}
						if("dateFormat" in obj && obj.dateFormat !== null)
						{
							dateopts.dateFormat = obj.dateFormat;
							timeopts.dateFormat = obj.dateFormat;
						}
						if("timeFormat" in obj && obj.timeFormat !== null)
						{
							timeopts.timeFormat = obj.timeFormat;
						}
					}


					// If input field exists with class
					if( $(adv_id+" .datepicker_"+attr).length)
					{

						// If IN or NOT IN is selected
						if($.inArray(this.value,other_lists) !== -1)
						{
							$(adv_id+" .datepicker_"+attr).datepicker("destroy");
							$(adv_id+" .datepicker_"+attr).removeClass("datepicker");
							$(adv_id+" .datepicker_"+attr).attr("readonly", false);
						}else
						{
						  // If picker not activated before
						  if(!($(adv_id+" .timepicker_"+attr+".datepicker").length))
						  {
							// Readonly
							$(adv_id+" .datepicker_"+attr).attr("readonly", true);

							// Activate datepicker
					  		$(adv_id+" .datepicker_"+attr).datepicker(dateopts).addClass('datepicker');
						  }
						}
					}

					// If input field exists with class
					if( $(adv_id+" .timepicker_"+attr).length)
					{

						// If IN or NOT IN is selected
						if($.inArray(this.value,other_lists) !== -1)
						{
							$(adv_id+" .timepicker_"+attr).datepicker("destroy");
							$(adv_id+" .timepicker_"+attr).datetimepicker("destroy");
							$(adv_id+" .timepicker_"+attr).removeClass("datetimepicker");
							$(adv_id+" .timepicker_"+attr).attr("readonly", false); 
						}else
						{
						  // If picker not activated before
						  if(!($(adv_id+" .timepicker_"+attr+".datetimepicker").length))
						  {
							// Readonly
							$(adv_id+" .timepicker_"+attr).attr("readonly", true);

							// activate datetimepicker and addclass datetimepicker
					  		$(adv_id+" .timepicker_"+attr).datetimepicker(timeopts).addClass('datetimepicker');
						  }
						}
					}
				}
			}
		});


		// Put off event which is initialised from flexigrid.js
		$('form[data="<?php echo $unique_hash; ?>"]').find('.search_clear').off("click");
	
		// On click
		$('form[data="<?php echo $unique_hash; ?>"]').find('.search_clear').on("click",function()
		{
                  
                  //erase all cookies with advanced search data
                $('.allcelldata_'+unique_hash).each(function() {
                                   if(readCookie($(this)[0]['name']+'_'+unique_hash)!==null)
                                   {  
                                      
                                   
                                      eraseCookie($(this)[0]['name']+'_'+unique_hash);
                                 
                                 
                                  }
                        });
               
			// Reset all input and select from advanced_search_* div
			$("#advanced_search_<?php echo $unique_hash; ?>").children().find('input,select').each(function(){
  				if($(this).getType() =="select"){
					// Reset index
					$(this).prop('selectedIndex',0); 

					// fire change event to hide user input box
					$(this).change(); 
				}else{
					$(this).val('');
				}
			});

			// No change as flexigrid.js
			$('form[data="<?php echo $unique_hash; ?>"]').find('.crud_page').val('1');
			$('form[data="<?php echo $unique_hash; ?>"]').find('.search_text').val('');

			// Copy value from radio input
			var tmp = $(".main_search_type_<?php echo $unique_hash; ?>").val();

			// make it basic for easy clearing
			$(".main_search_type_<?php echo $unique_hash; ?>").val('basic');

			// Submit form
			$('form[data="<?php echo $unique_hash; ?>"]').trigger('submit');

			// Again copy value saved in tmp back to hidden input
			$(".main_search_type_<?php echo $unique_hash; ?>").val(tmp);
		});

});
</script>
<!------------------------------------------------------ Edit-3 ends ------------------------------------------------------------------------------>


        <div class='search-div-clear-button'>
        	<input type="button" value="<?php echo $this->l('list_clear_filtering');?>" id='search_clear' class="search_clear">
        </div>

	</div>
<!--- Template modification ends--->

<?php
	} // end of else
?>

	<div class="pDiv">
		<div class="pDiv2">
			<div class="pGroup">
				<span class="pcontrol">
					<?php list($show_lang_string, $entries_lang_string) = explode('{paging}', $this->l('list_show_entries')); ?>
					<?php echo $show_lang_string; ?>
					<select name="per_page" id='per_page' class="per_page">
						<?php foreach($paging_options as $option){?>
							<option value="<?php echo $option; ?>" <?php if($option == $default_per_page){?>selected="selected"<?php }?>><?php echo $option; ?>&nbsp;&nbsp;</option>
						<?php }?>
					</select>
					<?php echo $entries_lang_string; ?>
					<input type='hidden' name='order_by[0]' id='hidden-sorting' class='hidden-sorting' value='<?php if(!empty($order_by[0])){?><?php echo $order_by[0]?><?php }?>' />
					<input type='hidden' name='order_by[1]' id='hidden-ordering' class='hidden-ordering'  value='<?php if(!empty($order_by[1])){?><?php echo $order_by[1]?><?php }?>'/>
				</span>
			</div>
			<div class="btnseparator">
			</div>
			<div class="pGroup">
				<div class="pFirst pButton first-button">
					<span></span>
				</div>
				<div class="pPrev pButton prev-button">
					<span></span>
				</div>
			</div>
			<div class="btnseparator">
			</div>
			<div class="pGroup">
				<span class="pcontrol"><?php echo $this->l('list_page'); ?> <input name='page' type="text" value="1" size="4" id='crud_page' class="crud_page">
				<?php echo $this->l('list_paging_of'); ?>
				<span id='last-page-number' class="last-page-number"><?php echo ceil($total_results / $default_per_page)?></span></span>
			</div>
			<div class="btnseparator">
			</div>
			<div class="pGroup">
				<div class="pNext pButton next-button" >
					<span></span>
				</div>
				<div class="pLast pButton last-button">
					<span></span>
				</div>
			</div>
			<div class="btnseparator">
			</div>
			<div class="pGroup">
				<div class="pReload pButton ajax_refresh_and_loading" id='ajax_refresh_and_loading'>
					<span></span>
				</div>
			</div>
			<div class="btnseparator">
			</div>
			<div class="pGroup">
				<span class="pPageStat">
					<?php $paging_starts_from = "<span id='page-starts-from' class='page-starts-from'>1</span>"; ?>
					<?php $paging_ends_to = "<span id='page-ends-to' class='page-ends-to'>". ($total_results < $default_per_page ? $total_results : $default_per_page) ."</span>"; ?>
					<?php $paging_total_results = "<span id='total_items' class='total_items'>$total_results</span>"?>
					<?php echo str_replace( array('{start}','{end}','{results}'),
											array($paging_starts_from, $paging_ends_to, $paging_total_results),
											$this->l('list_displaying')
										   ); ?>
				</span>
			</div>
		</div>
		<div style="clear: both;">
		</div>
	</div>
	<?php echo form_close(); ?>
	</div>
</div>
