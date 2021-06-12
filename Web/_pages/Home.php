<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/Page.php');

final class Home extends Page {
	
	protected function buildHeader(): void {
		$this->meta_title = "The Nintendo WFC alternative!";
		$this->header->build();
		return;
	}
	
	protected function buildPage(): void {
?>
<section id="banner" style="">
	<div class="content">
		<header>
			<h2>Welcome to <?php echo $this->site->config["main"]["name"]; ?></h2>
			<p>
			<strong>The Nintendo WFC alternative!</strong><br>
			Join the official Discord server:<br>
			<a href="https://discord.gg/XxXxXxX">https://discord.gg/XxXxXxX</a>
			</p>
		</header>
		<span class="image"><img src="images/cowfc.png" alt=""></span>
	</div>
</section>
<?php
return;
	}
}
?>
