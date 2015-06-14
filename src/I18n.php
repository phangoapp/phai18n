<?php

namespace PhangoApp\PhaI18n;

class I18n {

	static public $lang=array();

	static public $arr_i18n=array();
	
	static public $language='';
	
	static public $cache_lang=array();
	
	static public $base_path='';
	
	static public $modules_path='';
	
	static public function loadLang()
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
		else
		{
		
			$_SESSION['language']=I18n::$language;
		
		}
		
		$arg_list = func_get_args();
		
		foreach($arg_list as $lang_file)
		{
		
			$base_path=I18n::$base_path;

			$module_path=$lang_file;
				
			$pos=strpos($module_path, "/");
			
			if($pos!==false)
			{

				$arr_path=explode('/', $module_path);

				$module_path=$arr_path[0];
				
				$lang_file=$arr_path[1];
				
			}

			if(!isset(I18n::$cache_lang[$lang_file]))
			{

				//First search in module, after in root i18n.
				
				$path=$base_path.I18n::$modules_path.'/'.$module_path.'/i18n/'.I18n::$language.'/';
				
				$file_path=$path.$lang_file.'.php';
				
				if(is_file($file_path))
				{
					include($file_path);
				}
				else
				{
					
					$path=$base_path.'i18n/'.I18n::$language.'/';
					
					$file_path=$base_path.'i18n/'.I18n::$language.'/'.$lang_file.'.php';
					
					if(is_file($file_path))
					{
						include($file_path);
					}

				}

				//ob_end_clean();

				I18n::$cache_lang[$lang_file]=1;

			}

		}
	
	}
	
	//Simple method used by aestetic.
	
	static public function load_lang()
	{
	
		//I18n::loadLang(func_get_args());
		call_user_func_array('I18n::loadLang', func_get_args());
	
	}
	
	static public function lang($app, $code_lang, $default_lang) 
	{

		return isset(I18n::$lang[$app][$code_lang]) ? I18n::$lang[$app][$code_lang] : $default_lang;

	}

}

?>