<?php
/**
 * PHP Grocery_CRUD_MultiSearch - for Flexigrid theme
 *
 * @package Grocery_CRUD_MultiSearch for Flexigrid theme
 *
 * @author Akshay Hegde <akshay.k.hegde@gmail.com>
 *
 * www.linkedin.com/profile/view?id=206267783
 *
 * https://github.com/Akshay-Hegde
 *
 * @version 1.0.0
 *  
 */
class Grocery_CRUD_MultiSearch extends Grocery_CRUD
{

protected function create_dropdown_opts($options_q)
{
	$options = "";
	foreach($options_q as $n => $v)
	{
		$options .= "<option value='$v'>$n</option>";
	}
	return $options;
}

protected function get_multisearch_opts()
{
	$string_opts = array(
				"Select"                => "none",
				"Equal"                 => "=",
				"Not Equal"             => "!=",
				"contains"              => "LIKE",
				"does not contains"     => "NOT LIKE",
				"IN"                    => "IN",
				"NOT IN"                => "NOT IN"
			  );

	$numeric_opts = array(
				"Select"                => "none",
				"Equal"                 => "=",
				"Not Equal"             => "!=",
				"Greater than"          => ">",
				"Greater than or equal" => ">=",
				"Less than"             => "<",
				"Less than or equal"    => "<=",	
				"Between"               => "BETWEEN",
				"IN"                    => "IN",
				"NOT IN"                => "NOT IN"
			 );

	$date_and_time = array(
				"Select"                => "none",
				"Equal"                 => "=",
				"Not Equal"             => "!=",
				"Greater than"          => ">",
				"Greater than or equal" => ">=",
				"Less than"             => "<",
				"Less than or equal"    => "<=",
				"Between"               => "BETWEEN",
				"IN"                    => "IN",
				"NOT IN"                => "NOT IN",
				"contains"              => "LIKE",
				"does not contains"     => "NOT LIKE"
			 );

	$logic = array( "AND" => "AND", "OR" => "OR");
	

	return array(
			"string_opts"=>$this->create_dropdown_opts($string_opts),
			"numeric_opts"=>$this->create_dropdown_opts($numeric_opts),
			"date_and_time"=>$this->create_dropdown_opts($date_and_time),
			"logic"=>$this->create_dropdown_opts($logic)
	);
}

protected function _setup_advanced_opts($state_info = null)
{

				// Start of advanced search			
			
				// Set search type
				// set null to avoid errors while using other themes
				$state_info->search_type = ( array_key_exists("search_type",$_POST) ? $_POST['search_type'] : null );	
	

				if($state_info->search_type == "advanced")
				{

				$action_arr    = array();
				$valid_columns = $this->get_columns();
				$field_types = $this->get_field_types();

				// Parse field name and value
				foreach($_POST as $index => $value)
				{
					if(substr( $index, 0, 14 ) === "ADV_operation_" && $value != "none")
					{
						$field_name       = substr( $index, 14 ); 
						$field_oper       = $value; // Operation like equal, greater, like, etc
						$field_value_ind  = "ADV_data_".$field_name;
						$field_logic_ind  = "ADV_logic_".$field_name;
	
						$add_single_quote = true;	
										

						if(array_key_exists($field_value_ind,$_POST))
						{
							$field_value = $_POST[$field_value_ind]; 
							
							// If 'between' used than field_value will be always ""
							if(trim($field_value) === '' && $field_oper != "BETWEEN")
							{
								continue;
							}else
							{

							$field_value = $this->basic_model->escape_str($field_value);

							if(in_array($field_oper,array("LIKE","NOT LIKE")))
							{
								
								$field_value = "%".$field_value."%";
							}

							if(in_array($field_oper,array("IN","NOT IN")))
							{
								$tmp = array(); 
								foreach(explode(",",$field_value) as $v )
								{
									if(trim($v) != '')
										$tmp[] = $this->basic_model->escape_str($v);
								}
								if(empty($tmp))
								{
									continue;
								}else
								{
								   $field_value	= " ('" . implode("', '", $tmp) . "') ";
								   $add_single_quote = false; // do not add single quote 
								}
								
							}
						
							if($field_oper == "BETWEEN")
							{
								$from = $_POST[$field_value_ind."_from"];
								$to   = $_POST[$field_value_ind."_to"];

								if(trim($from) === '' || trim($to) === '')
								{
									continue;
								}

								$from = $this->basic_model->escape_str($from);
								$to   = $this->basic_model->escape_str($to);
							}

							$tmp        = $field_name ;
							$field_name = "`$field_name`";

							if(in_array($field_types[$tmp]->type,array('float','double')))
							{
								if($field_oper != "BETWEEN")
								{
								  $field_name = "ROUND($field_name,".strlen(substr(strrchr($field_value, "."), 1)).")";
								}
							}

								// Single Quote 
								if($add_single_quote)
								{ 
									$field_value = "'".$field_value."'"; 
								}

								$field_logic = "";
								if(array_key_exists($field_logic_ind,$_POST))
								{
									$field_logic  = $_POST[$field_logic_ind];
								}	

								if($field_oper == "BETWEEN")
								{
									$action_arr[] = " ( $field_name $field_oper '$from' AND '$to' ) $field_logic ";
								}else
								{	
									$action_arr[] = " ( $field_name  $field_oper $field_value ) $field_logic ";
								}
								
							}

						}

					}
				}
				if(!empty($action_arr))
				{
				   $state_info->advanced_query_string = rtrim(rtrim(implode("",$action_arr),"AND "),"OR ");
				}else
				{
				   $state_info->advanced_query_string = false;
				}
					
				}
				// End of advanced search

		return $state_info;
}

protected function _update_data($data)
{
		$option_type = array(); 
		$data->any_time = false; 
		$property = array();
		$tmp      = array();
		$possible_opts = array("numeric_opts","string_opts","date_and_time");
		
		foreach($data->columns as $k => $v)
		{
			$tmp[$v->field_name] = $v;			
		}

		if(isset($this->field_property) && is_array($this->field_property))
		{
			$property = $this->field_property;		
		}


		foreach($data->types as $k => $v)
		{
			// if its not created on fly then (real column in table)
			if(isset($v->db_type))
			{

			switch($v->type)
			{
	
			case 'date'     :
			case 'datetime' :
			case 'timestamp':
					 $option_type[$k] = "date_and_time";
					 $data->any_time = true;
					 break;
			case 'int'      :
			case 'float'    :
			case 'double'   :
					$option_type[$k] = "numeric_opts";
					break;
			default         :
					$option_type[$k] = "string_opts";
					break;
					
			}

			if( array_key_exists($k,$property) && is_array($property[$k]) && !empty($property[$k])) 
			{
				if(array_key_exists("type",$property[$k]) && in_array($property[$k]["type"],$possible_opts))
				{
					$option_type[$k] = $property[$k]["type"];
				}

				if( array_key_exists("search",$property[$k]) && !$property[$k]["search"])
				{
					// disable search opts for this field
					unset($tmp[$v->name]);
				}
			}

			}else
			{
				unset($tmp[$v->name]);
			}
								
		}
			$data->columns_for_search = $tmp; 
			$data->option_type = $option_type;
			$data->options     = $this->get_multisearch_opts();


			// Start or End should be yy-mm-dd HH:mm:ss - for range
			// usage : $crud ->field_property = array( "column1"=>array("help"=>"enter integer") );

			$field_opts    = array("dateFormat","timeFormat","start","end","help","type"); 
			$field_propery = array();

			foreach($data->columns as $name => $v)
			{
				$name = $v->field_name; 

				if( array_key_exists($name,$property) && is_array($property[$name]) && !empty($property[$name]) ) 
				{
					$arr = $property[$name];
					foreach($field_opts as $k )
					{
						if(array_key_exists($k,$arr))
						{
						   $field_propery[$name][$k] = $arr[$k];
						}
					}
				}
			}
			
			

			$data->field_property = $field_propery;

		return $data;

}

protected function showList($ajax = false, $state_info = null)
{
		$data = $this->get_common_data();

		$data->order_by 	= $this->order_by;

		$data->types 		= $this->get_field_types();
		
		$data->list = $this->get_list();
		$data->list = $this->change_list($data->list , $data->types);
		$data->list = $this->change_list_add_actions($data->list);

		$data->total_results = $this->get_total_results();

		$data->columns 				= $this->get_columns();

		$data->success_message		= $this->get_success_message_at_list($state_info);

		$data->primary_key 			= $this->get_primary_key();
		$data->add_url				= $this->getAddUrl();
		$data->edit_url				= $this->getEditUrl();
		$data->delete_url			= $this->getDeleteUrl();

		if(method_exists($this,'getDeleteMultipleUrl'))
		{
			$data->delete_multiple_url	= $this->getDeleteMultipleUrl();
		}

		$data->read_url				= $this->getReadUrl();
		$data->ajax_list_url			= $this->getAjaxListUrl();
		$data->ajax_list_info_url		= $this->getAjaxListInfoUrl();
		$data->export_url			= $this->getExportToExcelUrl();
		$data->print_url			= $this->getPrintUrl();
		$data->actions				= $this->actions;
		$data->unique_hash			= $this->get_method_hash();
		$data->order_by				= $this->order_by;

		$data->unset_add			= $this->unset_add;
		$data->unset_edit			= $this->unset_edit;
		$data->unset_read			= $this->unset_read;
		$data->unset_delete			= $this->unset_delete;
		$data->unset_export			= $this->unset_export;
		$data->unset_print			= $this->unset_print;

		$default_per_page = $this->config->default_per_page;
		$data->paging_options = $this->config->paging_options;
		$data->default_per_page		= is_numeric($default_per_page) && $default_per_page >1 && in_array($default_per_page,$data->paging_options)? $default_per_page : 25;
		


		/* Update starts */
		$data = $this->_update_data($data);	
		$data->multisearch = ((property_exists($this, "multisearch") && $this->multisearch) || !property_exists($this, "multisearch"));
		/* Update ends */
	

		if($data->list === false)
		{
			throw new Exception('It is impossible to get data. Please check your model and try again.', 13);
			$data->list = array();
		}

		foreach($data->list as $num_row => $row)
		{
			$data->list[$num_row]->edit_url = $data->edit_url.'/'.$row->{$data->primary_key};
			$data->list[$num_row]->delete_url = $data->delete_url.'/'.$row->{$data->primary_key};
			$data->list[$num_row]->read_url = $data->read_url.'/'.$row->{$data->primary_key};
		}

		if(!$ajax)
		{
			$this->_add_js_vars(array('dialog_forms' => $this->config->dialog_forms));

			$data->list_view = $this->_theme_view('list.php',$data,true);
			$this->_theme_view('list_template.php',$data);
		}
		else
		{
			$this->set_echo_and_die();
			$this->_theme_view('list.php',$data);
		}
		
		/* Update starts */	
		// Add scripts
		if($data->any_time && $data->multisearch)
		{
			$this->set_css($this->default_css_path.'/jquery_plugins/jquery-ui-timepicker-addon.css');
			$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery-ui-timepicker-addon.js');
		}
		/* Update ends */
}



protected function set_ajax_list_queries($state_info = null)
{

		$state_info->search_type = null;
		if( (property_exists($this, "multisearch") && $this->multisearch) || !property_exists($this, "multisearch"))
		{

		/* Advanced Search Option Setup       */
		if(in_array($this->getStateCode(), array(7,8,16,17)))
		{
			$state_info = $this->_setup_advanced_opts($state_info); 
		}

		}

		// Sort fix 
		$tmp = array();$types = $this->get_field_types(); 
		foreach($types as $k => $v)
		{
			if(isset($v->db_type))$tmp[]=$v->name;
		}

		if(!empty($state_info->per_page))
		{
			if(empty($state_info->page) || !is_numeric($state_info->page) )
				$this->limit($state_info->per_page);
			else
			{
				$limit_page = ( ($state_info->page-1) * $state_info->per_page );
				$this->limit($state_info->per_page, $limit_page);
			}
		}
		
		// Sort issue fix with columns created on fly
		if(!empty($state_info->order_by) && in_array($state_info->order_by[0],$tmp))
		{
			$this->order_by($state_info->order_by[0],$state_info->order_by[1]);
		}


if($state_info->search_type == "advanced")
{
	if($state_info->advanced_query_string != false)
	{
		$this->where($state_info->advanced_query_string,NULL,FALSE);
	}

// Existing Basic Search
}else
{
		if(!empty($state_info->search))
		{
			if (!empty($this->relation)) 
			{
				foreach ($this->relation as $relation_name => $relation_values)
				{
					$temp_relation[$this->_unique_field_name($relation_name)] = $this->_get_field_names_to_search($relation_values);
                		}
            		}

               if(is_array($state_info->search))
	       {
                foreach ($state_info->search as $search_field => $search_text)
		{
                    if (isset($temp_relation[$search_field])) 
		    {
                        if (is_array($temp_relation[$search_field]))
			{
                            foreach ($temp_relation[$search_field] as $relation_field)
			    {
                                $this->or_like($relation_field , $search_text);
                            }
                        } else 
			{
                            $this->like($temp_relation[$search_field] , $search_text);
                        }
                    } elseif(isset($this->relation_n_n[$search_field])) 
		    {
                        $escaped_text = $this->basic_model->escape_str($search_text);
                        $this->having($search_field." LIKE '%".$escaped_text."%'");
                    } else 
		    {
                        $this->like($search_field, $search_text);
                    }
                }
             } elseif ($state_info->search->field !== null) {
				if (isset($temp_relation[$state_info->search->field])) {
					if (is_array($temp_relation[$state_info->search->field])) {
						foreach ($temp_relation[$state_info->search->field] as $search_field) {
							$this->or_like($search_field , $state_info->search->text);
                        }
                    } else {
						$this->like($temp_relation[$state_info->search->field] , $state_info->search->text);
                    }
				} elseif(isset($this->relation_n_n[$state_info->search->field])) {
					$escaped_text = $this->basic_model->escape_str($state_info->search->text);
					$this->having($state_info->search->field." LIKE '%".$escaped_text."%'");
				} else {
					$this->like($state_info->search->field , $state_info->search->text);
				}
			}
			else
			{
				$columns = $this->get_columns();

				$search_text = $state_info->search->text;

				if(!empty($this->where))
					foreach($this->where as $where)
						$this->basic_model->having($where[0],$where[1],$where[2]);

				foreach($columns as $column)
				{
					if(isset($temp_relation[$column->field_name]))
					{
						if(is_array($temp_relation[$column->field_name]))
						{
							foreach($temp_relation[$column->field_name] as $search_field)
							{
								$this->or_like($search_field, $search_text);
							}
						}
						else
						{
							$this->or_like($temp_relation[$column->field_name], $search_text);
						}
					}
					elseif(isset($this->relation_n_n[$column->field_name]))
					{
						//@todo have a where for the relation_n_n statement
					}
					else
					{
						$this->or_like($column->field_name, $search_text);
					}
				}
			}
		}

} // end of else basic search

} // end of method set_ajax_list_queries


    
}

