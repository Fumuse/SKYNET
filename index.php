<?
define('P_ROOT', $_SERVER['DOCUMENT_ROOT']);
require_once(P_ROOT.'/core/header.php');
require_once(P_ROOT.'/core/functions.php'); //for translit
require_once(P_ROOT.'/lang/ru.php');

/* tarifs list */
$dataJSON = json_decode(file_get_contents('https://www.sknt.ru/job/frontend/data.json'), TRUE);

/* empty page if we have no data */
if ($dataJSON['result'] == 'error' || empty($dataJSON)):
	require_once(P_ROOT.'/core/404.php');
else:
	?>
	<!-- first screen -->
	<div class="container tarifs-container first-screen active" data-screen="1">
	<?	if (!empty($dataJSON['tarifs'])):
			foreach ($dataJSON['tarifs'] as $key => $data):
				$maxSpeed = 0;
				$price = array(
					"min"	=>	0,
					"max"	=>	0
				);
				$dataJSON['tarifs'][$key]['startPrice'] = 0;
				foreach ($data['tarifs'] as $innerKey => $innerData):
					if ($maxSpeed == 0 || $maxSpeed < $innerData['speed']) //define max speed
						$maxSpeed = $innerData['speed'];	
					
					if ($dataJSON['tarifs'][$key]['startPrice'] == 0 && $innerData['pay_period'] == 1) //for discounts
						$dataJSON['tarifs'][$key]['startPrice'] = $innerData['price'];
					$dataJSON['tarifs'][$key]['tarifs'][$innerKey]['monthPrice'] = $monthPrice = round($innerData['price'] / $innerData['pay_period']);
					if ($price['min'] == 0 || $price['min'] > $monthPrice) //define min tarif price
						$price['min'] = $monthPrice;
					if ($price['max'] == 0 || $price['max'] < $monthPrice) //define max tarif price
						$price['max'] = $monthPrice;
					//
				endforeach;
				$classSpeed = str2url($data['title']); //style class for speed block, because json-data have no information about general tarif ID
				?>
				<div class="tarif-block">
					<div class="tarif-block--name">
						<?=sprintf($tarifsLang["Tarif Title"], $data['title'])?>
					</div>
					<div class="tarif-block--detail">
						<div class="tarif-block--inner">
							<div class="tarif-block--speed color--<?=$classSpeed?>">
								<?=$maxSpeed?> <?=$tarifsLang["MS"]?>
							</div>
							<div class="tarif-block--price">
								<?=$price['min']?> — <?=$price['max']?> <?=$tarifsLang["Month Price"]?>
							</div>
							<? if (!empty($data['free_options'])): ?>
							<div class="tarif-block--options">
								<ul>
								<? foreach ($data['free_options'] as $option): ?>
									<li><?=$option?></li>
								<? endforeach; ?>
								</ul>
							</div>
							<? endif; ?>
						</div>
						<div class="tarif-block--inner-link" data-screen-open="2" data-tarif="k<?=$key?>">
							<div class="arrow"></div>
						</div>
					</div>
					<div class="tarif-block--about">
						<a href="<?=$data['link']?>" target="_blank"><?=$tarifsLang["More details"]?></a>
					</div>
				</div>
				<?
			endforeach;
		endif;?>
	</div>
	<!-- second screen -->
	<div class="container tarifs-container second-screen" data-screen="2">
		<?	if (!empty($dataJSON['tarifs'])):
				foreach ($dataJSON['tarifs'] as $key => $data):
					?><div class="inner-screen" data-tarif="k<?=$key?>">
						<div class="tarif-header return-header">
							<div class="tarif-block--return-link" data-screen-open="1"></div>
							<div class="tarif-block--return-text">
								<?=sprintf($tarifsLang["Tarif Title"], $data['title'])?>
							</div>
						</div>
					<?	
					usort($data['tarifs'], function($a, $b) { //sort array for monthes
						return ($a['pay_period'] - $b['pay_period']);
					});
					foreach ($data['tarifs'] as $innerKey => $innerData):
							if ($innerData['price'] != $data['startPrice'])
								$innerData['discount'] = abs($innerData['price'] - ($data['startPrice'] * $innerData['pay_period']));
					?>
							<div class="tarif-block">
								<div class="tarif-block--name">
									<?=$innerData['pay_period']?> <?=russianNumberOf($innerData['pay_period'], $tarifsLang["Month"], array("", $tarifsLang["Month > 1"], $tarifsLang["Month > 4"]))?>
								</div>
								<div class="tarif-block--detail">
									<div class="tarif-block--inner">
										<div class="tarif-block--price">
											<?=$innerData['monthPrice']?> <?=$tarifsLang["Month Price"]?>
										</div>
										<div class="tarif-block--options">
											<ul>
												<li><?=$tarifsLang["Pay"]?> — <?=$innerData['price']?> <?=$tarifsLang["RUR"]?></li>
												<? if (!empty($innerData['discount'])):?><li><?=$tarifsLang["Discount"]?> — <?=$innerData['discount']?> <?=$tarifsLang["RUR"]?></li><? endif; ?>
											</ul>
										</div>
									</div>
									<div class="tarif-block--inner-link" data-screen-open="3" data-tarif="k<?=$key?>" data-tarif-in="i<?=$innerKey?>">
										<div class="arrow"></div>
									</div>
								</div>
							</div>
						<? endforeach;
					?></div><?
				endforeach;
			endif;
		?>
	</div>
	<!-- third screen -->
	<div class="container tarifs-container third-screen" data-screen="3">
		<?	if (!empty($dataJSON['tarifs'])):
				foreach ($dataJSON['tarifs'] as $key => $data):?>	
					<div class="inner-screen" data-tarif="k<?=$key?>">
						<div class="tarif-header return-header">
							<div class="tarif-block--return-link" data-screen-open="2" data-tarif="k<?=$key?>"></div>
							<div class="tarif-block--return-text"><?=$tarifsLang['Tarif Title In']?></div>
						</div>
						<?	foreach ($data['tarifs'] as $innerKey => $innerData): ?>
							<div class="tarif-block tarif-block--third" data-tarif-in="i<?=$innerKey?>">
								<div class="tarif-block--name">
									<?=sprintf($tarifsLang["Tarif Title"], $data['title'])?>
								</div>
								<div class="tarif-block--detail">
									<div class="tarif-block--inner">
										<div class="tarif-block--price">
											<?=$tarifsLang["Period"]?> — <?=$innerData['pay_period']?> <?=russianNumberOf($innerData['pay_period'], $tarifsLang["Month"], array("", $tarifsLang["Month > 1"], $tarifsLang["Month > 4"]))?>
										</div>
										<div class="tarif-block--price min-padding-top">
											<?=$innerData['monthPrice']?> <?=$tarifsLang["Month Price"]?>
										</div>
										<div class="tarif-block--options">
											<ul>
												<li><?=$tarifsLang["Pay"]?> — <?=$innerData['price']?> <?=$tarifsLang["RUR"]?></li>
												<li><?=$tarifsLang["Account"]?> — <?=$innerData['price']?> <?=$tarifsLang["RUR"]?></li>
											</ul>
											<ul class="tarif-block--date">
												<li><?=$tarifsLang["Take effect"]?> — <?=$tarifsLang["Today"]?></li>
												<li><?=$tarifsLang["Active for"]?> — <?=date('d.m.Y', $innerData['new_payday'])?></li>
											</ul>
										</div>
									</div>
								</div>
								<div class="tarif-block--about">
									<a href="javascript:void(0)" class="button-green"><?=$tarifsLang["Select"]?></a>
								</div>
							</div>
						<?  endforeach;?>
					</div>
				<?endforeach;
			endif;
	?></div><?
endif;

require_once(P_ROOT.'/core/footer.php');