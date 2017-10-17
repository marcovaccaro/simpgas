<?php
//$_SESSION["segmento"] = $segmento = isset($_GET['segmento']) ? $_GET['segmento'] : 'empty';
//var_dump($_SESSION["segmento"]);
	$rows = get_field('offerta');
//var_dump($rows);
if($rows) {
	$conta=1;
		foreach($rows as $row){
			$conta=$conta+1;
			//if ($row['segmento'] == $segmento)
			//{
				//VISUALIZZO IL MENU STIKY NECTAR
				echo '
						<style>
						#stickyself {
						display:block!important;
						visibility:visible!important
						}
						</style>
						<div id="offerta_0'.$conta.'" data-midnight="dark" data-bg-mobile-hidden="" class="wpb_row vc_row-fluid vc_row full-width-section standard_section   " style="padding: 33.26px 209px; margin-left: -209px; visibility: visible;" data-top-percent="2%" data-bottom-percent="2%"><div class="row-bg-wrap instance-3"><div class="inner-wrap"> <div class="row-bg  using-bg-color  " style="background-color: #fafafa; " data-color_overlay="" data-color_overlay_2="" data-gradient_direction="" data-overlay_strength="0.3" data-enable_gradient="false"></div></div> </div><div class="col span_12 dark left">
							<div class="vc_col-sm-12 wpb_column column_container vc_column_container col boxed no-extra-padding instance-4" data-shadow="none" data-border-animation="" data-border-animation-delay="" data-border-width="none" data-border-style="solid" data-border-color="" data-bg-cover="" data-padding-pos="all" data-has-bg-color="false" data-bg-color="" data-bg-opacity="1" data-hover-bg="" data-hover-bg-opacity="1" data-animation="" data-delay="0">
								<div class="vc_column-inner">
									<div class="wpb_wrapper">

							<div id="fws_5925ac6e299fa'.$conta.'" data-midnight="" data-column-margin="default" data-bg-mobile-hidden="" class="wpb_row vc_row-fluid vc_row standard_section  vc_custom_1493676184325   " style="padding-top: 0px; padding-bottom: 0px; "><div class="row-bg-wrap"> <div class="row-bg   " style=""></div> </div><div class="col span_12  right">
							<div class="vc_col-sm-12 wpb_column column_container vc_column_container col no-extra-padding instance-5" data-border-animation="" data-border-animation-delay="" data-border-width="none" data-border-style="solid" data-border-color="" data-bg-cover="" data-padding-pos="all" data-has-bg-color="false" data-bg-color="" data-bg-opacity="1" data-hover-bg="" data-hover-bg-opacity="1" data-animation="" data-delay="0">
								<div class="vc_column-inner">
								<div class="wpb_wrapper">

							<div class="wpb_text_column wpb_content_element  vc_custom_1493215801250">
								<div class="wpb_wrapper">
									<div style="float: left; margin-bottom: 0;">
						';
				//STAMPO LE OFFERTE
				//echo $row['segmento'].'<br>';

				if ($row['tipologia'] == 'lucegas') {
					echo '
					<h2 style="color: white; margin-bottom: 0;">LUCE e GAS: <i>'.$row['nome_offerta'].'</i></h2>
					</div>
					<div style="float: right; padding-top: 9px; margin-bottom: 0;">
							<i class="icon-default-style linecon-icon-bulb extra-color-gradient-1"></i>
							<span style="color: #ffffff;">+</span>
							<i class="icon-default-style linecon-icon-fire extra-color-gradient-1"></i>
					</div>
					<div style="clear: both;"></div>
					';
				}
				else if ($row['tipologia'] == 'gas') {
					echo '
					<h2 style="color: white; margin-bottom: 0;">GAS: <i>'.$row['nome_offerta'].'</i></h2>
					</div>
					<div style="float: right; padding-top: 9px; margin-bottom: 0;">
							<i class="icon-default-style linecon-icon-fire extra-color-gradient-1"></i>
					</div>
					<div style="clear: both;"></div>
					';
				}
				else if ($row['tipologia'] == 'luce') {
					echo '
					<h2 style="color: white; margin-bottom: 0;">LUCE: <i>'.$row['nome_offerta'].'</i></h2>
					</div>
					<div style="float: right; padding-top: 9px; margin-bottom: 0;">
							<i class="icon-default-style linecon-icon-bulb extra-color-gradient-1"></i>
					</div>
					<div style="clear: both;"></div>
					';			}

				$pale_img_url = isset($row['image']['url']) ? $row['image']['url'] : 'http://placehold.it/200x200';

				echo '
				</div>
				</div>

				</div>
				</div>
				</div>
				</div></div>
				<div class="wpb_content_element  pale_tabs_nav" data-interval="0">
				<div class="wpb_wrapper tabbed clearfix" data-style="minimal" data-alignment="left">
				<ul class="wpb_tabs_nav ui-tabs-nav clearfix"><li><a href="#tab-1446568150305-73431-3a3d" class="active-tab">Presentazione</a></li><li><a href="#tab-1451522261332-5" class="">Vantaggi</a></li><li><a href="#tab-1492964447370-2-3" class="">Dettagli</a></li><li class="cta-button"><a class="nectar-button medium regular-button accent-color" data-color-override="false" href="#" style="visibility: visible;">Attiva l\'offerta</a></li></ul>


				<div id="tab-presentazione" class="wpb_tab ui-tabs-panel wpb_ui-tabs-hide clearfix" style="visibility: visible; position: relative; opacity: 1; left: 0px; display: block;">

				<div id="fws_5925ac6e2a61b'.$conta.'" data-midnight="" data-column-margin="default" data-bg-mobile-hidden="" class="wpb_row vc_row-fluid vc_row  vc_row-o-equal-height vc_row-flex  vc_row-o-content-top standard_section    " style="padding-top: 0px; padding-bottom: 0px; "><div class="row-bg-wrap"> <div class="row-bg   " style=""></div> </div><div class="col span_12  left">
				<div class="vc_col-sm-3 wpb_column column_container vc_column_container col padding-1-percent instance-6 no-left-margin" data-border-animation="" data-border-animation-delay="" data-border-width="none" data-border-style="solid" data-border-color="" data-bg-cover="" data-padding-pos="all" data-has-bg-color="false" data-bg-color="" data-bg-opacity="1" data-hover-bg="" data-hover-bg-opacity="1" data-animation="" data-delay="0" style="padding-top: 12.1406px; padding-bottom: 12.1406px;">
				<div class="vc_column-inner">
				<div class="wpb_wrapper">
				<div class="img-with-aniamtion-wrap center" data-max-width="100%"><div class="inner"><img data-shadow="none" data-shadow-direction="middle" class="img-with-animation  animated-in" data-delay="0" height="453" width="522" data-animation="grow-in" src="'.$pale_img_url.'" srcset="'.$pale_img_url.' 522w, '.$pale_img_url.' 300w" alt="" style="transform: scale(1, 1); opacity: 1;"></div></div>
				</div>
				</div>
				</div>

				<div class="vc_col-sm-6 wpb_column column_container vc_column_container col no-extra-padding instance-7" data-border-animation="" data-border-animation-delay="" data-border-width="none" data-border-style="solid" data-border-color="" data-bg-cover="" data-padding-pos="all" data-has-bg-color="false" data-bg-color="" data-bg-opacity="1" data-hover-bg="" data-hover-bg-opacity="1" data-animation="" data-delay="0">
				<div class="vc_column-inner">
				<div class="wpb_wrapper">

				<div class="wpb_text_column wpb_content_element  vc_custom_1493043413987">
				<div class="wpb_wrapper">
				<h6><span style="color: #c62727;">'.$row['sottotitolo'].'</span></h6>
				<h2>'.$row['titolo'].'</h2>
				<p>'.$row['descrizione_breve'].'</p>

				</div>
				</div>

				</div>
				</div>
				</div>

				<div style="border: 1px solid rgba(255, 255, 255, 0); padding-top: 12.1406px; padding-bottom: 12.1406px; opacity: 1; transform: translate(0px, 0px);" class="vc_col-sm-3 wpb_column column_container vc_column_container col has-animation padding-1-percent instance-8 animated-in" data-border-animation="true" data-border-animation-delay="" data-border-width="1px" data-border-style="solid" data-border-color="#e1e1e1" data-bg-cover="" data-padding-pos="all" data-has-bg-color="false" data-bg-color="" data-bg-opacity="1" data-hover-bg="" data-hover-bg-opacity="1" data-animation="fade-in-from-right" data-delay="0"><span class="border-wrap animation completed" style="border-color: #e1e1e1;"><span class="border-top"></span><span class="border-right"></span><span class="border-bottom"></span><span class="border-left"></span></span>
				<div class="vc_column-inner">
				<div class="wpb_wrapper">
				<div class="iwithtext"><div class="iwt-icon"> <i class="icon-default-style linecon-icon-fire extra-color-3"></i> </div><div class="iwt-text"> CORRISPETTIVO LUCE<br>
				<span style="font-size: smaller;">FASCIA F1</span><br>
				0,053 €/kWh<br>
				<span style="font-size: smaller;">FASCIA F2</span><br>
				0,041 €/kWh </div><div class="clear"></div></div><div class="divider-wrap"><div style="margin-top: 10px; height: 1px; margin-bottom: 10px; transform: scale(1, 1); visibility: visible;" data-width="100%" data-animate="yes" data-animation-delay="" data-color="extra-color-1" class="divider-border completed"></div></div><div class="iwithtext"><div class="iwt-icon"> <i class="icon-default-style linecon-icon-fire extra-color-2"></i> </div><div class="iwt-text"> CORRISPETTIVO GAS<br>
				0,215 €/Smc </div><div class="clear"></div></div>
				</div>
				</div>
				</div>
				</div></div>
				</div>
				<div id="tab-vantaggi" class="wpb_tab ui-tabs-panel wpb_ui-tabs-hide clearfix" style="visibility: hidden; position: absolute; opacity: 0; left: -9999px; display: none;"></div>
				<div id="tab-dettagli" class="wpb_tab ui-tabs-panel wpb_ui-tabs-hide clearfix" style="visibility: hidden; position: absolute; opacity: 0; left: -9999px; display: none;"></div>
				</div>
				</div>
				</div>
				</div>
				</div>
				</div></div>
				';

			}
		}
?>
