<?php

namespace PhangoApp\PhaI18n;

/**
* This is a class for load  i18n files and define text language strings,
*/

class I18n {
    
    /**
    * Static property that define an multidimensional array with language strings with two keys,the module of the language string (Example phangoapp/admin for module admin) and the unique key for the language string.
    * 
    */

	static public $lang=array();

    /**
    * Array where the avaliable languages are defined. By default are es-ES and en-US.
    *
    */

	static public $arr_i18n=array();
    
    /**
    * Variable that define the default language used by Phango.
    * 
    */
	
	static public $language='';
    
    /**
    * Variable where is saved the language files loaded for don't load again.
    * 
    */
	
	static public $cache_lang=array();
    
    /**
    * Variable where is saved the base path of language files
    * 
    */
	
	static public $base_path='';
    
    /**
    * Variable where modules path  is saved
    * 
    */
	
	static public $modules_path='';
    
    /**
    * Method used for load language files. The argumentes is
    * 
    * @param string language_file You can put a series of language files in composer format. I mind, you can load a language file from vendor/phangoapp/admin/i18n/admin.php using  'phangoapp/admin' how argument
    * 
    */
	
	static public function load_lang()
	{
	
		if(isset($_SESSION['language']))
		{
		
			if(in_array($_SESSION['language'], I18n::$arr_i18n))
			{

				I18n::$language=$_SESSION['language'];
				
			}
			else
			{
			
				$_SESSION['language']=I18n::$language;
			
			}

		}
		/*else
		{
		
			$_SESSION['language']=I18n::$language;
		
		}*/
		
		$arg_list = func_get_args();
		
		foreach($arg_list as $lang_file)
		{
		
			$base_path=I18n::$base_path;

			$module_path=$lang_file;
				
			/*$pos=strpos($module_path, "/");
			
			if($pos!==false)
			{

				$arr_path=explode('/', $module_path);

				$c=count($arr_path);
				
				$module_path=implode('/', array_slice($arr_path, 0, $c-1));
				
				$lang_file=$arr_path[$c-1];
				
			}*/
			
			$c=substr_count($module_path, '/');
			
			if($c==2)
			{
			
                $arr_path=explode('/', $module_path);
			
                $module_path=$arr_path[0].'/'.$arr_path[1];
                
			}
			
			$lang_file=basename($lang_file);
			
			if(!isset(I18n::$cache_lang[$lang_file]))
			{

				//First search in module, after in root i18n.
				
				$path=$base_path.I18n::$modules_path.'/'.$module_path.'/i18n/'.I18n::$language.'/';
				
				$file_path=$path.$lang_file.'.php';
                
				if(is_file($file_path))
				{
					include($file_path);
					
					I18n::$cache_lang[$lang_file]=1;
					
					return true;
					
				}
				else
				{
					
					$path=$base_path.'i18n/'.I18n::$language.'/';
					
					$file_path=$base_path.'i18n/'.I18n::$language.'/'.$lang_file.'.php';
					
					if(is_file($file_path))
					{
						include($file_path);
						
						I18n::$cache_lang[$lang_file]=1;
						
						return true;
					}

				}

				//ob_end_clean();

			}

		}
		
		return false;
	
	}
	
    /**
    * Method for set a language string
    * 
    * @param string $app The composer module where the string is located.
    * @param string $code_lang Unique code with the identified of this string lang
    * @param string $default_lang The default string lang if the string of determined language doesn't exists
    * 
    */
    
	static public function lang($app, $code_lang, $default_lang) 
	{

		return isset(I18n::$lang[$app][$code_lang]) ? I18n::$lang[$app][$code_lang] : $default_lang;

	}

}

