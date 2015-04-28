<?php

namespace PhangoApp\PhaI18n;

class I18n {

	static public $lang=array();

	static public $arr_i18n=array();
	
	static public $language='';
	
	static public $cache_lang=array();
	
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
		
			$base_path=getcwd();

			$module_path=$lang_file;
				
			$pos=strpos($module_path, "/");
			
			if($pos!==false)
			{

				$arr_path=explode('/', $module_path);

				$module_path=$arr_path[0];
				
			}

			if(!isset(I18n::$cache_lang[$lang_file]))
			{

				//First search in module, after in root i18n.

				//echo I18n::$base_path.'modules/'.$lang_file.'/i18n/'.I18n::$language.'/'.$lang_file.'.php';

				//ob_start();
				
				
				$path=$base_path.'/'.$module_path.'/i18n/'.I18n::$language.'/';
				
				$file_path=$path.$lang_file.'.php';
				
				if(is_file($file_path))
				{
					include($file_path);
				}
				else
				{

					$path=$base_path.'i18n/'.I18n::$language.'/';
					$file_path=$base_path.'i18n/'.I18n::$language.'/'.$lang_file.'.php';
				
					if(!include($file_path)) 
					{
						
						throw new \Exception('Error cannot load the language file in '.$file_path);
						
						die;
					
					}

				}
				
				//I18n::$l_[$lang_file]=new PhaLang($lang_file);

				//ob_end_clean();

				I18n::$cache_lang[$lang_file]=1;

			}

		}
	
	}
	
	static public function lang($app, $code_lang, $default_lang) 
	{

		return isset(I18n::$lang[$app][$code_lang]) ? I18n::$lang[$app][$code_lang] : $default_lang;

	}

}

?>