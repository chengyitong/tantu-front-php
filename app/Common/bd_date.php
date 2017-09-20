<?
function date1($stars=true,$month=6){
    (isset($_GET['page'])) ? $page = $_GET['page'] : $page = 1;
    if($page<1) $page=1;
    $page2=$page;
    $page2--;
    $star = DateAdd('m',$page2,now(),0);
	if(I('get.date')) $star=I('get.date');
    if(!$stars || $page2>0) $star = date('Y-m-d', mktime(00, 00, 00, date('m', strtotime($star)), 01));
    $end = date('Y-m-d', mktime(23, 59, 59, date('m', strtotime($star))+1, 00));
    $data['star'] = $star;
	$data['end'] = $end;
    $data['days'] = DateDiff('d',$star,$end);
	$today_arr = explode('-',now(1));
	$data['month'] = '<ul>';
	for($i=1;$i<=$month;$i++):
		if($page==$i) $lidef = ' class="def"'; else $lidef = ' onclick="location=\''.seturlget('page',$i).'\';"';
		$data['month'] .= '<li'.$lidef.'>';
		$today_arr = explode('-',now(1));
		$today = $today_arr[1] + $i - 1;
		$year = $today_arr[0]+floor(($today-1)/12);
		if($today>12) $today=$today % 12;
		if($today==0) $today=12;
		$data['month'] .= $year.'年';
		$data['month'] .= $today.'月份</li>';
	endfor;
	if($page>$month){
		$data['month'] .= '<li class="def">';
		$today = $today_arr[1] + $page - 1;
		$year = $today_arr[0]+floor(($today-1)/12);
		if($today>12) $today=$today % 12;
		if($today==0) $today=12;
		$data['month'] .= $year.'年';
		$data['month'] .= $today.'月份</li>';
	}
	$data['month'] .= '</ul>';
	if($page>1) $prev = seturlget('page',$page-1);
	else $prev = '';
	$data['prev'] = $prev;
	$data['next'] = seturlget('page',$page+1);
	return $data;
}
function calendar($arr){
	$datas = $arr['data'];
	/**
	* 日历
	*/
	$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
	$date = getdate(strtotime($date));
	$start = getdate(mktime(0, 0, 0, $date['mon'], 1, $date['year']));
	$end = getdate(mktime(0, 0, 0, $date['mon'] + 1, 1, $date['year']) - 1);
	$pre = date('Y-m-d', $start[0] - 1);
	$next = date('Y-m-d', $end[0] + 86400);
	$html = '<table class="table_list" width="100%" border="0" cellspacing="0" cellpadding="0">';
	$html .= '<tr><th colspan="10" align="left" class="title">&nbsp;'.$arr['title'].'</th></tr>';
	$html .= '<tr style="background:#FFF;" align="center">';
	if($arr['can_pre']) $html .= '<td><a href="'.seturlget('date',$pre).'">上个月</a></td>';
	else $html .= '<td> </td>';
	$html .= '<td colspan="5">'.$date['year'].'年'.$date['mon'].'月</td>';
	if($arr['can_next']) $html .= '<td><a href="'.seturlget('date',$next).'">下个月</a></td>';
	else $html .= '<td> </td>';
	$html .= '</tr>';
	$html .= '<tr align="center" style="background:#DDD;">
				<td width="15%">日</td>
				<td width="14%">一</td>
				<td width="14%">二</td>
				<td width="14%">三</td>
				<td width="14%">四</td>
				<td width="14%">五</td>
				<td width="15%">六</td>
			  </tr>';
	$arr_tpl = array(0 => '', 1 => '', 2 => '', 3 => '', 4 => '', 5 => '', 6 => '');
	$date_arr = array();
	$j = 0;
	for ($i = 0; $i < $end['mday']; $i++) {
	if (!isset($date_arr[$j])) {
	$date_arr[$j] = $arr_tpl;
	}
	$date_arr[$j][($i+$start['wday'])%7] = $i+1;
	if ($date_arr[$j][6]) {
	$j++;
	}
	}
	$ctdheight=$arr['td_height'];
	foreach ($date_arr as $value) {
	$html .= '<tr>';
	foreach ($value as $v) {
		if($v) {
			if($v<10) $v='0'.$v;
			$mon=$date['mon'];
			if($date['mon']<10) $mon = '0'.$date['mon'];
			$eachdate = $date['year'].$mon.$v;
			$dates = $date['year'].'-'.$mon.'-'.$v;
			$curdate = date('Ymd');
			if($arr['after_today_gray'] && $curdate > $eachdate){
				$html .= '<td valign="top" bgcolor="#EEEEEE" style="height:'.$ctdheight.'px;" class="cd_alldays">'.$v.'<div id="cd'.$eachdate.'"></div></td>';
			}else{
				$html .= '<td valign="top" style="height:'.$ctdheight.'px;position:relative;background:#FFFFFF" class="cd_alldays cd_days" onmouseover="$(\'#adds'.$eachdate.'\').css(\'display\',\'\');" onmouseout="$(\'#adds'.$eachdate.'\').css(\'display\',\'none\');">';
				if($curdate == $eachdate) $html .= '<span style="font-weight:bold;color:red;">'.$v.'</span>';
				else $html .= $v;
				$html .= '<ul>';
				foreach($datas[$eachdate] as $k){
					$name = '';
					if($k['name']!='') $name = '['.$k['name'].']';
					$html .= '<li style="position:relative;" onmouseover="$(\'.do'.$k['id'].'\').css(\'display\',\'\');" onmouseout="$(\'.do'.$k['id'].'\').css(\'display\',\'none\');">
					<a class="sexybox_iframe" href="/'.$arr['func'].'_v?id='.$k['id'].'" style="color:blue" hidefocus="true">'.$name.$k['title'].'</a>
					<div style="position: absolute; right: 0px; top: 0px;" class="t">
						<a href="/'.$arr['func'].'_del" data-id="'.$k['id'].'" class="btn_del do'.$k['id'].'" callback="" style="color:red;font-weight:bold;display:none;" hidefocus="true" title="删除该条">×</a>
						<span style="font-size:12px;color:#999;">'.$k['msgtime'].'</span>
					</div>
					</li>';
				}
				$html .= '</ul>
					<div id="adds'.$eachdate.'" style="position:absolute;top:3px;right:5px;font-size:12px;display:none">
					<a class="sexybox_iframe" href="/'.$arr['func'].'_addv?d='.$dates.'" style="color:blue">添加</a>
					</div>
					</td>';
			}
		} else {
			$html .= '<td valign="top" bgcolor="#EEEEEE" style="height:'.$ctdheight.'px;"> </td>';
		}
	}
	$html .= '</tr>';
	}
	$html .= '</table>';
	return $html;
}