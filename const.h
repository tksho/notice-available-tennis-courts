<?php

// テニス予約サイトURL
$kUrl = "https://yoyaku.sports.metro.tokyo.jp/";

// テニス予約サイトTOP 左ナビフレーム名
$kTopLightNaviFramename = "pawae1002";

// PCユーザ用ページリンク
$kPcUser = "https://yoyaku.sports.metro.tokyo.jp/web/index.jsp";

// 施設の空き状況リンク
$kAvailable_facl = "javascript:doActionFrame(((_dom%20==%203)%20?%20document.layers['disp'].document.formWTransInstSrchVacantAction%20:%20document.formWTransInstSrchVacantAction%20),%20gRsvWTransInstSrchVacantAction);";

// 検索ページリンク
$kToSearch = "javascript:doAction((_dom%20==%203)%20?%20document.layers['disp'].document.formWTransInstSrchMultipleAction%20:%20document.formWTransInstSrchMultipleAction,%20gRsvWTransInstSrchMultipleAction);";

// 種目リンク
$kShumoku	= "javascript:sendSelectWeekNum2((_dom%20==%203)%20?%20document.layers['disp'].document.formWTransInstSrchMultipleAction%20:%20document.formWTransInstSrchMultipleAction,%20gRsvWTransInstSrchPpsAction);";

// 人工芝テニスリンク
$kTennis_shiba	= "javascript:sendPpsCd((_dom%20==%203)%20?%20document.layers['disp'].document.formWTransInstSrchMultipleAction%20:%20document.formWTransInstSrchMultipleAction,%20gRsvWTransInstSrchMultipleAction,%20'0'%20,%20'2023')";

// 検索条件
$kMonth1 	= "javascript:changeMonthGif(0)";
$kMonth2 	= "javascript:changeMonthGif(1)";
$kMonday 	= "javascript:changeWeekGif((_dom%20==%203)%20?%20document.layers['disp'].document.weektype0%20:%20document.weektype0,%200);";
$kTuesday 	= "javascript:changeWeekGif((_dom%20==%203)%20?%20document.layers['disp'].document.weektype1%20:%20document.weektype1,%201);";
$kWednesday = "javascript:changeWeekGif((_dom%20==%203)%20?%20document.layers['disp'].document.weektype2%20:%20document.weektype2,%202);";
$kThursday 	= "javascript:changeWeekGif((_dom%20==%203)%20?%20document.layers['disp'].document.weektype3%20:%20document.weektype3,%203);";
$kFriday 	= "javascript:changeWeekGif((_dom%20==%203)%20?%20document.layers['disp'].document.weektype4%20:%20document.weektype4,%204);";
$kSaturday 	= "javascript:changeWeekGif((_dom%20==%203)%20?%20document.layers['disp'].document.weektype5%20:%20document.weektype5,%205);";
$kSunday 	= "javascript:changeWeekGif((_dom%20==%203)%20?%20document.layers['disp'].document.weektype6%20:%20document.weektype6,%206);";
$kHoliday 	= "javascript:changeWeekGif((_dom%20==%203)%20?%20document.layers['disp'].document.weektype7%20:%20document.weektype7,%207);";
$kDoSearch	= "javascript:sendSelectWeekNum((_dom%20==%203)%20?%20document.layers['disp'].document.formWTransInstSrchMultipleAction%20:%20document.formWTransInstSrchMultipleAction,%20gRsvWGetInstSrchInfAction);";

// テニスコートのボタン
$kCort_shibapark 	= "javascript:changeSelectBtnGif(1,%2027,%200002);";
$kCort_shinozaki 	= "javascript:changeSelectBtnGif(12,%2027,%200022);";
$kCort_Ariake 	= "javascript:changeSelectBtnGif(24,%2027,%200061);";
$kCort_AOoi	 	= "javascript:changeSelectBtnGif(26,%2027,%200065);";

?>
