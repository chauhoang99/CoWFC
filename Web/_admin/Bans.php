<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/AdminPage.php');

final class Bans extends AdminPage {
	private $ip_bans = array();
	private $banned_consoles = array();
	private $abused_consoles = array();
	private $identifierActions = array(
		"unbanIP" => "unbanIP",
		"unbanConsole" => "unbanConsole"
	);

	private function handleReq() {
		if(isset($_POST["action"], $_POST["identifier"])){
			switch($_POST["action"]){
				case "ban":
					if(isset($_POST["reason"]))
						$this->site->database->ban("IP", array(), $_POST["identifier"], $_POST["reason"]);
				break;
				case "banmac":
					if(isset($_POST["reason"]))
						$this->site->database->ban("Console", array(), $_POST["identifier"], $_POST["reason"]);
				break;
				default:
				if(array_key_exists($_POST["action"], $this->identifierActions))
					$this->site->database->{$this->identifierActions[$_POST["action"]]}($_POST["identifier"]);
				break;
			}
		}

		$this->ip_bans = $this->site->database->getIPBans();
		$this->banned_consoles = $this->site->database->getBannedConsoles();
		$this->abused_consoles = $this->site->database->getAbusedConsoles();
	}

	private function buildIPTable() {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>Adresse IP</th><th>Unban IP</th><th>Appliqué le </th><th>Durée</th><th>Raison</th>";
		echo '</tr></thead>';
		foreach($this->ip_bans as $row){
			echo "<tr>";
			echo "<td>";
			echo $row[0];
			echo "</td>";
			echo "<td>";
			echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='unbanIP'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input type='submit' class='btn btn-primary' value='Unban IP'></form>";
			echo "</td>";
			echo "<td>";
			echo date('m/d/Y H:i:s', $row[1]);
			echo "</td>";
			echo "<td>";
			if ($row[3] == 99999999999)
			echo 'Forever';
			else
			echo date('m/d/Y H:i:s', $row[3]);
			echo "</td>";
			echo "<td>";
			echo htmlentities($row[2]);
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}

	private function buildBannedTable() {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>MAC Address</th><th>Unban MAC</th><th>Timestamp</th><th>Until</th><th>Reason</th>";
		echo '</tr></thead>';
		foreach($this->banned_consoles as $row){
			echo "<tr>";
			echo "<td>";
			echo $row[0];
			echo "</td>";
			echo "<td>";
			echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='unbanConsole'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input type='submit' class='btn btn-primary' value='Unban MAC'></form>";
			echo "<td>";
			echo date('m/d/Y H:i:s', $row[1]);
			echo "</td>";
			echo "<td>";
			if ($row[3] == 99999999999)
			echo 'Forever';
			else
			echo date('m/d/Y H:i:s', $row[3]);
			echo "</td>";
			echo "<td>";
			echo htmlentities($row[2]);
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}

	private function buildAbusedTable() {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>MAC Address</th>";
		echo '</tr></thead>';
		foreach($this->abused_consoles as $row){
			echo "<tr>";
			echo "<td>";
			echo $row[0];
			echo "</td>";
			echo "</tr>";

		}
	}

	protected function buildAdminPage() {
		$this->handleReq();
		?>
		<div class="content-wrapper py-3">
			<div class="container-fluid">
				<ol class="breadcrumb">
					<li class="breadcrumb-item active"><?php echo $this->meta_title; ?></li>
				</ol>
				<form action='' method='post'>Ban IP address: <input type='hidden' name='action' id='action' value='ban'><input class='form-control' style='width:175px;' type='text' name='identifier' id='identifier' placeholder='IP' maxlength='15'><input class='form-control' style='width:225px;' type='text' name='reason' id='reason' placeholder='Reason'><input type='submit' class='btn btn-primary' value='Ban IP'></form>
				<form action='' method='post'>Ban MAC address: <input type='hidden' name='action' id='action' value='banmac'><input class='form-control' style='width:175px;' type='text' name='identifier' id='identifier' maxlength='12'><input class='form-control' style='width:225px;' type='text' name='reason' id='reason' placeholder='Reason'><input type='submit' class='btn btn-primary' value='Ban MAC'></form>
			</div>
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-table"></i>
					Banned IP
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<?php $this->buildIPTable(); ?>
					</div>
				</div>
			</div>
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-table"></i>
					Banned Consoles
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<?php $this->buildBannedTable(); ?>
					</div>
				</div>
			</div>
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-table"></i>
					Abused Consoles
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<?php $this->buildAbusedTable(); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
?>
