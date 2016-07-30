<?php

//-------------------
// 画面遷移に関するリンク
//-------------------
// テニス予約サイトURL
$kUrl = "https://yoyaku.sports.metro.tokyo.jp/";
// テニス予約サイトTOP 左ナビフレーム名
$kTopLightNaviFramename = "pawae1002";
// PCユーザ用ページリンク
$kPcUser = "https://yoyaku.sports.metro.tokyo.jp/web/";
// 施設の空き状況リンク
$kAvailable_facl = "javascript:doActionFrame(((_dom == 3) ? document.layers['disp'].document.formWTransInstSrchVacantAction : document.formWTransInstSrchVacantAction ), gRsvWTransInstSrchVacantAction);";
// 検索ページリンク
$kToSearch = "javascript:doComplexSearchAction((_dom == 3) ? document.layers['disp'].document.form1 : document.form1, gRsvWTransInstSrchMultipleAction);";
// 種目リンク
$kShumoku	= "javascript:sendSelectWeekNum2((_dom == 3) ? document.layers['disp'].document.form1 : document.form1, gRsvWTransInstSrchPpsAction);";
// 「テニス（人工芝）」リンク
$kTennis_shiba	= "javascript:doTransInstSrchMultipleAction((_dom == 3) ? document.layers['disp'].document.form1 : document.form1, gRsvWTransInstSrchMultipleAction, '1000' , '1030')";
// 戻るボタンのリンク
$kModoru = "javascript:doAction((_dom == 3) ? document.layers['disp'].document.form1 : document.form1, gRsvWInstSrchVacantBackAction);";

//-------------------
// 検索条件に関するリンク
//-------------------
// 今月ボタンのリンク
$kMonth1 	= "javascript:changeMonthGif(0)";
// 翌月ボタンのリンク
$kMonth2 	= "javascript:changeMonthGif(1)";
// 曜日ボタンのリンク
$kMonday 	= "javascript:changeWeekGif((_dom == 3) ? document.layers['disp'].document.weektype0 : document.weektype0, 0);";
$kTuesday 	= "javascript:changeWeekGif((_dom == 3) ? document.layers['disp'].document.weektype1 : document.weektype1, 1);";
$kWednesday = "javascript:changeWeekGif((_dom == 3) ? document.layers['disp'].document.weektype2 : document.weektype2, 2);";
$kThursday 	= "javascript:changeWeekGif((_dom == 3) ? document.layers['disp'].document.weektype3 : document.weektype3, 3);";
$kFriday 	= "javascript:changeWeekGif((_dom == 3) ? document.layers['disp'].document.weektype4 : document.weektype4, 4);";
$kSaturday 	= "javascript:changeWeekGif((_dom == 3) ? document.layers['disp'].document.weektype5 : document.weektype5, 5);";
$kSunday 	= "javascript:changeWeekGif((_dom == 3) ? document.layers['disp'].document.weektype6 : document.weektype6, 6);";
$kHoliday 	= "javascript:changeWeekGif((_dom == 3) ? document.layers['disp'].document.weektype7 : document.weektype7, 7);";
// 検索するボタンのリンク
$kDoSearch	= "javascript:sendSelectWeekNum((_dom == 3) ? document.layers['disp'].document.form1 : document.form1, gRsvWGetInstSrchInfAction);";
// 各テニスコートのボタンのリンク
$kCort_shibapark 	= "javascript:changeSelectBtnGif(1, 27, 1010);";
$kCort_hibiya	 	= "javascript:changeSelectBtnGif(0, 27, 1000);";
$kCort_AOoi	 	= "javascript:changeSelectBtnGif(25, 27, 1310);";

//-------------------
// 各通知の件名と本文
//-------------------
// メール件名
$kMailTitle_shibapark = "芝公園のテニスコートが空いたでー";
$kMailTitle_hibiya = "日比谷公園のテニスコートが空いたでー";
// メール本文
$kMailBody_shibapark = "Hello\n\nYou're able to reserve the tennis court at Shiba-park, between 19:00-21:00, weekday. \n\nLet's Reservation!\nhttps://yoyaku.sports.metro.tokyo.jp/";
$kMailBody_hibiya = "Hello\n\nYou're able to reserve the tennis court at Hibiya-park, between 19:00-21:00, weekday. \n\nLet's Reservation!\nhttps://yoyaku.sports.metro.tokyo.jp/";
// slack投稿本文
$kSlackBody_shibapark = '芝公園のテニスコートが空いてるよ!予約しよう！https://yoyaku.sports.metro.tokyo.jp/';
$kSlackBody_hibiya = '日比谷公園のテニスコートが空いてるよ!予約しよう！https://yoyaku.sports.metro.tokyo.jp/';



?>
