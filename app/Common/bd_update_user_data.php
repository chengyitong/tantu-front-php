<?php
	//通过作品id，更新作品的‘喜欢数’和‘作品数’
	//一般在触发了作品状态变更（status、haspass、isuse）或新增、用户对作品addlike之后需要调用此方法
	function updateCountAndLikes($productId){
		if(!is_int($productId) && !is_int(intval($productId))){
			return 'paramter: productId illegal.';
		}

		$uid = D('a_product')->where('id='.$productId)->getField('uid');
		return updateCountAndLikesByUserId($uid);
	}

	function updateCountAndLikesByUserId($userId){
		if(!is_int($userId) && !is_int(intval($userId))){
			return 'paramter: userId illegal.';
		}

		//检索对应的产品id集合
		$proudctIdArray = D('a_product')->where('status = 1 AND haspass = 1 AND isuse = 1 AND uid='.$userId)->Field('id')->select();
		
		$proudctIdArray = array_reduce($proudctIdArray, function ($result, $value) {
        	return array_merge($result, array_values($value));
        }, array());

		$count_imgs = sizeof($proudctIdArray);
		// //更新对应的作品数 & 喜欢数
		$sql_update = 'update bdmis_a_user set count_imgs='.$count_imgs.', ';
		$sql_update .= 'count_likes=(select sum(likes) from bdmis_a_product_number where productid in('.implode(',',$proudctIdArray).'))';
		$sql_update .= ' where id='.$userId;
		$flag =  D()->execute($sql_update);

		return $flag;
	}