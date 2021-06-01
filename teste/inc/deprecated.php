<?php
	$useragent = $_SERVER['HTTP_USER_AGENT'];
	 
	if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
		$browser_version=$matched[1];
		$browser = 'IE';
	} elseif (preg_match( '|Opera/([0-9].[0-9]{1,2})|',$useragent,$matched)) {
		$browser_version=$matched[1];
		$browser = 'Opera';
	} elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
		$browser_version=$matched[1];
		$browser = 'Firefox';
	} elseif(preg_match('|Chrome/([0-9\.]+)|',$useragent,$matched)) {
		$browser_version=$matched[1];
		$browser = 'Chrome';
	} elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
		$browser_version=$matched[1];
		$browser = 'Safari';
	} else {
	// browser not recognized!
		$browser_version = 0;
		$browser= 'other';
	}
	
	if($browser == "IE" && $browser_version < 9) {
?>
	<div align="center" style="width: 100%; background: #FFC; border: 2px solid #FC3; line-height: 2em; padding: 10px; margin-bottom: 30px; font-size: 12px;">
		<span style="font-size: 16px; font-weight: bold;"><?=$translate->translate("deprecated_navegador")?></span>
		<br />
		<a href="https://www.google.com/chrome?hl=pt-br" title="Google Chrome">Google Chrome</a>
		&nbsp;&nbsp; | &nbsp;&nbsp; 
		<a href="http://www.mozilla.org/pt-BR/firefox/new/" title="Mozilla Firefox">Mozilla Firefox</a>
		&nbsp;&nbsp; | &nbsp;&nbsp; 
		<a href="http://www.apple.com/br/safari/download/" title="Safari">Safari</a>
		&nbsp;&nbsp; | &nbsp;&nbsp; 
		<a href="http://www.opera.com/download/" title="Opera">Opera</a>
		&nbsp;&nbsp; | &nbsp;&nbsp; 
		<a href="http://windows.microsoft.com/pt-BR/internet-explorer/download-ie" title="Internet Explorer">Internet Explorer</a>
	</div>
<?
	}
?>	