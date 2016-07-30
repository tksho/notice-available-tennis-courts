<?php
namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverCoordinates;

require_once __DIR__ . '/lib/php-webdriver-community/vendor/autoload.php';
require_once __DIR__ . '/lib/PHPMailer-master/PHPMailerAutoload.php';
require_once __DIR__ . '/const.h';
require_once __DIR__ . '/secret.h';

// Chrome起動
$host = 'http://localhost:4444/wd/hub'; // this is the default
$capabilities = DesiredCapabilities::chrome();
$driver = RemoteWebDriver::create($host, $capabilities, 5000);

// テニスコート予約サイトへ遷移
$driver->get($kUrl);
sleep(4);

// パソコンからのご利用はこちらをクリック
click_a_tag_byHref($driver, $kPcUser);
sleep(4);

// 施設の空き状況をクリック
$driver = $driver->switchTo()->frame($kTopLightNaviFramename);
click_a_tag_byHref($driver, $kAvailable_facl);
sleep(4);

// 検索をクリック
click_a_tag_byHref($driver, $kToSearch);
sleep(4);

// 種目をクリック
click_a_tag_byHref($driver, $kShumoku);
sleep(4);

// テニス（人工芝）をクリック
click_a_tag_byHref($driver, $kTennis_shiba);
sleep(4);

// 検索条件と「芝公園」をクリックして検索
click_a_tag_byHref($driver, $kMonth1);
click_a_tag_byHref($driver, $kMonday);
click_a_tag_byHref($driver, $kTuesday);
click_a_tag_byHref($driver, $kWednesday);
click_a_tag_byHref($driver, $kThursday);
click_a_tag_byHref($driver, $kFriday);
click_a_tag_byHref($driver, $kCort_shibapark);
click_a_tag_byHref($driver, $kDoSearch);
sleep(4);

// 夜（19:00〜21:00）に空きコートがある?
$bool = isEnableReserve_night($driver);
if( $bool == true) {
	// メールとslackで通知
	sendNotification_fromMyGmail($kMailUsername, $kMailPassword, $kMailFrom, $kMailTo, $kMailTitle_shibapark, $kMailBody_shibapark);
    $text = urlencode($kSlackBody_shibapark);
    $url = "https://slack.com/api/chat.postMessage?token=${kSlackApiKey}&channel=%23${kChannel}&text=${text}&as_user=false";
    file_get_contents($url);
}
sleep(4);

// [戻る]ボタンを押す
click_a_tag_byHref($driver, $kModoru);
sleep(4);

// 「日比谷公園」を選択して検索
click_a_tag_byHref($driver, $kCort_shibapark);
click_a_tag_byHref($driver, $kCort_hibiya);
click_a_tag_byHref($driver, $kDoSearch);
sleep(4);

// 夜（19:00〜21:00）に空きコートがある？
$bool = isEnableReserve_night($driver);
if( $bool == true) {
	// メールとslackで通知
	sendNotification_fromMyGmail($kMailUsername, $kMailPassword, $kMailFrom, $kMailTo, $kMailTitle_hibiya, $kMailBody_hibiya);
    $text = urlencode($kSlackBody_hibiya);
    $url = "https://slack.com/api/chat.postMessage?token=${kSlackApiKey}&channel=%23${kChannel}&text=${text}&as_user=false";
    file_get_contents($url);
}

// [戻る]ボタンを押す
click_a_tag_byHref($driver, $kModoru);
sleep(4);


// 翌月の予約状況も確認できるなら調べる（日比谷公園->芝公園の順番）
$bool = isEnableReserve_nextMonth($driver);
if($bool == true) {
	click_a_tag_byHref($driver, $kMonth2);
	click_a_tag_byHref($driver, $kDoSearch);
	sleep(4);

	// 夜（19:00〜21:00）に空きコートがある？
	$bool = isEnableReserve_night($driver);
	if( $bool == true) {
		// メールとslackで通知
		sendNotification_fromMyGmail($kMailUsername, $kMailPassword, $kMailFrom, $kMailTo, $kMailTitle_hibiya, $kMailBody_hibiya);
	    $text = urlencode($kSlackBody_hibiya);
	    $url = "https://slack.com/api/chat.postMessage?token=${kSlackApiKey}&channel=%23${kChannel}&text=${text}&as_user=false";
	    file_get_contents($url);
	}

	// [戻る]ボタンを押す
	click_a_tag_byHref($driver, $kModoru);
	sleep(4);

	// 「芝公園」のみを選択
	click_a_tag_byHref($driver, $kCort_shibapark);
	click_a_tag_byHref($driver, $kCort_hibiya);
	click_a_tag_byHref($driver, $kDoSearch);
	sleep(4);

	// 夜（19:00〜21:00）に空きコートがある？
	$bool = isEnableReserve_night($driver);
	if( $bool == true) {
		// メールとslackで通知
		sendNotification_fromMyGmail($kMailUsername, $kMailPassword, $kMailFrom, $kMailTo, $kMailTitle_shibapark, $kMailBody_shibapark);
	    $text = urlencode($kSlackBody_shibapark);
	    $url = "https://slack.com/api/chat.postMessage?token=${kSlackApiKey}&channel=%23${kChannel}&text=${text}&as_user=false";
	    file_get_contents($url);
	}
}

// Chromeクローズ
$driver->quit();

//--------------------------------------------------------------------------

/*
 * 指定のhrefのついた<a>タグをクリック
 */
function click_a_tag_byHref($inWebDriver, $inHref) {	

	// ページ内の<a>タグ全て取得
	$link = $inWebDriver->findElements(
		WebDriverBy::tagName('a')
	);

	// 指定のhrefを持つ<a>タグをクリック
	foreach( $link as $value )
	{
		if( strcmp($value->getAttribute('href'), $inHref) == 0 )
		{
			$value->click();
			break;
		}
	}
}

/*
 *　テニスコートが空いてることをGmailで通知
 */
function sendNotification_fromMyGmail($gmailUsername, $gmailPassword, $mailFromAddr, $mailToAddr, $inTitle, $inBody) {

	mb_language("japanese");
	mb_internal_encoding("UTF-8");

	$mailer = new \PHPMailer();
	$mailer->IsSMTP();
	$mailer->Host = 'ssl://smtp.gmail.com:465';
	$mailer->SMTPAuth = TRUE;
	$mailer->Username = $gmailUsername;  // Gmailのアカウント名
	$mailer->Password = $gmailPassword;  // Gmailのパスワード
	$mailer->From     = $mailFromAddr; 	 // Fromのメールアドレス
	$mailer->FromName = mb_encode_mimeheader(mb_convert_encoding("コート空き検知システム","JIS","UTF-8"));
	$mailer->Subject  = mb_encode_mimeheader(mb_convert_encoding($inTitle,"JIS","UTF-8"));
	$mailer->Body     = $inBody;
	$mailer->AddAddress($mailToAddr); // 宛先
	  
	if( !$mailer->Send() ){
		echo "Message was not sent<br/ >";
		echo "Mailer Error: " . $mailer->ErrorInfo;
	} else {
		echo "Message has been sent";
	}
}

/*
 *　夜（19:00〜21:00）の空きコートがあるか？
 *  戻り値 true:あり、false：なし
 */
function isEnableReserve_night($inWebDriver) {

	$link = $inWebDriver->findElements(
		WebDriverBy::cssSelector("table tbody tr[bgcolor='#ffffff'] td:last-child")
	);
	foreach( $link as $value )
	{
		$numSpare = $value->gettext();

		if( strcmp($numSpare,"－") != 0 && strcmp($numSpare,"×") != 0 ) {
			return true;
		}
	}
	return false;
}

/*
 *　翌月の予約状況も見られるか？
 *  戻り値 true:あり、false：なし
 */
function isEnableReserve_nextMonth($inWebDriver) {

	// ページ内の<a>タグ全て取得
	$link = $inWebDriver->findElements(
		WebDriverBy::tagName('a')
	);

	// 翌月のボタンがある？
	foreach( $link as $value )
	{
		if( strcmp($value->getAttribute('href'), $kMonth2) == 0 )
		{
			return true;
		}
	}

	return false;
}

?>
