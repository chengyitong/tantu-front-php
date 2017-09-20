<?
	//  获取文件列表
    function files_getlist($root,$path,$sortby){
		$show_root = $root;
    	$root = '../../'.$root;
		$php_path = dirname(__FILE__).'/';
		$php_url = dirname($_SERVER['PHP_SELF']).'/';
		//根目录路径，可以指定绝对路径，比如 /var/www/attached/
		$root_path = $php_path . $root;
		//根目录URL，可以指定绝对路径，比如 http://www.yoursite.com/attached/
		$root_url = $php_url . $root;
		//根据path参数，设置各路径和URL
		if (empty($path)) {
			$current_path = realpath($root_path) . '/';
			$current_url = $root_url;
			$current_dir_path = '';
			$moveup_dir_path = '';
		} else {
			$current_path = realpath($root_path) . '/' . $path;
			$current_url = $root_url . $path;
			$current_dir_path = $path;
			$moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
		}
		//不允许使用..移动到上一级目录
		if (preg_match('/\.\./', $current_path)) {
			echo 'Access is not allowed.';
			exit;
		}
		//最后一个字符不是/
		if (!preg_match('/\/$/', $current_path)) {
			echo 'Parameter is not valid.';
			exit;
		}
		//目录不存在或不是目录
		if (!file_exists($current_path) || !is_dir($current_path)) {
			echo 'Directory does not exist.';
			exit;
		}
		//遍历目录取得文件信息
	    $file_list = array();
		if ($handle = opendir($current_path)) {
			$i = 0;
			while (false !== ($filename = readdir($handle))) {
				if ($filename{0} == '.') continue;
				$file = $current_path . $filename;
				if (is_dir($file)) {
					$file_list[$i]['is_dir'] = true; //是否文件夹
					$file_list[$i]['has_file'] = (count(scandir($file)) > 2); //文件夹是否包含文件
					$file_list[$i]['filesize'] = 0; //文件大小
					$file_list[$i]['is_photo'] = false; //是否图片
					$file_list[$i]['filetype'] = ''; //文件类别，用扩展名判断
				} else {
					$file_list[$i]['is_dir'] = false;
					$file_list[$i]['has_file'] = false;
					$file_list[$i]['filesize'] = filesize($file);
					$file_list[$i]['dir_path'] = '';
					$file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
					$file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
					$file_list[$i]['filetype'] = $file_ext;
				}
				$file_list[$i]['filename'] = $filename; //文件名，包含扩展名
				$file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); //文件最后修改时间
				$i++;
			}
			closedir($handle);
		}
		//  排序
		$sortby_arr = explode(',', $sortby);
		$file_list = array_sort($file_list,$sortby_arr[0],$sortby_arr[1]);
		//  返回赋值
		$result = array();
		//相对于根目录的上一级目录
		$result['moveup_dir_path'] = $moveup_dir_path;
		//相对于根目录的当前目录
		$result['current_dir_path'] = $current_dir_path;
		//服务器目录
		$result['current_path'] = $current_path;
		//当前目录的URL
		$result['current_url'] = str_replace('/../../','',$current_url);
		//目录URL
		$result['show_url'] = str_replace($show_root,'',$result['current_url']);
		//文件数
		$result['total_count'] = count($file_list);
		//文件列表数组
		$result['file_list'] = $file_list;
		//  输出结果array
		return $result;
    }
    //  递归创建文件夹
    function createFolder($path){
    	$path = '.'.$path;
	    if(!file_exists($path)){
		    createFolder(dirname($path));
		    mkdir($path,0777);
		    return true;
	    }else return false;
    }
    //  创建文件
    function createFile($path){
    	$path = '.'.$path;
	    if(!file_exists($path)){
	    	$fp = fopen($path,'w');
	    	fclose($fp);
	    }else return false;
    }
    //  递归删除文件夹
    function deleteFolder($path){
    	$dir = '.'.$path;
    	if(is_dir($dir)){
	    	$dh = opendir($dir);
	    	while($file = readdir($dh)){
		    	if($file!='.' && $file!='..'){
			    	$fullpath = $dir.'/'.$file;
			    	if(!is_dir($fullpath)){
				    	unlink($fullpath);
			    	}else{
				    	deleteFolder($path.'/'.$file);
			    	}
		    	}
	    	}
	    	closedir($dh);
	    	if(rmdir($dir)) return true;
	    	else return false;
	    }else{
		    unlink($dir);
		    return true;
	    }
    }