	<div style="font-size:18px; font-weight:bold;margin-bottom: 10px;">Auto Permutation List</div>
<?
	$no_sentences = $_POST['no_sentences'];
	$sentences=array();
	for ($x = 0; $x < $no_sentences; $x++) {
		$sentences[$x] = $_POST['sentence'.$x];
	}
	if ($_POST['use_first']) { 
		$first = $sentences[0];
		$sentences = array_slice($sentences,1);
	}
	if ($_POST['limit']) {
		$limit = $_POST['limit'];
		if ($first) $limit--;
		$num = $limit;
		foreach($sentences as $key => $sentence) {
			if ($key >= $limit) unset($sentences[$key]);	
		}
	}
	
	permutate($sentences, 10, 0);
		
	function permutate($items, $limit, $count, $perms = array( )) {
		$count = $count++;
		echo $count;
		if ($count == $limit) exit();
		else if (empty($items)) {
			configure_perm ($perms);
		}
		else { 
			for ($i = count($items) - 1; $i >= 0; --$i) { 
				$newitems = $items;
				$newperms = $perms;
				list($foo) = array_splice($newitems, $i, 1);
				array_unshift($newperms, $foo);
				$newperms = $newperms;
				permutate($newitems, $limit, $count, $newperms); 
			} 
		} 
	}
	
	function configure_perm($perms=array( )) {
		echo '<div class="has-floats" style="margin-bottom:15px;">';
		echo '<div style="float:left; margin-right:10px;"><input type="checkbox" vesion="'.$x.' class="perm_box" /></div>';
		echo '<div style="float:left;">';
		foreach ($perms as $perm) {			
			echo $perm.'<br>';
		}
		echo "</div></div>";
	}
?>