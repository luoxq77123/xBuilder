<?php
class CaptchaComponent extends Component {
	
	public function create($componentCollection, $size = 4, $width = 66, $height = 29) {
		//生成验证码图片
		Header("Content-type: image/PNG");
		$im = imagecreate($width,$height);
		$back = ImageColorAllocate($im, 245,245,245);
		imagefill($im,0,0,$back); //背景

		//生成size位数字
		for($i=0;$i<$size;$i++){
			$font = ImageColorAllocate($im, rand(100,255),rand(0,100),rand(100,255));
			$authnum=rand(1,9);
			@$authCode.=$authnum;
			imagestring($im, 5, 8+$i*14, 7, $authnum, $font);
		}
		
		for($i=0;$i<100;$i++) //加入干扰象素
		{ 
			$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
			imagesetpixel($im, rand()%70 , rand()%30 , $randcolor);
		}
		
		App::import('Component', 'SessionComponent');
		$session = new SessionComponent($componentCollection);
		$session->write('Users.authCode', $authCode);
		
		ImagePNG($im);
		ImageDestroy($im);
	}
}