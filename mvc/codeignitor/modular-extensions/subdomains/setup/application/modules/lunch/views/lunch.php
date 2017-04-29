<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Lunch Picker</title>

	<style type="text/css">

		html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,center,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,summary,time,mark,audio,video{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline}
		article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}
		body{line-height:1}
		ol,ul{list-style:none}
		blockquote,q{quotes:none}
		blockquote:before,blockquote:after,q:before,q:after{content:'';content:none}
		table{border-collapse:collapse;border-spacing:0}
	

		body {font-family:arial,helvetica,sans-serif;line-height:1.3em;}
		h1,h2 {font-size:1.5em;font-weight:700;display:block;line-height:2em;}
		h2 {font-size:1.2em;}
		#wrapper {width:1024px;margin:20px auto;}
		.column {float:left;margin-right:30px;}
		button{clear:both;display:block;}
		li{list-style-type:none;margin:5px 0 5px 15px;}

	</style>

</head>
<body>

	<form action="" method="post">
			
		<div id="wrapper">

			<?php if (isset($winner) && $winner): ?>
			
				<h1>WINNER: <?php echo $combined[$winner]; ?></h1>

			<?php endif; ?>

			<div class="column">

				<h2>Dine In</h2>

				<ul>

				<?php 

				foreach ($in as $key => $restaurant): 

					$checked = '';

					if ($this->input->post('submittered') && (is_array($selected) && in_array($key, $selected))):

						$checked = 'checked="checked"';

					endif; ?>
					
					<li>
						<label>
							<input name="restaurants[]" value="<?php echo $key;?>" type="checkbox" <?php echo $checked; ?> /> 
							<?php echo $restaurant; ?>
						</label>
					</li>

				<?php endforeach; ?>

				</ul>

			</div>
			<div class="column">
				<h2>Take Out</h2>
				<ul>
					<?php foreach ($out as $key => $restaurant): 

						$checked = '';

						if ($this->input->post('submittered') && (is_array($selected) && in_array($key, $selected))):

							$checked = 'checked="checked"';

						endif; ?>
						
						<li>
							<label>
								<input name="restaurants[]" value="<?php echo $key;?>" type="checkbox" <?php echo $checked; ?> /> 
								<?php echo $restaurant; ?>
							</label>
						</li>

					<?php endforeach; ?>
				</ul>
			</div>	
			
			<input type="hidden" name="submittered" value="1" />
			<button>MMM mmm good.</button>	
		</div>		

	</form>
	
</body>
</html>