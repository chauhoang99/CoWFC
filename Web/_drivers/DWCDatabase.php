<?php
include($_SERVER["DOCUMENT_ROOT"] . '/_drivers/Database.php');

class DWCDatabase extends Database {

	public function ban(string $type, array $target_aliases, string $identifier, string $reason="none", int $time=0) {
		$this->{"ban{$type}"}($identifier, $reason, $time);
		$identifier = !$target_aliases ? $identifier : implode(" / ", $target_aliases);
		$format = "[%s] %s - %s banned %s %s (Reason: %s)\n";
		$time = $time == 0 ? "forever" : "until " . date("m/d/Y H:i:s", time()+$time);
		$logmsg = sprintf($format, date("m/d/Y H:i:s", time()), strtoupper($type), $_SESSION["username"], $identifier, $time, empty($reason) ? "None" : $reason);

		file_put_contents($this->site->config["admin"]["banlog_path"], $logmsg, FILE_APPEND);
	}

	public function getWhitelist(): array {
		$sql = "SELECT * from allowed_games";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getSetting(string $setting_name): int {
		$sql = "SELECT setting_value FROM settings WHERE setting_name = :setting_name";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindValue(':setting_name', $setting_name);
		$stmt->execute();
		return $stmt->fetch()[0];
	}

	public function getSettings(): array {
		$sql = "SELECT * from settings";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function setSetting(string $setting_name, string $setting_value) {
		$sql = "UPDATE settings SET setting_value= :setting_value WHERE setting_name = :setting_name";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindValue(':setting_value', $setting_value);
		$stmt->bindValue(':setting_name', $setting_name);
		$stmt->execute();
		return;
	}

	public function addToWhitelist(string $game) {
		$sql = "INSERT INTO allowed_games (gamecd) VALUES (:gamecd)";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindValue(':gamecd', strtoupper($game));
		$stmt->execute();
		return;
	}

	public function removeFromWhitelist(string $game) {
		$sql = "DELETE FROM allowed_games WHERE gamecd = :gamecd";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':gamecd', $game);
		$stmt->execute();
		return;
	}

	private function banIP(string $ip, string $reason='none', int $time=0) {
		$ubtime = time() + $time;
		if($time == 0) $ubtime = 99999999999;
		$sql = "INSERT INTO banned (banned_id, timestamp, reason, ubtime, type) VALUES (:ipaddr, :timestamp, :reason, :ubtime, 'ip')";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':ipaddr', $ip);
		$stmt->bindValue(':timestamp', time());
		$stmt->bindParam(':reason', $reason);
		$stmt->bindParam(':ubtime', $ubtime);
		$stmt->execute();
		return;
	}

	private function banProfile(string $profile, string $reason='none', int $time=0) {
		$ubtime = time() + $time;
		if($time == 0) $ubtime = 99999999999;
		$sql = "INSERT INTO banned (banned_id, timestamp, reason, ubtime, type) VALUES (:gsbrcd, :timestamp, :reason, :ubtime, 'profile')";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':gsbrcd', $profile);
		$stmt->bindValue(':timestamp', time());
		$stmt->bindParam(':reason', $reason);
		$stmt->bindParam(':ubtime', $ubtime);
		$stmt->execute();
		return;
	}

	public function unbanProfile(string $gsbrcd) {
		$sql = "DELETE FROM banned WHERE banned_id = :gsbrcd";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':gsbrcd', $gsbrcd);
		$stmt->execute();
		return;
	}

	private function banAP(string $ap, string $reason='none', int $time=0) {
		$ubtime = time() + $time;
		if($time == 0) $ubtime = 99999999999;
		$sql = "INSERT INTO banned (banned_id, timestamp, reason, ubtime, type) VALUES (:bssid, :timestamp, :reason, :ubtime, 'ap')";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':bssid', $ap);
		$stmt->bindValue(':timestamp', time());
		$stmt->bindParam(':reason', $reason);
		$stmt->bindParam(':ubtime', $ubtime);
		$stmt->execute();
		return;
	}

	public function isBanned(string $type, string $banned_id): int {
		$sql = "SELECT COUNT(*) FROM banned WHERE type = :type AND banned_id = :banned_id";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':type', $type);
		$stmt->bindParam(':banned_id', $banned_id);
		$stmt->execute();
		return $stmt->fetch()[0];
	}


	public function unbanAP(string $ap) {
		$sql = "DELETE FROM banned WHERE banned_id = :bssid";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':bssid', $ap);
		$stmt->execute();
		return;
	}

	public function unbanIP(string $console) {
		$sql = "DELETE FROM banned WHERE type = 'ip' AND banned_id = :ipaddr";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':ipaddr', $console);
		$stmt->execute();
		return;
	}

	public function getBannedAPs(): array {
		$sql = "SELECT banned_id, timestamp, reason, ubtime FROM banned WHERE type = 'ap' AND ubtime > ".time();
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getIPBans(): array {
		$sql = "SELECT banned_id, timestamp, reason, ubtime FROM banned WHERE type = 'ip' AND ubtime > ".time();
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getRegisteredConsoles(): array {
		$sql = "SELECT * FROM consoles WHERE enabled = '1'";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getAbusedConsoles(): array {
		$sql = "SELECT (macadr) FROM consoles WHERE abuse = '1'";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	private function banConsole(string $console, string $reason='none', int $time=0) {
		$ubtime = time() + $time;
		if($time == 0) $ubtime = 99999999999;
		$sql = "INSERT INTO banned (banned_id, timestamp, reason, ubtime, type) VALUES (:macadr, :timestamp, :reason, :ubtime, 'console')";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':macadr', $console);
		$stmt->bindValue(':timestamp', time());
		$stmt->bindParam(':reason', $reason);
		$stmt->bindParam(':ubtime', $ubtime);
		$stmt->execute();
	}

	public function unbanConsole(string $console) {
		$sql = "DELETE FROM banned WHERE type = 'console' AND banned_id = :macadr";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':macadr', $console);
		$stmt->execute();
		return;
	}

	public function unregisterConsole(string $console) {
		$sql = "DELETE FROM consoles WHERE macadr = :macadr";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':macadr', $console);
		$stmt->execute();
		return;
	}

	public function registerConsole(string $console) {
		$sql = "UPDATE consoles SET enabled='1' WHERE macadr = :macadr";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->bindParam(':macadr', $console);
		$stmt->execute();
		return;
	}

	public function getPendingConsoles(): array {
		$sql = "SELECT (macadr) FROM consoles where enabled = '0'";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getBannedConsoles(): array {
		$sql = "SELECT banned_id, timestamp, reason, ubtime FROM banned WHERE type = 'console' AND ubtime > ".time();
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getBannedProfiles(): array {
		$sql = "SELECT banned_id, timestamp, reason, ubtime FROM banned WHERE type = 'profile' AND ubtime > ".time();
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getUsers(): array {
		$sql = "SELECT users.profileid,enabled,data,users.gameid,console,users.userid
				FROM nas_logins
				INNER JOIN users
				ON users.userid = nas_logins.userid
				INNER JOIN (
					SELECT max(profileid) newestpid,userid,gameid,devname
					FROM users GROUP BY userid,gameid)
				ij on ij.userid = users.userid and
				users.profileid = ij.newestpid
				ORDER BY users.gameid";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function getBannedList(): array {
		$sql = "SELECT banned_id, timestamp, reason, ubtime FROM banned WHERE type = 'ip' AND ubtime > ".time();
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		$banned = array();
		foreach($stmt->fetchAll() as $row){
			$banned[] = $row[0];
		}
		return $banned;
	}

	public function getNumBannedMisc(): int {
		$sql = "SELECT COUNT(*) FROM banned WHERE type = 'ip' AND ubtime > ".time();
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetch()[0];
	}

	public function getNumBannedProfiles(): int {
		$sql = "SELECT COUNT(*) FROM banned WHERE type = 'profile' AND ubtime > ".time();
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetch()[0];
	}

	public function getNumBannedConsoles(): int {
		$sql = "SELECT COUNT(*) FROM banned WHERE type = 'console' AND ubtime > ".time();
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetch()[0];
	}

	public function getActiveGames(): int {
		$sql = "SELECT COUNT(*) FROM allowed_games";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetch()[0];
	}

	public function getConsoles(): int {
		$sql = "SELECT COUNT(*) FROM consoles WHERE enabled = '1'";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetch()[0];
	}

	public function getProfiles(): int {
		$sql = "SELECT COUNT(*) FROM users";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetch()[0];
	}

	public function getNumOfAllBans(): int {
		$sql = "SELECT COUNT(*) FROM banned";
		$stmt = $this->getConn()->prepare($sql);
		$stmt->execute();
		return $stmt->fetch()[0];
	}

}
