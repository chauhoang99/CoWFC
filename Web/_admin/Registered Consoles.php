<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/AdminPage.php');

final class RegisteredConsoles extends AdminPage {
	private $reg_consoles = array();
	private $identifierActions = array(
		"rm" => "unregisterConsole",
		"ban" => "ban"
	);

	private function handleReq() {
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
		$this->reg_consoles = $this->site->database->getRegisteredConsoles();
		return;
	}

	private function buildRegisteredTable() {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;" id="dataTable">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>MAC Address</th><th>Platform</th><th>Serial Number (Wii)</th><th>Unregister</th><th>Ban</th>";
		echo '</tr></thead>';
		foreach($this->reg_consoles as $row){
			if($this->site->database->isBanned("console", $row[0]) == 0){
				echo "<tr>";
				echo "<td>";
				echo $row[0];
				echo "</td>";
				echo "<td>";
				echo $row[2];
				echo "</td>";
				echo "<td>";
				echo $row[1];
				echo "</td>";
				echo "<td>";
				echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='rm'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input type='submit' class='btn btn-primary' value='Unregister'></form>";
				echo "</td>";
				echo "<td>";
				echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='ban'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input class='form-control' style='width:225px;' type='text' name='reason' id='reason' placeholder='Reason'><input type='submit' class='btn btn-primary' value='Ban'></form>";
				echo "</td>";
				echo "</tr>";
			}
		}
		echo "</table>";
		return;
	}



	protected function buildAdminPage() {
		$this->handleReq();
		?>
		<div class="content-wrapper py-3">
			<div class="container-fluid">
				<ol class="breadcrumb">
					<li class="breadcrumb-item active"><?php echo $this->meta_title; ?></li>
				</ol>
				<div class="card mb-3">
					<div class="card-header">
						<i class="fa fa-table"></i> <?php echo $this->meta_title; ?>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<?php $this->buildRegisteredTable(); ?>
						</div>
					</div>
				</div>
			</div>
			<?php
			return;
		}
	}
	?>
