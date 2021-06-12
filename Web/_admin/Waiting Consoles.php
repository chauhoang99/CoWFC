<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/AdminPage.php');

final class WaitingConsoles extends AdminPage {

	private $pend_consoles = array();

	private $identifierActions = array(
		"rg" => "registerConsole",
		"unban" => "unbanConsole"
	);

	private function handleReq(): void {
		if(isset($_POST["action"], $_POST["identifier"])){
			switch($_POST["action"]){
				case "ban":
					if(isset($_POST["reason"]))
						$this->site->database->ban("Console", array(), $_POST["identifier"], $_POST["reason"]);
				break;
				default:
					if(array_key_exists($_POST["action"], $this->identifierActions))
						$this->site->database->{$this->identifierActions[$_POST["action"]]}($_POST["identifier"]);
				break;
			}
		}
		$this->pend_consoles = $this->site->database->getPendingConsoles();
		return;
	}

	private function buildPendRegisteredTable(): void {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;" id="dataTable">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>MAC Address</th><th>Authorize</th><th>Disallow (Ban)</th>";
		echo '</tr></thead>';
		foreach($this->pend_consoles as $row){
			echo "<tr>";
			echo "<td>";
			echo $row[0];
			echo "</td>";
			echo "<td>";
			echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='rg'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input type='submit' class='btn btn-primary' value='Authorize'></form>";
			echo "</td>";
			echo "<td>";
			echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='ban'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input class='form-control' style='width:225px;' type='text' name='reason' id='reason' placeholder='Reason'><input type='submit' class='btn btn-primary' value='Ban'></form>";
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
		return;
	}



	private function generateMessage(): void {
		$value = $this->site->database->getSetting("console_manualactivation");
		echo "<br>";
		if($value == 0){
			echo "Manual Console Activation is disabled, all new consoles will be automatically activated.";
		}else if($value == 1){
			echo "Manual Console Activation is enabled, all new consoles will require a manual validation.";
		}else{
			echo "Database problem ! Value console_manualactivation in settings not found (or not 0 or 1)! Check that the version of CoWFC is the same as that of the dwc server in Settings or Dashboard.";
		}
		return;
	}

	protected function buildAdminPage(): void {
		$this->handleReq();
		?>
		<div class="content-wrapper py-3">
			<div class="container-fluid">
				<ol class="breadcrumb">
					<li class="breadcrumb-item active">
						<?php echo $this->meta_title; ?> - You can change manual activation setting at any moment : <a href="/?page=admin&section=settings">here</a>
						<?php echo $this->generateMessage(); ?>
					</li>
				</ol>
				<div class="card mb-3">
					<div class="card-header">
						<i class="fa fa-table"></i>
						<?php echo $this->meta_title; ?>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<?php $this->buildPendRegisteredTable(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		return;
	}
}
?>
