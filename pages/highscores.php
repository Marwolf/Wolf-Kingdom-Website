<?php
include_once './inc/common.php';

$skill_array = array(skill_total, attack, strength, defense, hits, ranged, prayer, magic, cooking, woodcut, fletching, fishing, firemaking, crafting, smithing, mining, herblaw, agility, thieving);


function totalXP($skills) {
	$skill_total = 0;
	foreach ($skills as $key => $value) {
		if (substr($key, 0, 4) == "exp_") {
			$skill_total += $value;
		}
	}
	return $skill_total;
}

$connector = new DarscapeDbc();

if ($_GET['skill']) {
                $subpage = $_GET['skill'];
                $subpage = mysqli_real_escape_string($con, $subpage);
		$subpage = preg_replace("/[^A-Za-z0-9 ]/","_",$subpage);
		
                if($subpage == $skill_array[0]){
			$query = array('wk_players.'.$subpage.', wk_experience.*','wk_players.'.$subpage);
		} else {
			$query = array('wk_experience.exp_'.$subpage,'exp_'.$subpage);
		}
		
                $args = $query[0];
		$order = $query[1];
		$stat_result = $connector->query("SELECT wk_players.username,$args FROM wk_experience LEFT JOIN wk_players ON wk_experience.user = wk_players.user WHERE wk_players.banned != '1' AND wk_players.group_id != '1' ORDER BY $order DESC LIMIT 30");
?>
	<div class="main">
		<div class="content">
			<article>
				<h4><?php print preg_replace("/[^A-Za-z0-9 ]/"," ",$subpage);?> Highscores</h4>
				<p>View the current leaders for <?php print preg_replace("/[^A-Za-z0-9 ]/"," ",$subpage);?>.</p>
				<div class="ranking">
					<ul>
						<li id="header">
							<div id="rank">Rank</div>
							<div id="username">Username</div>
							<div id="level">Level</div>
							<div id="experience">Experience</div>
						</li>
					</ul>
					<ul>
					<?php
						$i = 1;
						while($row = $connector->fetchArray($stat_result)) {
							$usernamelink = preg_replace("/[^A-Za-z0-9]/","-",$row['username']);
					?>
						<li id="table">
							<div id="rank"><?php echo $i; ?></div>
							<div id="username"><a href="/<?php echo $script_directory; ?>highscores.php/characters/<?php echo $usernamelink; ?>"><?php echo $row['username']; ?></a></div>
							<div id="level"><?php echo ($subpage == $skill_array[0]) ? $row['skill_total'] : experienceToLevel($row['exp_'.$subpage]); ?></div>
							<div id="experience"><?php echo ($subpage == $skill_array[0]) ? totalXP($row) : $row['exp_'.$subpage]; ?></div>
						</li>
					<?php
						$i++;
						}
					?>
					</ul>
				</div>
			</article>
		</div>
	</div>
<?php
} else {
?>
	<div class="main">
		<div class="content">
			<article>
				<h4>Highscores</h4>
				<p>Select one of the skills below to view the leaders in each.</p>
				<div class="skill">
					<ul>
						<?php foreach ($skill_array as $skill) { ?>
                                                        <li><a href="<?php echo $script_directory; ?>highscores.php?skill=<?php print $skill;?>"><img src="<?php echo $script_directory; ?>css/images/skill_icons/<?php print $skill;?>-lg.png" alt="<?php print $skill;?>"/><?php print ucwords(preg_replace("/[^A-Za-z0-9 ]/"," ",$skill));?></a></li>
						<?php } ?>
					</ul>
				</div>
			</article>
		</div>
	</div>
<?php
}
?>