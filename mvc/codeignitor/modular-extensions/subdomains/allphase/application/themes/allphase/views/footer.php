		<footer id="t-footer">
			<div class="wrap">
				<div class="col-l">
					<img src="/resources/allphase/img/logo2.png" alt="All Phase Hosting" />
				</div>
				<div class="col-r">
					<p>At All Phase Hosting, we are dedicated to providing our Partners with unmatched support, flexibility, and brand-control, making it easy and profitable to own and manage your web hosting company!</p>

					<!--
					<div class="column">
						<h2>Services</h2>
						<ul>
							<li><a href="#">Sign Up Now!</a></li>
							<li><a href="#">Features</a></li>
							<li><a href="#">About Us</a></li>
							<li><a href="#">Affiliates</a></li>
							<li><a href="#">Partners</a></li>
						</ul>
					</div>
					<div class="column">
						<h2>Help Center</h2>
						<ul>
							<li><a href="#">Customer Login</a></li>
							<li><a href="#">Contact Us</a></li>
							<li><a href="#">Support Center</a></li>
						</ul>
					</div>
					<div class="column">
						<h2>Connect</h2>
						<ul>
							<li><a href="#">Customer Login</a></li>
							<li><a href="#">Contact Us</a></li>
							<li><a href="#">Support Center</a></li>
						</ul>
					</div>
				-->
				</div>
			</div>
			<div id="t-copyright">
				<div class="wrap">

					<span id="copyright">&copy;</span> <?php echo date("Y"); ?> All Phase Web Hosting, LLC All Rights Reserved.

					<ul>
						<!--<li><a href="http://allphasehosting.com/about-us/">About Us</a></li>-->
						<li><a href="http://www.allphasehosting.com/terms">Terms and Conditions</a></li>
						<li><a href="http://www.allphasehosting.com/privacy">Privacy Policy</a></li>
					</ul>
				</div>
			</div>
		</footer>
		<?php 

		echo $template['footermeta']; 

		$this->load->config('debug');
		if (in_array($this->session->userdata('ip_address'), $this->config->item('debug_ips'))): ?>

			<script type="text/javascript" src="/resources/brainhost/js/debugger.js"></script>

		<?php endif; ?>


		<?php include('tracking/analytics.php'); ?>


	</body>
</html>
