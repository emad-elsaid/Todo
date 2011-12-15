<?php 
include 'connection.php';
header('Content-Type: text/xml'); 
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<todo>
	
	<?php 
		$total_query = mysql_query("select count(*) as total from `items`");
		$total = mysql_fetch_object($total_query);
	?>
	<total><?=$total->total?></total>
	
	<?php
		$sort = json_decode($_GET['sort']);
		$items = mysql_query(sprintf(
					"select * from `items` order by `%s` %s limit %d offset %d",
					mysql_real_escape_string($sort[0]->property),
					mysql_real_escape_string($sort[0]->direction),
					mysql_real_escape_string($_GET['limit']),
					mysql_real_escape_string($_GET['start'])
				)); 
	?>	
    <?php while( $item = mysql_fetch_object($items)): ?>
    <item>
        <id><?=$item->id?></id>
        <text><?=$item->text?></text>
        <done><?=$item->done?></done>
    </item>
    <?php endwhile; ?>
</todo>