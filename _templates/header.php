<!DocType html>
<head>
<meta charset="UTF-8">
<!-- Viewport for Mobile Devices -->
<meta name="viewport"                   content="width=device-width, initial-scale=1.0">
<!-- Meta Info -->
<title><?php echo $Page['Title']; ?></title>
<meta name="description"                content="<?php echo $Page['Description']; ?>">
<meta name="author"                     content="<?php echo $Page['Author']; ?>">
<meta name="theme-color"                content="<?php echo $Page['Theme Color']; ?>">
<!-- Optimizations for Google -->
<meta itemprop="name"                   content="<?php echo $Page['Title']; ?>" />
<meta itemprop="description"            content="<?php echo $Page['Description']; ?>" />
<meta itemprop="image"                  content="<?php echo $Page['Image']; ?>" />
<!-- Optimizations for Facebook -->
<meta property="og:title"               content="<?php echo $Page['Title']; ?>" />
<meta property="og:description"         content="<?php echo $Page['Description']; ?>" />
<meta property="og:image"               content="<?php echo $Page['Image']; ?>" />
<!-- Optimizations for Twitter -->
<meta name="twitter:title"              content="<?php echo $Page['Title']; ?>">
<meta name="twitter:description"        content="<?php echo $Page['Description']; ?>">
<meta name="twitter:image"              content="<?php echo $Page['Image']; ?>" />
<meta name="twitter:site"               content="<?php echo $Page['Twitter Site']; ?>">
<meta name="twitter:creator"            content="<?php echo $Page['Twitter Author']; ?>">
<?php
	if ( !empty($Page['Image']) ) {
		echo '<meta name="twitter:card"               content="summary_large_image">';
	} else {
		echo '<meta name="twitter:card"               content="summary">';
	}
	echo "\n";
?>
<!-- iPinning -->
<meta name="apple-mobile-web-app-title" content="<?php echo $Sitewide['Settings']['Site Title']; ?>">
<!-- Android Pinning -->
<link rel="manifest"                    href="<?php echo $Sitewide['Settings']['Site Root']; ?>assets/manifest.json">
<!-- Favicon -->
<link rel="shortcut icon"               href="<?php echo $Sitewide['Settings']['Site Root']; ?>assets/icons/favicon.ico">
<link rel="apple-touch-icon"            href="<?php echo $Sitewide['Settings']['Site Root']; ?>assets/icons/apple-touch-icon.png">
<link rel="icon" type="image/png"       href="<?php echo $Sitewide['Settings']['Site Root']; ?>assets/icons/favicon.png" sizes="256x256">
<!-- Authorship -->
<link rel="author"                      href="<?php echo $Page['Google+ Author']; ?>" title="<?php echo $Page['Author Name']; ?>"/>
<!-- Stylesheets -->
<link rel="stylesheet"                  href="<?php echo $Sitewide['Assets']['External']['CSS']; ?>normalize.min.css">
<!-- Grid.css -->
<link rel="stylesheet"                  href="<?php echo $Sitewide['Assets']['External']['CSS']; ?>grid.css">
<!-- Fontawesome -->
<link rel="stylesheet"                  href="<?php echo $Sitewide['Settings']['Site Root']; ?>assets/font-awesome-4.5.0/css/font-awesome.min.css">
<!-- Tablesorter -->
<link rel="stylesheet"                  href="<?php echo $Sitewide['Assets']['External']['CSS']; ?>tablesorter/theme.default.min.css">
<link rel="stylesheet"                  href="<?php echo $Sitewide['Assets']['External']['CSS']; ?>colors.min.css">
<link rel="stylesheet"                  href="<?php echo $Sitewide['Assets']['External']['CSS']; ?>styles.css">
<?php
	if (!empty($Page['CSS'])) {
		foreach ($Page['CSS'] as $Stylsheet) {
			echo '<link rel="stylesheet"                  href="'.$Stylsheet.'">'."\n";
		}
	}
?>
<!-- JavaScripts -->
<script src="<?php echo $Sitewide['Assets']['External']['JS']; ?>jquery@2.2.0,jquery.equalize,jquery.downboy,jquery.leanmodal2,jquery.autosize.min.js"></script>
<script src="<?php echo $Sitewide['Assets']['External']['JS']; ?>tablesorter/jquery.tablesorter.combined.min.js"></script>
<script>
<?php include_once $Sitewide['Assets']['Internal']['JS'].'external-links.js'; ?>
</script>
<script>
<?php include_once $Sitewide['Assets']['Internal']['JS'].'smooth-scrolling.js'; ?>
</script>
<script>
$(function() {
	autosize($('textarea'));
	$('table.tablesorter').tablesorter();
});
</script>
<?php
	if (!empty($Page['JS'])) {
		foreach ( $Page['JS'] as $Script ) {
			echo '<script src="'.$Script.'"></script>'."\n";
		}
	}
	echo !empty($Page['Header']) ? $Page['Header'] : false;
	puff_hook('head');
?>
<!-- Website Logo Schema -->
<script type="application/ld+json">
{
	"@context"            : "http://schema.org/",
	"@type"               : "Organization",
	"url"                 : "<?php echo $Sitewide['Request']['Scheme'].'://'.$Sitewide['Request']['Host']; ?>",
	"logo"                : "<?php echo $Sitewide['Assets']['External']['Image']; ?>logo.png"
}
</script>
<!-- Website Name Schema -->
<script type="application/ld+json">
{
	"@context"            : "http://schema.org",
	"@type"               : "WebSite",
	"name"                : "<?php echo $Sitewide['Settings']['Site Title']; ?>",
	"alternateName"       : "<?php echo $Sitewide['Settings']['Alternative Site Title']; ?>",
	"url"                 : "<?php echo $Sitewide['Request']['Scheme'].'://'.$Sitewide['Request']['Host']; ?>"
}
</script>
<!-- Website Social Schema -->
<script type="application/ld+json">
<?php
echo '{
	"@context"            : "http://schema.org",
	"@type"               : "Organization",
	"name"                : "'.$Sitewide['Settings']['Site Title'].'",
	"url"                 : "'.$Sitewide['Request']['Scheme'].'://'.$Sitewide['Request']['Host'].'",
	"sameAs"              : [';
	$Socials = '';
	foreach ( $Sitewide['Social'] as $Social ) {
		$Socials .= "\n".'		"'.$Social.'",';
	}
	echo trim($Socials, ',');
echo '
	]
}
';
?>
</script>
<?php
if (
	!empty($Page['Type']) &&
	$Page['Type'] == 'Article'
) {
	echo '<!-- Article Schema -->
<script type="application/ld+json">
{
	"@context"            : "http://schema.org",
	"@type"               : "NewsArticle",
	"headline"            : "'.$Page['Title'].'",
	"alternativeHeadline" : "'.$Page['Tagline'].'",
	"datePublished"       : "'.$Page['Published'].'",
	"description"         : "'.$Page['Description'].'",
	"image"               : [';
	if ( !empty($Page['Images']) ) {
		$Images = '';
		foreach ( $Page['Images'] as $Image ) {
			$Images .= "\n".'		"'.$Image.'",';
		}
		echo trim($Images, ',');
	}
	echo '
	]
}
</script>
';
}
?>
</head>
<body class="page-<?php echo $Sitewide['Request']['AutoLink']; ?>">
<?php
	puff_hook('header');
	puff_hook('navigation');
	puff_hook('pre-content');
