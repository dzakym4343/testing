<?php
error_reporting(0);
$email = $_POST['email'];
$pass = $_POST['password'];

$headers = array(
"sec-fetch-site:same-origin", 
"sec-gpc:1", 
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://mbasic.facebook.com/login/?refsrc=deprecated&_rdr");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 12) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Mobile Safari/537.36");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
curl_close($ch);
$scrape = scrape($result);
$lsd = $scrape->query('//input[@name="lsd"]')->item(0)->getAttribute('value');
$jazoest = $scrape->query('//input[@name="jazoest"]')->item(0)->getAttribute('value');
$m_ts = $scrape->query('//input[@name="m_ts"]')->item(0)->getAttribute('value');
$li = $scrape->query('//input[@name="li"]')->item(0)->getAttribute('value');

$chs = curl_init();
curl_setopt($chs, CURLOPT_URL, "https://mbasic.facebook.com/login/device-based/regular/login/?refsrc=deprecated&lwv=100");
curl_setopt($chs, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($chs, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chs, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 12) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Mobile Safari/537.36");
curl_setopt($chs, CURLOPT_HTTPHEADER, $headers);
curl_setopt($chs, CURLOPT_HEADER, true);
curl_setopt($chs, CURLOPT_POST, true);
curl_setopt($chs, CURLOPT_POSTFIELDS, "lsd=".$lsd."&jazoest=".$jazoest."&m_ts=".$m_ts."&li=".$li."&try_number=0&unrecognized_tries=0&email=".$email."&pass=".$pass."&login=Masuk&bi_xrwh=0");
$result2 = curl_exec($chs);
curl_close($chs);

preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result2, $cookies);

$cookie = '';
foreach ($cookies[1] as $values) {
	$cookie .= $values.'; ';
}
if(strpos($cookie, 'c_user=') !== false) {
	echo json_encode(["success" => true, "msg" => "success get cookie", "cookies" => urldecode($cookie)]);
//echo urldecode($cookie);
} else if(strpos($cookie, 'checkpoint=') !== false) {
	echo json_encode(["success" => false, "msg" => "Your Account Checkpoint!"]);
} else {
	echo json_encode(["success" => false, "msg" => "Invalid username/password"]);
} 



function scrape($web) {
$dom = new DOMDocument();
@$dom->loadHTML($web);
$xp = new DOMXpath($dom); 
return $xp;
} 
?>