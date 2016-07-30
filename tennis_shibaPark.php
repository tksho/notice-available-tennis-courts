<?php
namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverCoordinates;

require_once __DIR__ . '/lib/php-webdriver-community/vendor/autoload.php';
require_once __DIR__ . '/lib/PHPMailer-master/PHPMailerAutoload.php';
require_once __DIR__ . '/const.h';
require_once __DIR__ . '/secret.h';

// Google Chrome起動
$host = 'http://localhost:4444/wd/hub';
$capabilities = DesiredCapabilities::chrome();
$driver = RemoteWebDriver::create($host, $capabilities, 5000);

// 今月の予約状況を確認（「芝公園」and「日比谷公園」）
checkEnableReserve($driver, $kMonth1, $kCort_shibapark, $kMailTitle_shibapark, $kMailBody_shibapark, $kSlackBody_shibapark);
checkEnableReserve($driver, $kMonth1, $kCort_hibiya, $kMailTitle_hibiya, $kMailBody_hibiya, $kSlackBody_hibiya);

// 翌月の予約状況も見られるなら確認
$bool = isEnableReserve_nextMonth($driver);
if($bool == true) {
	checkEnableReserve($driver, $kMonth2, $kCort_shibapark, $kMailTitle_shibapark, $kMailBody_shibapark, $kSlackBody_shibapark);
	checkEnableReserve($driver, $kMonth2, $kCort_hibiya, $kMailTitle_hibiya, $kMailBody_hibiya, $kSlackBody_hibiya);	
}

// Chromeクローズ
$driver->quit();

//--------------------------------------------------------------------------

/*
 *　指定月のテニスコートの、
 *　月火水木金の夜の予約状況を調べる
 */
function checkEnableReserve($inWebDriver, $inMonth, $inCort, $inMailTitle, $inMailBody, $inSlackbody) {

	global $kUrl; 					// テニス予約サイトURL
	global $kTopLightNaviFramename; // テニス予約サイトTOP 左ナビフレーム名
	global $kPcUser; 				// PCユーザ用ページリンク
	global $kAvailable_facl; 		// 施設の空き状況リンク
	global $kToSearch; 				// 検索ページリンク
	global $kShumoku;				// 種目リンク
	global $kTennis_shiba;			// 人工芝テニスリンク
	global $kMonth1;				// 検索条件
	global $kMonth2;
	global $kMonday;
	global $kTuesday;
	global $kWednesday;
	global $kThursday;
	global $kFriday;
	global $kSaturday;
	global $kSunday;
	global $kHoliday;
	global $kDoSearch;
	global $kCort_shibapark;
	global $kCort_hibiya;
	global $kCort_AOoi;
	global $kMailTitle_shibapark;
	global $kMailTitle_hibiya;
	global $kMailBody_shibapark;
	global $kMailBody_hibiya;
	global $kSlackBody_shibapark;
	global $kSlackBody_hibiya;
	global $kModoru;

	// テニスコート予約サイトへ遷移
	$inWebDriver->get($kUrl);
	sleep(4);

	// パソコンからのご利用はこちらをクリック
	click_a_tag_byHref($inWebDriver, $kPcUser);
	sleep(4);

	// 施設の空き状況をクリック
	$inWebDriver = $inWebDriver->switchTo()->frame($kTopLightNaviFramename);
	click_a_tag_byHref($inWebDriver, $kAvailable_facl);
	sleep(4);

	// 検索をクリック
	click_a_tag_byHref($inWebDriver, $kToSearch);
	sleep(4);

	// 種目をクリック
	click_a_tag_byHref($inWebDriver, $kShumoku);
	sleep(4);

	// テニス（人工芝）をクリック
	click_a_tag_byHref($inWebDriver, $kTennis_shiba);
	sleep(4);

	// 検索条件と「芝公園」をクリックして検索
	click_a_tag_byHref($inWebDriver, $inMonth);
	click_a_tag_byHref($inWebDriver, $kMonday);
	click_a_tag_byHref($inWebDriver, $kTuesday);
	click_a_tag_byHref($inWebDriver, $kWednesday);
	click_a_tag_byHref($inWebDriver, $kThursday);
	click_a_tag_byHref($inWebDriver, $kFriday);
	click_a_tag_byHref($inWebDriver, $inCort);
	click_a_tag_byHref($inWebDriver, $kDoSearch);
	sleep(4);

	// 夜（19:00〜21:00）に空きコートがある?
	$bool = isEnableReserve_night($inWebDriver);
	if( $bool == true) {
		// メールとslackで通知
		sendNotification_fromMyGmail($kMailUsername, $kMailPassword, $kMailFrom, $kMailTo, $inMailTitle, $inMailBody);
	    $text = urlencode($inSlackbody);
	    $url = "https://slack.com/api/chat.postMessage?token=${kSlackApiKey}&channel=%23${kChannel}&text=${text}&as_user=false";
	    file_get_contents($url);
	}

}

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

	$mailer 			= new \PHPMailer();
	$mailer->IsSMTP();
	$mailer->Host 		= 'ssl://smtp.gmail.com:465';
	$mailer->SMTPAuth 	= TRUE;
	$mailer->Username 	= $gmailUsername;  // Gmailのアカウント名
	$mailer->Password 	= $gmailPassword;  // Gmailのパスワード
	$mailer->From     	= $mailFromAddr; 	 // Fromのメールアドレス
	$mailer->FromName 	= mb_encode_mimeheader(mb_convert_encoding("コート空き検知システム","JIS","UTF-8"));
	$mailer->Subject  	= mb_encode_mimeheader(mb_convert_encoding($inTitle,"JIS","UTF-8"));
	$mailer->Body     	= $inBody;
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

	global $kUrl; 					// テニス予約サイトURL
	global $kTopLightNaviFramename; // テニス予約サイトTOP 左ナビフレーム名
	global $kPcUser; 				// PCユーザ用ページリンク
	global $kAvailable_facl; 		// 施設の空き状況リンク
	global $kToSearch; 				// 検索ページリンク
	global $kMonth2;				// 検索条件

	// テニスコート予約サイトへ遷移
	$inWebDriver->get($kUrl);
	sleep(4);

	// パソコンからのご利用はこちらをクリック
	click_a_tag_byHref($inWebDriver, $kPcUser);
	sleep(4);

	// 施設の空き状況をクリック
	$inWebDriver = $inWebDriver->switchTo()->frame($kTopLightNaviFramename);
	click_a_tag_byHref($inWebDriver, $kAvailable_facl);
	sleep(4);

	// 検索をクリック
	click_a_tag_byHref($inWebDriver, $kToSearch);
	sleep(4);

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
