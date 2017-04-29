<html>
<head>
	<title>How many beans?</title>

	<style type="text/css">
		article,aside,details,figcaption,figure,footer,header,hgroup,nav,section,summary{display:block}audio,canvas,video{display:inline;zoom:1}audio:not([controls]){display:none;height:0}[hidden]{display:none}html{font-size:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}html,button,input,select,textarea{font-family:sans-serif}a:focus{outline:thin dotted}a:active,a:hover{outline:0}abbr[title]{border-bottom:1px dotted}b,strong{font-weight:700}blockquote{margin:1em 40px}dfn{font-style:italic}mark{background:#ff0;color:#000}code,kbd,pre,samp{font-family:monospace,serif;_font-family:'courier new',monospace;font-size:1em}pre{white-space:pre-wrap;word-wrap:break-word}q{quotes:none}q:before,q:after{content:none}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sup{top:-.5em}sub{bottom:-.25em}dd{margin:0 0 0 40px}menu,ol,ul{padding:0 0 0 40px}nav ul,nav ol{list-style:none;list-style-image:none}img{border:0;-ms-interpolation-mode:bicubic}svg:not(:root){overflow:hidden}fieldset{border:1px solid silver;margin:0 2px;padding:.35em .625em .75em}legend{border:0;white-space:normal;margin-left:-7px;padding:0}button,input,select,textarea{font-size:100%;vertical-align:middle;margin:0}button,input{line-height:normal}button,html input[type="button"],input[type="reset"],input[type="submit"]{-webkit-appearance:button;cursor:pointer;overflow:visible}button[disabled],input[disabled]{cursor:default}input[type="checkbox"],input[type="radio"]{box-sizing:border-box;height:13px;width:13px;padding:0}input[type="search"]{-webkit-appearance:textfield;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;box-sizing:content-box}input[type="search"]::-webkit-search-cancel-button,input[type="search"]::-webkit-search-decoration{-webkit-appearance:none}button::-moz-focus-inner,input::-moz-focus-inner{border:0;padding:0}textarea{overflow:auto;vertical-align:top}table{border-collapse:collapse;border-spacing:0}body,figure,form{margin:0}

		td, th {padding:10px;border:1px solid #ddd;}

		#wrap {padding:20px;width:800px;margin:0 auto;}

		form {padding:20px 0;text-align:right;}

		label {display:block;margin:5px 0;}

	</style>

</head>
<body>

	<div id="wrap">
	
		<form method="post" action="">
	
			<label>
				Start Date:
				<input type="text" name="start_date" value="<?php echo $start_date; ?>" />
			</label>

			<label>
				End Date:
				<input type="text" name="end_date" value="<?php echo $end_date; ?>" />
			</label>

			<button type="submit">Go</button>

		</form>

		<table>
			<thead>
				<tr>
					<th></th>
					<th>Brain Host</th>
					<th>BH Brazil</th>
					<th>Purely Hosting</th>
					<th>FreeWebsite</th>
				</tr>
			</thead>
			<tbody>

				<?php if (isset($stats) && is_array($stats)): 

					foreach ($stats as $skey => $info): 

						$name = ucwords(str_replace('_',' ', $skey)); ?>

						<tr>
							<td><?php echo $name; ?></td>
							<?php foreach ($info as $bkey => $stat): ?>
			
								<td><?php echo number_format($stat,2); ?></td>

							<?php endforeach; ?>
						</tr>

					<?php 

					endforeach;

				endif; ?>

			</tbody>
		</table>
	</div>

</body>
</html>