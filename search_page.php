<title>Search Results</title>
<div class="container">
<?php
	include("connect.php");
	include("search_fetcher.php");
	include "navheader.html";
	
	$word = $_GET['q'];
	
	$results = searchForStuff($mysqli, $word);
	
	$results = json_decode($results, true);
	
	//var_dump($results);
	?>
	<h3>Search Results for "<?=$word?>"</h3>
	<?php
	foreach($results as $r)
	{
		?>
		<a class="searchresult" href="<?=$r['link']?>"><?=$r['display']?></a>
		<br>
		<br>
		<?php
	}
	
	

?>
	</div>
	<footer class="nav" class="site-footer">
				<a href="admin/index.php" class="fstd">Admin</a> - <a href="contact.php" class="fstd">Contact Us</a> - <a href="recognition.php" class="fstd">Acknowledgements</a>
		</footer>
	</body>
</html>