<?php


/**
 * 综合工具类
 * 2014-11-18 
 * yes123
 */

class ImageUtils {

	//上传图片
	public static function imageUpload($savepath,$user_id,$max_size) {
		
		$picname = $_FILES['image']['name'];
		$picsize = $_FILES['image']['size'];
		
		if ($picname != "") {
			if ($picsize > $max_size) {
				$max_msg = $max_size==2048000?"2MB":"5MB";
				return array('code'=>3,'msg'=>'图片大小不能超过'.$max_msg);
			}
			$type = strtolower(strstr($picname, '.')); //modify yes123 2014-12-04 都转换为小写再判断
			if ($type != ".gif" && $type != ".jpg" && $type != ".png" && $type != ".bmp") {
				return array('code'=>4,'msg'=>'图片格式不对!');
			}
			$rand = rand(100, 999);
			$pics = date("YmdHis") . $rand . $type;
			
			$pics = $user_id."_".$pics; 
		
			//上传路径
			$pic_path = $savepath . '/' . $pics;
			move_uploaded_file($_FILES['image']['tmp_name'], $pic_path);
		}
		else
		{
			$arr = array (
				'code'=>0,
				'name' => $picname,
				'msg' => '上传失败'
			);
			
			ss_log(__FUNCTION__.'上传失败');
			return $arr;
		}
		
		$size = round($picsize / 1024, 2);
		$arr = array (
			'code'=>0,
			'name' => $picname,
			'pic' => $pic_path, 
			'size' => $size,
			'msg' => '上传成功'
		);
		return $arr;
	}

	public static function delImg($filename) {
		if (!empty ($filename)) {
			unlink($filename);
			return array('code'=>0,'msg'=>'删除成功');
		} else {
			return array('code'=>1,'msg'=>'删除失败');
		}
	}

}
?>
