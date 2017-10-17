<div id="pdoc" data-midnight="dark" data-bg-mobile-hidden="" class="wpb_row vc_row-fluid vc_row standard_section   " style="padding-top: 0px; padding-bottom: 0px; ">
	<div class="row-bg-wrap instance-0">
		<div class="row-bg    " style="" data-color_overlay="" data-color_overlay_2="" data-gradient_direction="" data-overlay_strength="0.3" data-enable_gradient="false"></div>
	</div>
	<div class="col span_12 dark left">
		<div class="vc_col-sm-12 wpb_column column_container vc_column_container col no-extra-padding instance-0" data-bg-cover="" data-padding-pos="all" data-has-bg-color="false" data-bg-color="" data-bg-opacity="1" data-hover-bg="" data-hover-bg-opacity="1" data-animation="" data-delay="0">
			<div class="vc_column-inner">
				<div class="wpb_wrapper">

					<div class="toggles " data-style="minimal">

					<?php
						$rows = get_field($carica_modulistica);
						if($rows)

						{
							$conta = 0;
							foreach($rows as $row)
							{
								if ($row['assegna_categoria'] == 'Agevolazioni') { $conta ++; }
							}

							echo '
							<div class="toggle default"><h3><a href="#"><i class="icon-plus-sign"></i>Agevolazioni - <span>'.$conta.' documenti</span></a></h3>
														<div>
															<div class="wpb_text_column wpb_content_element ">
																<div class="wpb_wrapper">
																	<ul class="dlm-downloads">
							';
							foreach($rows as $row)
							{
								if ($row['assegna_categoria'] == 'Agevolazioni') {?>
								<li><a class="download-link" title="" href="<? echo $row['carica_file']['url']; ?>" target="_blank" rel="nofollow"><? echo $row['carica_file']['title']; ?></a></li>
							<?}
							}
							echo '
										</ul>
									</div>
								</div>
							</div>
						</div>
							';
						}

						$rows = get_field($carica_modulistica);
						/*asort($rows);*/
						if($rows)
						{
							$conta = 0;
							foreach($rows as $row)
							{
								if ($row['assegna_categoria'] == 'Contrattuali') { $conta ++; }
							}

							echo '
							<div class="toggle default"><h3><a href="#"><i class="icon-plus-sign"></i>Contrattuali - <span>'.$conta.' documenti</span></a></h3>
														<div>
															<div class="wpb_text_column wpb_content_element ">
																<div class="wpb_wrapper">
																	<ul class="dlm-downloads">
							';
							foreach($rows as $row)
							{
								if ($row['assegna_categoria'] == 'Contrattuali') {?>
								<li><a class="download-link" title="" href="<? echo $row['carica_file']['url']; ?>" target="_blank" rel="nofollow"><? echo $row['carica_file']['title']; ?></a></li>
							<?}
							}
							echo '
										</ul>
									</div>
								</div>
							</div>
						</div>
							';
						}

						$rows = get_field($carica_modulistica);
						/*asort($rows);*/
						if($rows)
						{
							$conta = 0;
							foreach($rows as $row)
							{
								if ($row['assegna_categoria'] == 'Pagamenti') { $conta ++; }
							}

							echo '
							<div class="toggle default"><h3><a href="#"><i class="icon-plus-sign"></i>Pagamenti - <span>'.$conta.' documenti</span></a></h3>
														<div>
															<div class="wpb_text_column wpb_content_element ">
																<div class="wpb_wrapper">
																	<ul class="dlm-downloads">
							';
							foreach($rows as $row)
							{
								if ($row['assegna_categoria'] == 'Pagamenti') {?>
								<li><a class="download-link" title="" href="<? echo $row['carica_file']['url']; ?>" target="_blank" rel="nofollow"><? echo $row['carica_file']['title']; ?></a></li>
							<?}
							}
							echo '
										</ul>
									</div>
								</div>
							</div>
						</div>
							';
						}

						$rows = get_field($carica_modulistica);
						/*asort($rows);*/
						if($rows)
						{
							$conta = 0;
							foreach($rows as $row)
							{
								if ($row['assegna_categoria'] == 'Prestazioni Tecniche') { $conta ++; }
							}

							echo '
							<div class="toggle default"><h3><a href="#"><i class="icon-plus-sign"></i>Prestazioni Tecniche - <span>'.$conta.' documenti</span></a></h3>
														<div>
															<div class="wpb_text_column wpb_content_element ">
																<div class="wpb_wrapper">
																	<ul class="dlm-downloads">
							';
							foreach($rows as $row)
							{
								if ($row['assegna_categoria'] == 'Prestazioni Tecniche') {?>
								<li><a class="download-link" title="" href="<? echo $row['carica_file']['url']; ?>" target="_blank" rel="nofollow"><? echo $row['carica_file']['title']; ?></a></li>
							<?}
							}
							echo '
										</ul>
									</div>
								</div>
							</div>
						</div>
							';
						}

						$rows = get_field($carica_modulistica);
						/*asort($rows);*/
						if($rows)
						{
							$conta = 0;
							foreach($rows as $row)
							{
								if ($row['assegna_categoria'] == 'Reclami') { $conta ++; }
							}

							echo '
							<div class="toggle default"><h3><a href="#"><i class="icon-plus-sign"></i>Reclami - <span>'.$conta.' documenti</span></a></h3>
														<div>
															<div class="wpb_text_column wpb_content_element ">
																<div class="wpb_wrapper">
																	<ul class="dlm-downloads">
							';
							foreach($rows as $row)
							{
								if ($row['assegna_categoria'] == 'Reclami') {?>
								<li><a class="download-link" title="" href="<? echo $row['carica_file']['url']; ?>" target="_blank" rel="nofollow"><? echo $row['carica_file']['title']; ?></a></li>
							<?}
							}
							echo '
										</ul>
									</div>
								</div>
							</div>
						</div>
							';
						}

						$rows = get_field($carica_modulistica);
						/*asort($rows);*/
						if($rows)
						{
							$conta = 0;
							foreach($rows as $row)
							{
								if ($row['assegna_categoria'] == 'Varie') { $conta ++; }
							}

							echo '
							<div class="toggle default"><h3><a href="#"><i class="icon-plus-sign"></i>Varie - <span>'.$conta.' documenti</span></a></h3>
														<div>
															<div class="wpb_text_column wpb_content_element ">
																<div class="wpb_wrapper">
																	<ul class="dlm-downloads">
							';
							foreach($rows as $row)
							{
								if ($row['assegna_categoria'] == 'Varie') {?>
								<li><a class="download-link" title="" href="<? echo $row['carica_file']['url']; ?>" target="_blank" rel="nofollow"><? echo $row['carica_file']['title']; ?></a></li>
							<?}
							}
							echo '
										</ul>
									</div>
								</div>
							</div>
						</div>
							';
						}
					?>

				</div> <!-- chiudo toogles-->



				</div>
			</div>
		</div>
	</div>
</div>
