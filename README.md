# Grocery_Crud_MultiSearch

### Version
1.0.0

### Installation

If you have installed Codeigniter & Grocery crud already you just need to unzip Grocery_Crud_MultiSearch-master

```sh
$ unzip Grocery_Crud_MultiSearch-master.zip -d /apache_root/<CI>
```

If you are using Codeigniter & Grocery crud first time, you may extract this file,
  - Grocery_Crud_MultiSearch.zip         
          Codeigniter 3.0.0, Grocery Crud 1.5.1 and Grocery_Crud_MultiSearch preinstalled, you only have to modify application/config/database.php file after importing examples_database.sql file.
 

### Options available
```php 
	/* This should be associative array
 
	  Options are :
				 dateFormat   - datepicker format string
				 timeFormat   - timepicker format string
				 search       - true or false, disable specific field
				 start        - date or datetime picker start date should be in yy-mm-dd HH:mm:ss format
				 end          - date or datetime picker start date should be in yy-mm-dd HH:mm:ss format
				 help         - This is help option visible to user
				 type         - If column is not automatically detected user can specify option type
						        options are :
								               numeric_opts
								               string_opts
								               date_and_time
	
	*/
	
	class Examples extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
		$this->load->library('Grocery_CRUD_MultiSearch');
	}
	
	public function customers_management()
	{
		$crud = new Grocery_CRUD_MultiSearch();
		...
		...
		...
		/* Optional Option */
		
		// By default multisearch is enabled for all, if you set it false only basic search will be shown
		$crud->multisearch = false;

		$crud->field_property = array(
					// Help text for user
					"last_update"=>array("help"=>"Should Be in yy-mm-dd HH:mm:ss"),
		
					// Intializes date and time picker with below range
					"start_date" =>array("start"=>"2015-06-22 04:00:00","end"=>"2015-06-29 05:00:00"),

					/* Suppose column "total_count" is varchar in your table, by default program 
					   intializes "string_opts" option in dropdown, if you want to override this option
					   you can specify type here like below
					*/ 
					"total_count" =>array("type"=>"numeric_opts")
	         );

		$output = $crud->render();
	}
	
	} // end of class
```


#### Package Strucure 
```sh
grocery_crud_multisearch/
├── application
│   └── libraries
│       └── Grocery_CRUD_MultiSearch.php
└── assets
    └── grocery_crud
        └── themes
            └── flexigrid
                └── views
                    └── list_template.php

7 directories, 2 files
```


#### Radio selection - basic or advanced search options
![Add Field][1]

#### List of fields with dropdown
![list][2]

#### Automatic date and datetime picker 
![no file text][3]


[1]:https://github.com/Akshay-Hegde/Grocery_Crud_MultiSearch/blob/master/screenshots/search_1.png
[2]:https://github.com/Akshay-Hegde/Grocery_Crud_MultiSearch/blob/master/screenshots/search_2.png
[3]:https://github.com/Akshay-Hegde/Grocery_Crud_MultiSearch/blob/master/screenshots/search_3.png

Author  
----
     Akshay Hegde
     akshay.k.hegde@gmail.com
     https://www.linkedin.com/profile/view?id=206267783


License
----
	https://github.com/scoumbourdis/grocery-crud/blob/master/license-grocery-crud.txt
	
Copyright
----
    Copyright (c) 2010 through 2014, John Skoumbourdis


