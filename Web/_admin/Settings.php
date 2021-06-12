<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_site/AdminPage.php');

final class Settings extends AdminPage {

	private $identifierActions = array(
		"enable" => "setSetting",
		"disable" => "setSetting"
	);

	private function handleReq() {
		if(isset($_POST["action"], $_POST["identifier"])){
			switch($_POST["action"]){
				case "enable":
					if(isset($_POST["identifier"]))
						$this->site->database->setSetting($_POST["identifier"], "1");
				break;
					case "disable":
						if(isset($_POST["identifier"]))
							$this->site->database->setSetting($_POST["identifier"], '0');
				break;
				default:
					echo "ERROR";
				break;
			}
		}

		$this->settings = $this->site->database->getSettings();
		return;
	}

	private function buildSettingsTable() {
		echo '<table class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" style="width: 100%;" id="dataTable">';
		echo '<thead><tr>';
		echo "<th class='sorting-asc'>Setting Name</th><th>Description</th><th>Actual Value</th><th>Action</th";
		echo '</tr></thead>';
		foreach($this->settings as $row){
			echo "<tr>";
			echo "<td>";
			echo $row[0];
			echo "</td>";
			echo "<td>";
			echo $row[2];
			echo "</td>";
			echo "<td>";
			if($row[1] == 0)
			echo "Disabled";
			if($row[1] == 1)
			echo "Enabled";
			echo "</td>";
			echo "<td>";
			if($row[1] == 0)
			echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='enable'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input type='submit' class='btn btn-primary' value='Enable'></form>";
			if($row[1] == 1)
			echo "<form action='' method='post'><input type='hidden' name='action' id='action' value='disable'><input type='hidden' name='identifier' id='identifier' value='{$row[0]}'><input type='submit' class='btn btn-primary' value='Disable'></form>";
			echo "</td>";
			echo "<td>";

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
					<li class="breadcrumb-item active">
						<?php echo $this->site->config["main"]["name"]; ?> <?php echo $this->meta_title; ?>
					</li>
				</ol>
				<div class="card mb-3">
					<div class="card-header">
						<i class="fa fa-table"></i>
						<?php echo $this->meta_title; ?>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<?php $this->buildSettingsTable(); ?>
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
