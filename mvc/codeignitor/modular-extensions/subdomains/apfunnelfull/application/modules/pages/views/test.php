		<div id="t-main" role="main">
			<div class="center-width">
				<div class="content billing">
					<form action="#" method="post" id="frmSetup">
						<div class="focus">
							<h1>Search for a <span>domain</span> to start!</h1>
							<ol id="accordion">
								<li class="one">
									<h2>Register a new domain</h2>
									<div class="panel">
										<div class="col-l">
											<div class="row">
												<input type="radio" name="radDomainType" id="radDomainTypeNew" class="required" value="new" checked="checked" />
												<label for="radDomainTypeNew">Register a new domain</label>
											</div>
										</div>
										<div class="col-r">
											<div class="row">
												<input type="radio" name="radDomainType" id="radDomainTypeOld" value="transfer" />
												<label for="radDomainTypeOld">Transfer an existing domain</label>
											</div>
											<div class="row">
												<input type="radio" name="radDomainType" id="radDomainTypeDNS" value="update" />
												<label for="radDomainTypeDNS">I'll update my own DNS setting</label>
											</div>
										</div>
										<input type="text" placeholder="your domain" id="txtDomain" name="txtDomain" class="required" />
										<select id="selTLD" name="selTLD" class="required">
											<option value="com">.com</option>
											<option value="net">.net</option>
											<option value="org">.org</option>
										</select>
										<a href="#" id="next-step1" class="btn-yellow left">Search</a>
									</div>
								</li>
								<li class="two disabled">
									<h2>Select Available Domain</h2>
									<div class="panel">
										<div id="pnl-success">
											<div class="msg-success">
												<p>Congratulations,<br />www.<span>yourdomainsgf.com</span> is available!</p>
											</div>
											<p>If you don't complete your order within the next 10 minutes, your domain will be released at Brain Host and could be registered by someone else.</p>
											<p class="row center"><a href="#" id="next-step2" class="btn-yellow">Continue</a></p>
										</div>
										<div id="pnl-failed">
											<div class="msg-failed">
												<p>Unfortunately,<br />www.<span>yourdomainsgf.com</span> is not available!</p>
											</div>
											
											<h3>View alternate options below</h3>
											<div id="domain-suggestions">
												<div class="row half domain">
													<input type="radio" name="radAlternateDomain" id="radAlternateDomain1" />
													<label for="radAlternateDomain1">www.test.com</label>
												</div>
											</div>
											<p class="row center"><a href="#" id="next-step2" class="btn-yellow">Continue</a><a href="#" id="prev-step2" class="btn-gray">Try Search Again</a></p>
										</div>
									</div>
								</li>
								<li class="three disabled">
									<h2>Select Hosting Package</h2>
									<div class="panel">
										<ol id="hosting-package">
											<li data-hosting-package="twoyear" data-hosting-price="11.95" data-setup-fee="30.00" data-trial-discount="30.00" data-registration-fee="30.00">
												<h3>$11.95</h3>
												<span>a month</span>
												<p><strong>$286.80 Billed Every 24 Months<br />(Save 20%)</strong>
											</li>
											<li data-hosting-package="annual" data-hosting-price="12.95" data-setup-fee="20.00" data-trial-discount="20.00" data-registration-fee="20.00">
												<h3>$12.95</h3>
												<span>a month</span>
												<p><strong>$155.40 Billed Every 12 Months<br />(Save 13%)</strong>
											</li>
											<li data-hosting-package="biannual" data-hosting-price="13.95" data-setup-fee="10.00" data-trial-discount="10.00" data-registration-fee="10.00">
												<h3>$13.95</h3>
												<span>a month</span>
												<p><strong>$83.70 Billed Every 6 Months<br />(Save 6%)</strong>
											</li>
											<li data-hosting-package="monthly" data-hosting-price="14.95" data-setup-fee="0.00" data-trial-discount="0.00"  data-registration-fee="0.00" class="active">
												<h3>$14.95</h3>
												<span>a month</span>
												<p><strong>$14.95 Billed Monthly</strong>
											</li>
										</ol>
										<ul id="package-details">
											<li>Instant Activation = Included</li>
											<li>$250 in Free Advertising Credits = Included</li>
											<li>Account Setup = $<span id="hosting-setup-fee">20</span></li>
											<li>99.9% Uptime Guarantee = Included</li>
											<li>Host Unlimited Websites = Included</li>
											<li>30-Day Money Back Guarantee = Included</li>
											<li>Unlimited Email Accounts =Included</li>
											<li>100% Satisfaction Guaranatee = Included</li>
											<li>Domain Registration = $<span class="registration-fee">14.95</span></li>
											<li>Website Builder = Included</li>
											<li>Unlimited Bandwidth = Included</li>
											<li class="checkbox"><input type="checkbox" id="chkAddonsPrivacy" name="chkAddons" checked="checked" data-addon-price="11.95" /> <label for="chkAddonsPrivacy"><span>Domain Privacy</span> = $11.95</label></li>
											<li>24/7 Customer Support = Included</li>
											<li class="checkbox"><input type="checkbox" id="chkAddonsBackup" name="chkAddons" checked="checked" data-addon-price="9.95" /> <label for="chkAddonsBackup"><span>Automated Daily Backup</span> = $9.95</label></li>
											<li class="checkbox"><input type="checkbox" id="chkAddonsSecurity" name="chkAddons" checked="checked" data-addon-price="9.95" /> <label for="chkAddonsSecurity"><span>Weblock Domain Security</span> = $9.95</label></li>
										</ul>
										<p class="row center"><a href="#" id="next-step3" class="btn-yellow">Continue</a></p>
									</div>
								</li>
								<li class="four disabled">
									<h2>Billing and Payment</h2>
									<div class="panel">
										<p>*Address provided should match billing address.</p>
										<fieldset>
											<h3>Billing Information</h3>
					                        <div class="row half">
					                        	<label for="first_name">First Name</label>
					                        	<input type="text" name="first_name" value="" id="first_name" class="required" size="10">
											</div>
											<div class="row half">
												<label for="last_name">Last Name</label>
					                        	<input type="text" name="last_name" value="" id="last_name" class="required" size="10">
					                        </div>
											<div class="row half">
												<label for="email">Email</label>
					                        	<input type="text" name="email" value="" id="email" class="required" size="10">
					                        </div>
											<div class="row half">
												<label for="phone">Phone</label>
					                        	<input type="text" name="phone" value="" id="phone" class="required" size="10">
					                        </div>
					                        <div class="row half">
					                        	<label for="country">Country</label>
				                                <select name="country" id="country" class="required">
													<optgroup label="Choose Your Country">
														<option value="US" selected="selected" data-req-zip="yes" data-req-state="yes">United States</option>
														<option value="CA" data-req-zip="yes" data-req-state="yes">Canada</option>
														<option value="GB" data-req-zip="yes" data-req-state="yes">United Kingdom</option>
														<option value="AU" data-req-zip="yes" data-req-state="yes">Australia</option>
													</optgroup>
				                                </select>
					                        </div>
											<div class="row half">
												<label for="state">State</label>
				                                <select name="state" id="state" class="required">
				                                	<optgroup label="US">
														<option value="AL">Alabama</option>
														<option value="AK">Alaska</option>
														<option value="AZ">Arizona</option>
													</optgroup>
												</select>
											</div>
											<div class="row">
												<label for="address">Street Address</label>
					                        	<input type="text" name="address" value="" id="address" class="required" size="10">
					                        </div>
											<div class="row half">
												<label for="city">City</label>
					                        	<input type="text" name="city" value="" id="city" class="required" size="10">
					                        </div>
											<div class="row half">
												<label for="zipcode">Zip</label>
					                        	<input type="text" name="zipcode" value="" id="zipcode" class="required" size="10">
					                        </div>
					                    </fieldset>
					                    <fieldset>
					                    	<h3>Payment Information <img src="/resources/allphase_funnel/img/icon-ccs.png" alt="We accept: Visa, MasterCard. AmEx" style="margin:0 0 -5px 5px;" /></h3>
											<div class="row half">
				                                <label for="cc_num">Card Number</label>
				                                <input type="text" name="cc_num" value="" id="cc_num" class="required" size="10" autocomplete="off">
				                            </div>
				                            <div class="row"></div>
											<div class="row half exp">
												<label for="cc_exp_yr">Expiration</label>
				                                <select name="cc_exp_mo" id="cc_exp_mo" class="required">
													<option value="01" selected="selected">01 Jan</option>
													<option value="02">02 Feb</option>
												</select>
												<select name="cc_exp_yr" id="cc_exp_yr" class="required">
													<option value="2012" selected="selected">2012</option>
													<option value="2013">2013</option>
													<option value="2014">2014</option>
												</select>
											</div>
											<div class="row"></div>
											<div class="row half">
				                                <label for="cc_security">Security Code</label>
				                                <input type="text" name="cc_security" value="" id="cc_security" class="cvv required" autocomplete="off">
				                            </div>
				                            <div class="row">
				                            	<input type="checkbox" name="tos_agreement" value="1" id="tos_agreement" class="required"> <label for="tos_agreement" style="float:none;width:auto;">I agree to All Phase Hosting's <a href="#">Terms of Service</a> and <a href="#">Privacy Statement</a>.</label>
				                            </div>
                    					</fieldset>
                    					<p class="row center"><input type="submit" id="submit" class="btn-yellow" value="Continue"></p>
									</div>
								</li>
							</ol>
							<section id="total">
								<h1>Order Summary</h1>
								<ul>
								</ul>
								<strong>Total: $<span>0.00</span></strong>
							</section>
						</div>
						<span class="bg"></span>
						<div id="hosting_options" style="display:none;">
							<input type="hidden" name="hosting" id="selHosting" value="annual"/>
							<input type="hidden" class="hosting_options" data-hosting-package="annual" data-price="95.40" data-setup="0.00" data-trial-discount="0.00" data-months="12"/>
							<input type="hidden" class="hosting_options" data-hosting-package="biannual" data-price="53.70" data-setup="0.00" data-trial-discount="0.00" data-months="6"/>
							<input type="hidden" class="hosting_options" data-hosting-package="monthly" data-price="19.95" data-setup="20.00" data-trial-discount="0.00" data-months="1"/>
						</div>
					</form>
				</div>
			</div>
		</div>