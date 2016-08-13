<!DOCTYPE html>
<html>
<head>
	<title>Multi upload</title>
</head>
<body>
	<form method="post" enctype="multipart/form-data">
		<input type="file" name="imgs" />
		<input type="submit" value="Upload!" />
	</form>
</body>
</html>
<?php 

function debug($debug_datas){
	$datas = func_get_args();
	echo '<pre>';
	foreach ($datas as $key => $data) {
		print_r($data);echo '<br>';
	}
	die;
}

class ImageMultipleResizeUpload{
	private $store,$sizes,$multiFile,$fileName;

	public function __construct( $store = '',$fileName = '',$multiFile = true,$sizes = array('small' => 200,'middle' => 500,'big' => 900) ){
		$this->store = $store;
		$this->sizes = $sizes;
		$this->multiFile = $multiFile;
		$this->fileName = $fileName;
	}

	public function createImages(){
		if( empty($_FILES) ){
			return array('status' => 'faild','message' => '$_FILES is empty!');
		}
		if( $this->multiFile ){
			foreach ($_FILES[$this->fileName]['name'] as $k => $name) {
				$tmpName = $_FILES[$this->fileName]['tmp_name'][$k];
				if(	$_FILES[$this->fileName]['type'][$k] == 'image/jpeg' || 
					$_FILES[$this->fileName]['type'][$k] == 'image/jpg' ){

					$Name = date('U').'.jpg';
					$img = imagecreatefromjpeg($tmpName);
					$size = getimagesize($tmpName);	
					header('Content-Type: image/jpeg');
					foreach ($this->sizes as $dir => $newWidth) {
						if($size[0] > $newWidth){
							if(!is_dir($this->store.'/'.$dir)){
								mkdir($this->store.'/'.$dir);
							}
							$fullNameWithPath = $this->store.'/'.$dir.'/'.$Name;
							$width = $newWidth;
							$percent = $size[0]/$newWidth;
							$height = $size[1]/$percent;
							$dest = imagecreatetruecolor($width, $height);
							imagecopyresized($dest, $img, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
							imagejpeg($dest,$fullNameWithPath,75);
							imagedestroy($dest);
						}
					}
					imagedestroy($img);

				}elseif($_FILES[$this->fileName]['type'][$k] == 'image/png'){
					$Name = date('U').'.png';
					$img = imagecreatefrompng($tmpName);
					$size = getimagesize($tmpName);	
					header('Content-Type: image/png');
					foreach ($this->sizes as $dir => $newWidth) {
						if($size[0] > $newWidth){
							if(!is_dir($this->store.'/'.$dir)){
								mkdir($this->store.'/'.$dir);
							}
							$fullNameWithPath = $this->store.'/'.$dir.'/'.$Name;
							$width = $newWidth;
							$percent = $size[0]/$newWidth;
							$height = $size[1]/$percent;
							$dest = imagecreatetruecolor($width, $height);
							imagecopyresized($dest, $img, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
							imagepng($dest,$fullNameWithPath,75);
							imagedestroy($dest);
						}
					}
					imagedestroy($img);
				}elseif($_FILES[$this->fileName]['type'][$k] == 'image/gif'){
					$Name = date('U').'.gif';
					$img = imagecreatefrompng($tmpName);
					$size = getimagesize($tmpName);	
					header('Content-Type: image/gif');
					foreach ($this->sizes as $dir => $newWidth) {
						if($size[0] > $newWidth){
							if(!is_dir($this->store.'/'.$dir)){
								mkdir($this->store.'/'.$dir);
							}
							$fullNameWithPath = $this->store.'/'.$dir.'/'.$Name;
							$width = $newWidth;
							$percent = $size[0]/$newWidth;
							$height = $size[1]/$percent;
							$dest = imagecreatetruecolor($width, $height);
							imagecopyresized($dest, $img, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
							imagegif($dest,$fullNameWithPath);
							imagedestroy($dest);
						}
					}
					imagedestroy($img);
				}else{
					return array('status' => 'faild','message' => 'image type: '.$_FILES[$this->fileName]['type'][$k]);
				}
			}
		}else{
			if(	$_FILES[$this->fileName]['type'] == 'image/jpeg' || 
				$_FILES[$this->fileName]['type'] == 'image/jpg' ){

				$tmpName = $_FILES[$this->fileName]['tmp_name'];
				$Name = date('U').'.jpg';
				$img = imagecreatefromjpeg($tmpName);
				$size = getimagesize($tmpName);	
				header('Content-Type: image/jpeg');
				foreach ($this->sizes as $dir => $newWidth) {
					if($size[0] > $newWidth){
						if(!is_dir($this->store.'/'.$dir)){
							mkdir($this->store.'/'.$dir);
						}
						$fullNameWithPath = $this->store.'/'.$dir.'/'.$Name;
						$width = $newWidth;
						$percent = $size[0]/$newWidth;
						$height = $size[1]/$percent;
						$dest = imagecreatetruecolor($width, $height);
						imagecopyresized($dest, $img, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
						imagejpeg($dest,$fullNameWithPath,75);
						imagedestroy($dest);
					}
				}
				imagedestroy($img);
			}elseif( $_FILES[$this->fileName]['type'] == 'image/png' ){

				$tmpName = $_FILES[$this->fileName]['tmp_name'];
				$Name = date('U').'.png';
				$img = imagecreatefrompng($tmpName);
				$size = getimagesize($tmpName);	
				header('Content-Type: image/png');
				foreach ($this->sizes as $dir => $newWidth) {
					if($size[0] > $newWidth){
						if(!is_dir($this->store.'/'.$dir)){
							mkdir($this->store.'/'.$dir);
						}
						$fullNameWithPath = $this->store.'/'.$dir.'/'.$Name;
						$width = $newWidth;
						$percent = $size[0]/$newWidth;
						$height = $size[1]/$percent;
						$dest = imagecreatetruecolor($width, $height);
						imagecopyresized($dest, $img, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
						imagepng($dest,$fullNameWithPath,75);
						imagedestroy($dest);
					}
				}
				imagedestroy($img);
			}elseif( $_FILES[$this->fileName]['type'] == 'image/gif' ){

				$tmpName = $_FILES[$this->fileName]['tmp_name'];
				$Name = date('U').'.gif';
				$img = imagecreatefrompng($tmpName);
				$size = getimagesize($tmpName);	
				header('Content-Type: image/gif');
				foreach ($this->sizes as $dir => $newWidth) {
					if($size[0] > $newWidth){
						if(!is_dir($this->store.'/'.$dir)){
							mkdir($this->store.'/'.$dir);
						}
						$fullNameWithPath = $this->store.'/'.$dir.'/'.$Name;
						$width = $newWidth;
						$percent = $size[0]/$newWidth;
						$height = $size[1]/$percent;
						$dest = imagecreatetruecolor($width, $height);
						imagecopyresized($dest, $img, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
						imagegif($dest,$fullNameWithPath);
						imagedestroy($dest);
					}
				}
				imagedestroy($img);
			}else{
				return array('status' => 'faild','message' => 'image type: '.$_FILES[$this->fileName]['type']);
			}

		}
		return array('status' => 'ok');
	}
}

$image = new ImageMultipleResizeUpload('store','imgs',false,array('xs' => 250,'md' => 600,'lg' => 900));
$answer = $image->createImages();
debug($answer);

?>