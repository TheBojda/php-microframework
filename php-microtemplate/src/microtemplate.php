<?php
	
	define("PHP_MICROTEMPLATE", true);
	
	class MicroTemplate {
		
		public $template_dir = '.';

		private function replace_vars($content) {
			$result = "";
			$last_pos = 0;
			$pos = strpos($content, "#{", $last_pos);
			do {
				$pos = strpos($content, "#{", $last_pos);
				if($pos !== FALSE) {
					$result .= substr($content, $last_pos, $pos-$last_pos);
					$result .= "<?php echo ";
					$pos +=2;
					$last_pos = $pos;
					$cnt = 1;
					do{
						if($content{$last_pos} == '{')
							$cnt++;
						if($content{$last_pos} == '}')
							$cnt--;
						$last_pos++;
					} while($cnt>0);
					$result .= substr($content, $pos, $last_pos-$pos-1);
					$result .= "; ?>";
				}
			} while($pos);
			$result .= substr($content, $last_pos, strlen($content)-$last_pos);
			return $result;
		}

		public function generate($template) {
			$compiled_template_path = $this->template_dir . '/' . $template . '.php'; 
			@mkdir(dirname($compiled_template_path),0777,true);
			if(file_exists($compiled_template_path)) {
				if(filemtime($compiled_template_path) >= filemtime($template))
					return $compiled_template_path;
			}
			$content = file_get_contents($template);
			$content = str_replace("<!--{", "<?php", $content);
			$content = str_replace("}-->", "?>", $content);
			$content = str_replace("<!-- mt_remove -->", "<?php /* ?>", $content);
			$content = str_replace("<!-- /mt_remove -->", "<?php */ ?>", $content);
			$content = $this->replace_vars($content);
			// prevent template external access
			$content = "<?php if(!defined('PHP_MICROTEMPLATE')) exit(); ?>" . $content;
			file_put_contents($compiled_template_path, $content);
			return $compiled_template_path;
		}
	
	}
	
	global $MT;
	$MT = new MicroTemplate;
	$MT->template_dir = TEMPLATE_DIR;
?>