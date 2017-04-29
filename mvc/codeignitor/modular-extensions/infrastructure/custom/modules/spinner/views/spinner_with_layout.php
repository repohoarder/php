
          <h1>Choose Your Domain</h1>
          <section class="choosedomain">
            
            <?php 

            foreach ($suggestions as $key => $value): ?>

              <form action="<?php echo $submit_url;?>" method="post" target="_top" class="form_container">

                <section class="domainbx_outer">
                  <section class="domainbx_top"></section>
                  <section class="domainbx_mid">
                    <section class="domain_outer">
                      <section class="domainavail"><span><?php echo $value; ?></span> is available</section>
                      <section class="getdomain_btn">
                        
                        <button type="submit" class="get_domain">Get This Domain</button>

                      </section>
                    </section>
                  </section>
                </section>

                <input type="hidden" name="core_domain" value="<?php echo $value; ?>"/>
                <input type="hidden" name="core_num_years" value="1"/>
                <input type="hidden" name="core_type" value="register"/>

              </form>

              <?php

            endforeach; 

            ?>

          </section>
          <section class="owndomain_outer"> <img src="/resources/purely_aress/assets/images/or_img.png" alt="or">

            <form action="<?php echo $submit_url;?>" id="choose_own">
              
              <section class="click_btn">
  
                <button id="choose_own">Click Here to Choose Your Own Domain and Website Now</button>

              </section>
              
            </form>

          </section>

          <img height="1" width="1" src="http://freeaffiliateclub.com/tracking/step_3_pixel.php" />