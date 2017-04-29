<?php $partner_data = $this->session->userdata('partner_info'); ?>

<h2><?php echo $this->lang->line('billing_v1_c4c_donation'); ?></h2>
<div>
    <div id="change-for-change">
        <img src="/resources/modules/billing/assets/v1/img/english/img-habitat.png" />
        <h3><?php echo $this->lang->line('billing_v1_c4c_gives_back', $partner_data['website']['company_name']); ?></h3>
        <p><strong><?php echo $this->lang->line('billing_v1_c4c_join_us'); ?></strong></p>
        <p><?php echo $this->lang->line('billing_v1_c4c_partnered', $partner_data['website']['company_name']); ?></p>
        <p><em>Every donation helps us make a difference!</em> <a href="http://www.youtube.com/embed/7jp3txyqC4s?autoplay=1" class="lightview" rel="flash"><?php echo $this->lang->line('billing_v1_c4c_learn'); ?></a></p>
        <input type="radio" id="radC4CYes" name="charity" class="required" checked="checked" value="yes"/><label for="radC4CYes"><?php echo $this->lang->line('billing_v1_c4c_yes'); ?></label>
    </div>
    <div id="change-for-change-no">
        <input type="radio" id="radC4CNo" name="charity" class="required" value="no" /><label for="radC4CNo"><?php echo $this->lang->line('billing_v1_c4c_no'); ?></label>
    </div>
    <?php /*
        $c4c_checked = ( ! $fields['c4c'] && $this->input->post('billing_form')) ? '' : 'checked';

        echo form_checkbox(
            array(
                'name'    => 'c4c',
                'value'   => '1',
                'id'      => 'c4c',
                'checked' => $c4c_checked
            )
        );
    */ ?>
    <p class="center row"><a href="#" id="trigger-step4"><img src="/resources/modules/billing/assets/v1/img/english/btn-continue-next.png" alt="<?php echo $this->lang->line('billing_v1_contine_next'); ?>" /></a></p>
</div>