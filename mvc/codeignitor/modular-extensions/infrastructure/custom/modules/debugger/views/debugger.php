<div>
	
	<div style="margin:10px;padding:10px;border:1px solid #000;background:#fff">
	
		<h1 style="font-weight:bold;font-size:1.3em;background:#eee;padding:10px;border:#dedede;" id="debug_session">
			SESSION <a href="#debug_cookies" style="font-size:.7em;">(Skip to cookies)</a>
		</h1>
		
		<pre><?php print_r($this->session->userdata); ?></pre>
		


		<h1 style="font-weight:bold;font-size:1.3em;background:#eee;padding:10px;border:#dedede;" id="debug_cookies">
			COOKIES <a href="#debug_session" style="font-size:.7em;">(Back to session)</a>
		</h1>
		

		<?php
			
		$cookies = array();

		$ignore_cookies = array(
			'__u',
			'PHPSESSID',
			'bhcisess',
			'yggdrasil',
			'_pk',
			'__ar',
			'_pb'
		);
	
		foreach ($_COOKIE as $ckey=>$cval):

			$show_cookie = TRUE;

			foreach ($ignore_cookies as $ignore):

				if (substr($ckey,0,strlen($ignore)) == $ignore):

					$show_cookie = FALSE;
					break;

				endif;

			endforeach;

			if ( ! $show_cookie):

				continue;

			endif;

			$cookies[$ckey] = $cval;	
			
		endforeach;
			
		?>
		
		<pre><?php print_r($cookies); ?></pre>
	
	</div>
	
</div>