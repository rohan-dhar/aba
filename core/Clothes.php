<?php 

	class Clothes{
		public function __construct($py = false){
			if($py){
				putenv("PATH=/usr/local/bin/:" . exec('echo $PATH'));
			}
		}

		public function getAll($noWash = false, $getAllInfo = false){
			$ret = [];
			if($noWash){
				$qry = $GLOBALS['db']->prepare("SELECT * FROM clothes WHERE washing = 0");
			}else{
				$qry = $GLOBALS['db']->prepare("SELECT * FROM clothes");
			}
			$qry->execute();
			$res = $qry->fetchAll(PDO::FETCH_ASSOC);

			if($getAllInfo == false){			
				foreach($res as $v){
					$c = $v['type'] . '-' . $v['color'] . '-' . $v['washing'];
					array_push($ret, $c);
				}			
			}else{
				foreach($res as $v){
					$c = $v['type'] . '-' . $v['color'] . '-' . $v['washing']. '-' . $v['id']. '-' . $v['slot'];
					array_push($ret, $c);
				}							
			}
			return $ret;
		}

		public function getNumber($washing = false){
			if($washing){
				$qry = $GLOBALS['db']->prepare("SELECT COUNT(*) FROM clothes WHERE washing = 0");	
			}else{
				$qry = $GLOBALS['db']->prepare("SELECT COUNT(*) FROM clothes");	
			}	
			$qry->execute();
			$cnt = (int)$qry->fetch(PDO::FETCH_ASSOC)['COUNT(*)'];
			
			return $cnt;				
		}

		public function add($cloth, $color){

			if($this->getNumber() >= MAX_CLOTHES){
				return([false, "You can not add more than ".MAX_CLOTHES.' clothes', 'MAX_CLOTHES']);
			}

			if(!is_numeric($cloth) || !is_numeric($color)){
				return ([false, "Invalid color or cloth selected", "INVALID_ARG"]);
			}

			$qry = $GLOBALS['db']->prepare("INSERT INTO clothes (type, color, slot, washing) VALUES (:type, :color, NULL, 0)");
			$qry->execute([
				':type' => $cloth,
				':color' => $color
			]);

			$clothId = (int)$GLOBALS['db']->lastInsertId();

			$slot = shell_exec('sudo python3 '.ROOT."/py/addCloth.py ".$clothId." 2>&1");

			if(!is_numeric($slot)){
				$qry = $GLOBALS['db']->prepare("DELETE FROM clothes WHERE id = :id LIMIT 1");
				$qry->execute([
					':id' => $clothId
				]);
				return([false, "An unknown error occured (SYS_EXEC)", "SYS_EXEC", $slot]);
			}

			if($slot < 0){
				if($slot == -1){
					$qry = $GLOBALS['db']->prepare("DELETE FROM clothes WHERE id = :id LIMIT 1");
					$qry->execute([
						':id' => $clothId
					]);
					return([false, "An unknown error occured\n(SYS_ARG)", "SYS_ARG"]);
				}else if($slot == -2){
					return([false, "The RFID tag was not detected.\nTry to add the cloth again. ", "RFIF_ERR"]);
				}else{
					return([false, "The slot was not detected.\nTry to add the cloth again. ", "SLOT_ERR"]);
				}
			}

			return([true]);
		}

		public function manualRemove($type, $color){
			$qry = $GLOBALS['db']->prepare("SELECT COUNT(*) FROM clothes WHERE type = :type AND color = :color");
			$qry->execute([':type' => $type, ':color' => $color]);
			$initialCnt = (int)$qry->fetch(PDO::FETCH_ASSOC)['COUNT(*)'];
			if($initialCnt == 0){
				return [false, "Invalid cloth being removed"];
			}

			$qry = $GLOBALS['db']->prepare("DELETE FROM clothes WHERE type = :type AND color = :color AND washing = 1 LIMIT 1");
			$qry->execute([':type' => $type, ':color' => $color]);

			$qry = $GLOBALS['db']->prepare("SELECT COUNT(*) FROM clothes WHERE type = :type AND color = :color");
			$qry->execute([':type' => $type, ':color' => $color]);
			$newCnt = (int)$qry->fetch(PDO::FETCH_ASSOC)['COUNT(*)'];

			if($newCnt == $initialCnt){
				$qry = $GLOBALS['db']->prepare("DELETE FROM clothes WHERE type = :type AND color = :color LIMIT 1");
				$qry->execute([':type' => $type, ':color' => $color]);				
			}
			
			return [true, $this->getAll()];
		}

		private function checkCombo($cloth, $clothLessInfo, $combos){
			foreach ($cloth as $k => $v){
				$c = explode('-', $v);
				foreach ($combos as $combo){
					if($combo[0][0] == (int)$c[0] and $combo[0][1] == (int)$c[1] and in_array($combo[1][0].'-'.$combo[1][1].'-0' , $clothLessInfo)){
						return [true, $v, $cloth[array_search($combo[1][0].'-'.$combo[1][1].'-0' , $clothLessInfo)], false];
					}else if($combo[1][0] == (int)$c[0] and $combo[1][1] == (int)$c[1] and in_array($combo[0][0].'-'.$combo[0][1].'-0' , $clothLessInfo)){
						return [true, $v, $cloth[array_search($combo[0][0].'-'.$combo[0][1].'-0' , $clothLessInfo)], false];
					}
				}
			}
			return [false];
		}

		private function checkAnyCombo($cloth){
			foreach ($cloth as $v){
				$c = explode('-', $v);
				if((int)$c[0] == 0 or (int)$c[0] == 2){
					foreach ($cloth as $v2){
						$c2 = explode('-', $v2);
						if((int)$c2[0] == 1 or (int)$c2[0] == 3 or (int)$c2[0] == 4){
							return [true, $v, $v2, true];
						}
					} 
				}
			}
			return [false];
		}

		public function genPair(){
			$combos = $GLOBALS['combosWeek'];
			$combosAlter = $GLOBALS['combosWeekend'];
			if(date('N', time()) >= 6){
				$combos = $GLOBALS['combosWeekend'];
				$combosAlter = $GLOBALS['combosWeek'];
			}

			$cloth = $this->getAll(true, true);
			$clothLessInfo = $this->getAll(true);
			if(count($cloth) < 2){
				return [false, 'No pair can be generated using the given clothes. You need to add more clothes or return clothes from washing', 'NO_PAIR_LESS'];
			}
			
			$c1 = $this->checkCombo($cloth, $clothLessInfo, $combos);
			$c2 = $this->checkCombo($cloth, $clothLessInfo, $combosAlter);
			$c3 = $this->checkAnyCombo($cloth);
			if($c1[0]){
				return $c1;
			}else if($c2[0]){
				return $c2;
			}else if($c3[0]){
				return $c3;
			}else{
				return [false, 'No pair can be generated using the given clothes. You need to add more clothes or return clothes from washing', 'NO_PAIR'];
			}
			
		}

		public function removePair(){		
			$pair = $this->genPair();
			if($pair[0] == false){
				return $pair;
			}			
			$c1 = explode('-', $pair[1]);
			$c2 = explode('-', $pair[2]);
			// ID1 SLOT1 ID2 SLOT2
			$arg = (int)$c1[3] . ' ' . (int)$c1[4] . ' ' . (int)$c2[3] . ' ' . (int)$c2[4];
			$status = shell_exec('sudo python3 '.ROOT."/py/removePair.py ".$arg." 2>&1");
			if(!is_numeric($status)){
				return([false, "An unknown error occured (SYS_EXEC)", "SYS_EXEC", $status]);
			}
			$status = (int)$status;
			if($status == 1){
				return [true];
			}else{
				return [false, 'You didn\'t remove all the clothes.', 'sudo python3 '.ROOT."/py/removePair.py ".$arg." 2>&1"];
			}
		}
		public function updateWash(){
			$n1 = $this->getNumber(true);
			$n2 = $this->getNumber();
			if($n1 == $n2){
				return([false, "No clothes are out for washing", "FULL"]);
			}

			$stat = shell_exec('sudo python3 '.ROOT."/py/updateWash.py 2>&1");

			if(!is_numeric($stat)){
				return([false, "An unknown error occured (SYS_EXEC)", "SYS_EXEC", $stat]);
			}

			$stat = (int)$stat;

			if($stat != 1){
				return([false, "You did not add any clothes!", "TIMEOUT"]);
			}else{
				return([true]);
			}
		}

		public function outForWash(){
			$n1 = $this->getNumber(true);
			
			if($n1 == 0){
				return([false, "No clothes are left to remove", "EMPTY"]);
			}

			$stat = shell_exec('sudo python3 '.ROOT."/py/outForWash.py 2>&1");

			if(!is_numeric($stat)){
				return([false, "An unknown error occured (SYS_EXEC)", "SYS_EXEC", $stat]);
			}

			$stat = (int)$stat;

			if($stat != 1){
				return([false, "You did not remove any clothes!", "TIMEOUT"]);
			}else{
				return([true]);
			}
		}


	}
?>
