<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/AdminPage.php');

final class Dashboard extends AdminPage {

	protected function buildAdminPage(): void {
		?>
		<div class="content-wrapper py-3">
			<div class="container-fluid">
				<ol class="breadcrumb">
					<li class="breadcrumb-item active"><?php echo $this->meta_title; ?></li>
				</ol>
				<br>
				Welcome to the <?php echo $this->site->config["main"]["name"]; ?> admin panel!
				If you are seeing this page, it means you have Moderator access to <?php echo $this->site->config["main"]["name"]; ?>. By using this page, you agree to use it responsibly. If you are found abusing the system, your access to it will be revoked. This panel will allow you to:
				<li>Ban IPs, Consoles (MAC) and Profils</li>
				<li>Manage existing bans</li>
				<li>Manage console states (activated, pending, etc)</li>
				<li>Manage allowed games</li>
				<li>Manage profils</li>
				<li>Manage <?php echo $this->site->config["main"]["name"]; ?> server settings</li>
				<br>
				<br>
				Be sure that your server is based on EnergyCube <a href="https://github.com/energycube/dwc_network_server_emulator">Repos</a>.
				<br> Or that you have install the server with EnergyCube CoWFC & dwc_network_server_emulator <a href="https://github.com/EnergyCube/cowfc_installer">Installer</a> and not with any other version.
				<br> (Otherwise compatibility issues will occur !)
				<br><br>
				<ol class="breadcrumb">
					<li class="breadcrumb-item active">
						&copy; 2020 CoWFC<br>
						'Nintendo' and the Nintendo logo are registered trademarks of Nintendo Co., Ltd.</li>
					</li>
				</ol>
			</div>
		</div>
		<?php
	}
}
?>
