<?php
$baseUrl = url::site().'console/media';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?=$baseUrl?>/js/fancybox/jquery.fancybox-1.3.1.css" />

	<title><?=html::chars($title)?></title>

	<script type="text/javascript" src="<?=$baseUrl?>/js/jquery-1.4.3.min.js"></script>
	<script type="text/javascript" src="<?=$baseUrl?>/js/tools.tooltip-1.1.3.min.js"></script>
	<script type="text/javascript" src="<?=$baseUrl?>/js/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
	<script type="text/javascript" src="<?=$baseUrl?>/js/main.js"></script>
</head>

<body>

<div class="container" id="page">
	<div id="header">
		<div class="top-menus">
			<?=html::anchor('http://kohanaframework.org/', 'Oficial'); ?> |
			<?=html::anchor('', 'Home'); ?>
		</div>
		<div id="logo"><?=html::image('console/media/images/logo.png')?></div>
	</div><!-- header -->

	<div class="container">
		<div class="span-4">
			<div id="sidebar">
				<div id="yw1" class="portlet">
					<div class="portlet-decoration">
					<div class="portlet-title">Generators</div>
					</div>
					<div class="portlet-content">
						<ul>
							<li><?=html::anchor(Route::get('console')->uri(array('command'=>'error')), 'Error generator');?></li>
							<li><?=html::anchor(Route::get('console')->uri(array('command'=>'orm')), 'Model generator');?></li>
						</ul>
					</div>
				</div>
			</div><!-- sidebar -->
		</div>
		<div class="span-16">
			<div id="content">
				<?=$content?>
			</div><!-- content -->
		</div>
		<div class="span-4 last">
			&nbsp;
		</div>
	</div>

</div><!-- page -->

<div id="footer">
</div><!-- footer -->

</body>
</html>