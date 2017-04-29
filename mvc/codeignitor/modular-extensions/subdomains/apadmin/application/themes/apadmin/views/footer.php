<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/jquery.min.js"></script>
<!--  include jquery ui -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/jqueryui/jquery-ui-1.8.23.custom.js"></script>
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/jqueryui/jquery.ui.core.js"></script>
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/jqueryui/jquery.ui.draggable.js"></script>
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/jqueryui/jquery.ui.droppable.js"></script>
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/jqueryui/jquery.ui.sortable.js"></script>
      
<!-- smart resize event -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/jquery.debouncedresize.min.js"></script>
<!-- hidden elements width/height -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/jquery.actual.min.js"></script>
<!-- js cookie plugin -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/jquery._cookie.min.js"></script>
<!-- main bootstrap js -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/bootstrap/js/bootstrap.js"></script>
<!-- tooltips -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/qtip2/jquery.qtip.min.js"></script>
<!-- jBreadcrumbs -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/jBreadcrumbs/js/jquery.jBreadCrumb.1.1.min.js"></script>
<!-- lightbox -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/colorbox/jquery.colorbox.min.js"></script>
<!-- fix for ios orientation change -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/ios-orientationchange-fix.js"></script>
<!-- scrollbar -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/antiscroll/antiscroll.js"></script>
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/antiscroll/jquery-mousewheel.js"></script>
<!-- to top -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/UItoTop/jquery.ui.totop.min.js"></script>
<!-- common functions -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/gebo_common.js"></script>
			
<!-- bootstrap plugins -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/bootstrap.plugins.min.js"></script>
<!-- autosize textareas -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/forms/jquery.autosize.min.js"></script>
<!-- enhanced select -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/chosen/chosen.jquery.min.js"></script>
<!-- touch events for jquery ui-->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/forms/jquery.ui.touch-punch.min.js"></script>
<!-- multi-column layout -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/jquery.imagesloaded.min.js"></script>
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/jquery.wookmark.js"></script>
<!-- responsive table -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/jquery.mediaTable.min.js"></script>
<!-- small charts -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/jquery.peity.min.js"></script>
<!-- calendar -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/fullcalendar/fullcalendar.min.js"></script>
<!-- sortable/filterable list -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/list_js/list.min.js"></script>
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/list_js/plugins/paging/list.paging.min.js"></script>
<!-- password strength checker -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/complexify/jquery.complexify.min.js"></script>
<!-- form functions -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/gebo_forms.js"></script>
<!-- datepicker -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/datepicker/bootstrap-datepicker.min.js"></script>
<!-- timepicker -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/datepicker/bootstrap-timepicker.min.js"></script>
<!-- dashboard functions -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/gebo_dashboard.js"></script>
<!-- validation -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/validation/jquery.validate.min.js"></script>
  <!-- datatable -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/datatables/extras/Scroller/media/js/Scroller.min.js"></script>
<!-- modal function popups -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/jquery.validate.js"></script>	
<!-- calendar -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/fullcalendar/fullcalendar.js"></script>
<!-- validation functions -->
<script>
	$(document).ready(function() {
		//* show all elements & remove preloader
		setTimeout('$("html").removeClass("js")',1000);
		
	});
</script>

<!--  Wizard  jquery functions -->
<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/lib/stepy/js/jquery.stepy.js"></script> 
<script type='text/javascript'>
			var config_dir  = '<?php echo $this->config->item('subdir'); ?>';
			<?php echo isset($javascriptsave) && !empty($javascriptsave) ? $javascriptsave : "target=''; 
		$(document).ready(function() {";?>
			$('.dTableR').dataTable({
                "sDom": "<'row'<'span6'<'dt_actions'>l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
                "sPaginationType": "bootstrap_alt",
                "oLanguage": {
                    "sLengthMenu": "_MENU_ records per page"
                }
            });
				$(".multiselect").chosen({
					allow_single_deselect: true
				});
				$('.datepicker').datepicker();
				$('.timepick').timepicker({
					defaultTime: 'current',
					minuteStep: 5,
					disableFocus: true
				});
			    $('.modal').modal({
			        show: false
			        });
		        $(".closemodal").click(function(){
		        	$('#error' + target).hide();
					$(".modal").modal('hide');
		        });
				// initialize sort behavior
				
				$(".clearfields").click(function(){
					$('#error' + target).hide();
					clearFormFields();
				});
				$('#save_assoc').click(function() {
		        	$('#assoc_dialog').modal('hide');

					var id = $('#assoc_id').val();
		        	var type = $('#assoc_type').val();
					var members = '';

					$('.assoc_checked:checked').each(function() {
						members += $(this).attr('id').replace('assoc_', '') + ',';
					});

					members = members.substring(0, members.length - 1);  // trim last comma
					
					clearFormFields();
	        	
					$.post('<?php echo $this->config->item('subdir'); ?>/ajax/apadmin/assocsave/' + type + '/', { id: id, members: members } );
				});

			});
			<?php echo isset($wizardjs)? $wizardjs:''; ?>
			
			</script>	
			<script src="<?php echo $this->config->item('subdir'); ?>/resources/apadmin/js/modal.save.js"></script>	
		</div>

		<?php /*
		// coding champion code goes here
		if ($this->session->userdata('coding_champion_footer')):
			
			// embed code
			echo '
				<script type="text/javascript">
				$(document).ready(function() {
					
					$("#coding_champion").modal("show");

					setTimeout(function(){$("#champ").show()},2000);
				});
				</script>

				<div class="modal fade" id="coding_champion" >
					
					<div class="modal-header">	
					</div>
					
					<div class="modal-body" id="champ" style="display:none;">
						
						<center><img src="http://i.imgur.com/DNO3a.gif"></center>
						<!--
						<img src="http://i.imgur.com/dBNKQ.gif">
						<iframe width="540" height="380" src="http://www.youtube.com/embed/oHg5SJYRHA0?autoplay=1&start=22&controls=0" frameborder="0" allowfullscreen></iframe>-->
					</div>
					
					<div class="modal-footer">
					</div>
		
				</div>
			';

			// destroy session
			$this->session->unset_userdata('coding_champion_footer');

		endif;*/
		?>

	</body>
</html>