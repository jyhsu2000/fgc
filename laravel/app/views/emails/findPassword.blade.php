<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{ Config::get('config.sitename') }}</h2>

		<div>
			感謝支持 {{ Config::get('config.sitename') }} ，<br />
            請透過以下連結，重新設定密碼：<br />
            <a href="{{ URL::to('resetPassword') }}/{{ $findPwdCode }}">{{ URL::to('resetPassword') }}/{{ $findPwdCode }}</a><br />
            請注意：請盡快重新設定密碼，以免連結過期
		</div>
	</body>
</html>