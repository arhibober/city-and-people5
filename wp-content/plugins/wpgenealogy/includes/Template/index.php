<!DOCTYPE html>
<html>
<head>
	<title>WP Genealogy</title>
</head>
<body>
	<?php 
		wp_head();
		 	while(have_posts()):
		 		the_post();
		 		the_content();
			endwhile;
		wp_footer();
	?>

</body>
</html>