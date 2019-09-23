<?php 
ini_set('max_execution_time', 0);
header("refresh: 1");
//chdir($_SERVER['DOCUMENT_ROOT']);	

include('aws-autoloader.php');
use Aws\Credentials\CredentialProvider;
use Aws\Exception\AwsException;
// Create an S3 client
$client = new Aws\S3\S3Client([
	'region' 			=> 'ap-south-1',
	'version' 			=> 'latest',
	'signature_version' => 'v4',
	'credentials' 		=> [
		'key' 		=> 'AKIA5H55QG3QUVUS3VXD',
		'secret' 	=> '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',
	],
]);

$dir 		= $_SERVER['DOCUMENT_ROOT'].'/s3fs/sm_sa/';
echo $dir.'<br>';
$copyPath 	= 'moved_sm_sa/';

if(is_dir($dir)) {
    if($dh = opendir($dir)) {
        while(($file = readdir($dh)) !== false) {
			if($file != "." && $file != "..") {
				//echo "$entry\n";
				print'<pre>';
				echo 'Moving files...'.'<br>';
				echo "filename: $file";
				$result = $client->putObject([
					'Bucket' 		=> 'aakash-prod-digital',
					'Key'    		=> 'html-images/sm_sa/'.$file,
					'SourceFile' 	=> $dir . $file			
				]);

				$info 	= $client->doesObjectExist('aakash-prod-digital', 'html-images/sm_sa/'.$file);
				if ($info) {
					copy($dir.$file,$copyPath.$file);
					unlink($dir . $file);
				}
				//$count++;
			}else{
				//header("location:complete.php");
			}
        }
        closedir($dh);
    }else{
		echo "Directory not opening";
	}
}else{
	echo "Not a directory";
}

