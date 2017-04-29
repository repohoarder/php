//*******************  UI  *******************//
			$(function(){

				// Accordion
				$("#accordion").accordion({ header: "h3" });
	
				// Tabs
				$('#tabs').tabs();

				// Dialog			
				$('#dialog').dialog({
					autoOpen: false,
					width: 600,
					buttons: {
						"Ok": function() { 
							$(this).dialog("close"); 
						}, 
						"Cancel": function() { 
							$(this).dialog("close"); 
						} 
					}
				});
				
				// Dialog Link
				$('#dialog_link').click(function(){
					$('#dialog').dialog('open');
					return false;
				});

				// Datepicker
				$('#datepicker').datepicker({
					inline: true
				});
				$('#inline-datepicker').datepicker({
					inline: true
				});
				
				// Slider
				$( "#slider" ).slider(
					{
						slide: function( event, ui ) {
							$( "#amount" ).val( "$" + ui.value );
						}
					}
				);
				
				$( "#slider2" ).slider({
						value:100,
						min: 0,
						max: 500,
						step: 1,
						slide: function( event, ui ) {
							$( "#amount" ).val( "$" + ui.value );
						}
					});
				$( "#amount" ).val( "$" + $( "#slider" ).slider( "value" ) );
				$( "#slider-range" ).slider({
					range: true,
					min: 0,
					max: 500,
					values: [ 75, 300 ],
					slide: function( event, ui ) {
						$( "#amount2" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
					}
				});
				$( "#amount2" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
					" - $" + $( "#slider-range" ).slider( "values", 1 ) );
					// setup graphic EQ
				$( "#eq > span" ).each(function() {
					// read initial values from markup and remove that
					var value = parseInt( $( this ).text(), 10 );
					$( this ).empty().slider({
						value: value,
						range: "min",
						animate: true,
						orientation: "vertical"
					});
				});
				$( "#slider-range-min" ).slider({
					range: "min",
					value: 23,
					min: 23,
					max: 500,
					slide: function( event, ui ) {
						$( "#amount3" ).val( "$" + ui.value );
					}
				});
				$( "#amount3" ).val( "$" + $( "#slider-range-min" ).slider( "value" ) );
				$( "#slider-range-max" ).slider({
					range: "max",
					value: 56,
					min: 1,
					max: 350,
					slide: function( event, ui ) {
						$( "#amount4" ).val( "$" + ui.value );
					}
				});
				$( "#amount4" ).val( "$" + $( "#slider-range-min" ).slider( "value" ) );
				// Progressbar
				$("#progressbar").progressbar({
					value: 20
				});
				
				//hover states on the static widgets
				$('#dialog_link, ul#icons li').hover(
					function() { $(this).addClass('ui-state-hover'); }, 
					function() { $(this).removeClass('ui-state-hover'); }
				);
				
			});

			
//*******************  MENU LEFT  *******************//
jQuery.fn.initMenu = function() {  
    return this.each(function(){
        var theMenu = $(this).get(0);
        $('.acitem', this).hide();
        $('li.expand > .acitem', this).show();
        $('li.expand > .acitem', this).prev().addClass('active');
        $('li a', this).click(
            function(e) {
                e.stopImmediatePropagation();
                var theElement = $(this).next();
                var parent = this.parentNode.parentNode;
                if($(parent).hasClass('noaccordion')) {
                    if(theElement[0] === undefined) {
                        window.location.href = this.href;
                    }
                    $(theElement).slideToggle('normal', function() {
                        if ($(this).is(':visible')) {
                            $(this).prev().addClass('active');
                        }
                        else {
                            $(this).prev().removeClass('active');
                        }    
                    });
                    return false;
                }
                else {
                    if(theElement.hasClass('acitem') && theElement.is(':visible')) {
                        if($(parent).hasClass('collapsible')) {
                            $('.acitem:visible', parent).first().slideUp('normal', 
                            function() {
                                $(this).prev().removeClass('active');
                            }
                        );
                        return false;  
                    }
                    return false;
                }
                if(theElement.hasClass('acitem') && !theElement.is(':visible')) {         
                    $('.acitem:visible', parent).first().slideUp('normal', function() {
                        $(this).prev().removeClass('active');
                    });
                    theElement.slideDown('normal', function() {
                        $(this).prev().addClass('active');
                    });
                    return false;
                }
            }
        }
    );
});
};

$(document).ready(function() {$('.menu').initMenu();});
	
//*******************  Suggest Search *******************//
	$(document).ready(function(){
			var options = {
		script:"php/suggest.php?json=true&",
		varname:"input",
		json:true,
		callback: function (obj) { document.getElementById('testid').value = obj.id; }
	};
	var as_json = new AutoSuggest('testinput', options);
	
	
	var options_xml = {
		script:"php/suggest?",
		varname:"input"
	};
	var as_xml = new AutoSuggest('testinput_xml', options_xml);
			
					
	});
	
		
//*******************  jScroll Panel *******************//	
	$(function()
			{
				$('.scroll-pane').each(
					function()
					{
						$(this).jScrollPane(
							{
								showArrows: $(this).is('.arrow')
							}
						);
						var api = $(this).data('jsp');
						var throttleTimeout;
						$(window).bind(
							'resize',
							function()
							{
								if ($.browser.msie) {
									// IE fires multiple resize events while you are dragging the browser window which
									// causes it to crash if you try to update the scrollpane on every one. So we need
									// to throttle it to fire a maximum of once every 50 milliseconds...
									if (!throttleTimeout) {
										throttleTimeout = setTimeout(
											function()
											{
												api.reinitialise();
												throttleTimeout = null;
											},
											50
										);
									}
								} else {
									api.reinitialise();
								}
							}
						);
					}
				)

			});
			
			
			
//*******************  Placeholder for all browsers  *******************//

	$(function() {
	$("input").each(
		function(){
			if($(this).val()=="" && $(this).attr("placeholder")!=""){
			$(this).val($(this).attr("placeholder"));
			$(this).focus(function(){
				if($(this).val()==$(this).attr("placeholder")) $(this).val("");
			});
			$(this).blur(function(){
				if($(this).val()=="") $(this).val($(this).attr("placeholder"));
			});
		}
	});

/* */

//*******************  Collapsing blocks jQuery  *******************//

	$(document).ready(function() {
		$('.title-grid').append('<span></span>');
		$('.grid-1 span').each(function() {
			var trigger = $(this), state = false, el = trigger.parent().next('.content-gird');
			trigger.click(function(){
				state = !state;
				el.slideToggle();
				trigger.parent().parent().toggleClass('inactive');
			});
		});
				$('.grid-2 span').each(function() {
			var trigger = $(this), state = false, el = trigger.parent().next('.content-gird');
			trigger.click(function(){
				state = !state;
				el.slideToggle();
				trigger.parent().parent().toggleClass('inactive');
			});
		});
				$('.grid-3 span').each(function() {
			var trigger = $(this), state = false, el = trigger.parent().next('.content-gird');
			trigger.click(function(){
				state = !state;
				el.slideToggle();
				trigger.parent().parent().toggleClass('inactive');
			});
		});
	});
				$('.grid-4 span').each(function() {
			var trigger = $(this), state = false, el = trigger.parent().next('.content-gird');
			trigger.click(function(){
				state = !state;
				el.slideToggle();
				trigger.parent().parent().toggleClass('inactive');
			});
		});
	});
	


//*******************  Fancybox  *******************//

	$(document).ready(function() {
				$("a.fancybox").fancybox({
				'titlePosition'		: 'outside',
				'overlayColor'		: '#000',
				'overlayOpacity'	: 0.8
			});
			
			$("#vimeo_test3").fancybox(
		$.extend(fb_opts, {'href' : 'http://vimeo.com/moogaloop.swf?clip_id=16700057&amp;fullscreen=1', 'overlayColor'		: '#000',
				'overlayOpacity'	: 0.8})
	);
	
	
	});
	
	
	
	function formatTitle(title, currentArray, currentIndex, currentOpts) {
	   
	}
	
	var fb_opts = {
		'padding'		: 0,
		'cyclic'        : true,
		'autoScale'		: false,
		'transitionIn'	: 'elastic',
		'transitionOut'	: 'elastic',
		'width'			: 640,
		'height'		: 360,
		'type'			: 'swf',
		'showNavArrows' : false,
		'titlePosition' : 'inside',
		'titleFormat'	: formatTitle,
		'swf'           : {
		    'wmode'				: 'transparent',
		    'allowfullscreen'	: 'true'
		}
	};

//*********************  Information messages   (Alerts)  *********************//
	$(document).ready(function() {
		$(".hideit").click(function() {
			$(this).fadeOut(1000);
		});
		
	});

//********************* Color Picker  *********************//
	$(document).ready(function() {
	$('#colorpickerField1, #colorpickerField2, #colorpickerField3').ColorPicker({
	onSubmit: function(hsb, hex, rgb, el) {
		$(el).val(hex);
		$(el).ColorPickerHide();
	},
	onBeforeShow: function () {
		$(this).ColorPickerSetColor(this.value);
	}
	})
	.bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});
	});
//********************* CheckBox Iphone  *********************//
	  $(document).ready(function(){
		$('.splLink').click(function(){
		  $(this).parent().children('div.splCont').toggle('fast');
		  return false;
		});
	  });
	  
	  	$(document).ready(function(){
	
	$('input[type=checkbox].iphone').tzCheckbox({labels:['Enable','Disable']});
	$('input[type=checkbox].iphone2').tzCheckbox2({labels:['Enable','Disable']});
});
//*********************  CALENDAR  *********************//			
	$(document).ready(function() {
		
		
		var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	
	$('.calendar').fullCalendar({
		header: {
			left: 'prev,next',
			center: 'title',
			right: 'month,basicWeek,basicDay'
		},
		editable: true,
		events: [
			{
				title: 'All day event',
				start: new Date(y, m, 2)
			},
			{
				title: 'Long event',
				start: new Date(y, m, 5),
				end: new Date(y, m, 7)
			},
			{
				id: 999,
				title: 'Repeating event',
				start: new Date(y, m, 10, 11, 0),
				end: new Date(y, m, 3, 18, 0),
				allDay: false
			},
			{
				id: 999,
				title: 'Repeating event',
				start: new Date(y, m, 9, 16, 0),
				end: new Date(y, m, 10, 18, 0),
				allDay: false
			},
			{
				title: 'Long text text text text text text ',
				start: new Date(y, m, 30, 10, 30),
				end: new Date(y, m, d+1, 14, 0),
				allDay: false,
			},
			{
				title: 'Lunch',
				start: new Date(y, m, 14, 12, 0),
				end: new Date(y, m, 15, 14, 0),
				allDay: false
			},
			{
				title: 'Birthday PARTY',
				start: new Date(y, m, 18),
				end: new Date(y, m, 20),
				allDay: false
			},
			{
				title: 'Click link',
				start: new Date(y, m, 23),
				end: new Date(y, m, 26),
				url: 'http://themeforest.net/user/dimka_ua_kh'
			}
		]
	});
		
	});
	
	
	
//*********************  File explorer  *********************//
	$(document).ready(function(){
			
			var f = $('#finder').elfinder({
				url : 'lib/elfinder/connectors/php/connector.php',
				lang : 'en',
				docked : true

				// dialog : {
				// 	title : 'File manager',
				// 	height : 500
				// }

				// Callback example
				//editorCallback : function(url) {
				//	if (window.console && window.console.log) {
				//		window.console.log(url);
				//	} else {
				//		alert(url);
				//	}
				//},
				//closeOnEditorCallback : true
			})
			// window.console.log(f)
			$('#close,#open,#dock,#undock').click(function() {
				$('#finder').elfinder($(this).attr('id'));
			})
			
		});
//*********************   EDITOR   *********************//
		$(document).ready(function(){
			$('#editor').wysiwyg({
				controls:"bold,italic,|,undo,redo,image"
			});
			$('#editor-2').wysiwyg({
				controls:"bold,italic,|,undo,redo,image"
			});
		});
		
		
//*********************  FORMS   *********************//
	//select
	$(document).ready(function() {
	 $(".chzn-select").chosen(); $(".chzn-select-deselect").chosen({allow_single_deselect:true}); 
	});
	
	$(document).ready(function(){
	$("input[type=file]").change(function(){$(this).parents(".uploader").find(".filename").val($(this).val());});
	$("input[type=file]").each(function(){
	if($(this).val()==""){$(this).parents(".uploader").find(".filename").val("No file selected...");}
	});
	});
	

//*********************  SMILES   *********************//
 
function collapsElement(id) {
    if ( document.getElementById(id).style.display != "none" ) {
        document.getElementById(id).style.display = 'none';
		document.getElementById(id).show().jScrollPane();
    }
    else {
        document.getElementById(id).style.display = '';

    }
}
<!--
var ie=document.all?1:0;
var ns=document.getElementById&&!document.all?1:0;

function InsertSmile(SmileId)
{
if(ie)
{
document.all.message.focus();
document.all.message.value+=" "+SmileId+" ";
}

else if(ns)
{
document.forms['guestbook'].elements['message'].focus();
document.forms['guestbook'].elements['message'].value+=" "+SmileId+" ";
}

else
alert("Your browser is not supported!");
}
// -->	
	
//********************* Tooltip *********************//	
	$(function(){
		
		$(".tip-top").tipTip({defaultPosition: "top", delay: 1000});
		$(".tip-bottom").tipTip({defaultPosition: "bottom", delay: 1000});
		$(".tip-left").tipTip({defaultPosition: "left", delay: 1000});
		$(".tip-right").tipTip({defaultPosition: "right", delay: 1000});
	});

		
//********************* Select all Checkbox *********************//
	function setChecked(obj) 
		{
	
		var check = document.getElementsByName("id[]");
		for (var i=0; i<check.length; i++) 
		   {
		   check[i].checked = obj.checked;
		   }
	}
	
//********************* TABLE (NEWS) *********************//
$(document).ready(function() {
    $('#example').dataTable( {
        "sPaginationType": "full_numbers",
		
    } );
} );
	
//********************* autorisize *********************//	

	$(document).ready(function() {
	$('textarea.resize-text').autoResize({});
	});
	
//********************* Contact list *********************//	
	 $(document).ready(function(){
         $('#slider-contact').sliderNav();
		 
		 
     });
	 
//********************* Dialogs *********************//
$(document).ready( function() {
				
				$("#alert_button").click( function() {
					jAlert('This is a custom alert boxhis is a custom alert boxhis is a custom alert boxhis is a custom alert boxhis is a custom alert box', 'Alert Dialog');
				});
				
				$("#confirm_button").click( function() {
					jConfirm('Can you confirm this?', 'Confirmation Dialog', function(r) {
						jAlert('Confirmed: ' + r, 'Confirmation Results');
					});
				});
				
				$("#prompt_button").click( function() {
					jPrompt('Type something:<br>', 'Prefilled value', 'Prompt Dialog', function(r) {
						if( r ) alert('You entered ' + r);
					});
				});
				
				$("#alert_button_with_html").click( function() {
					jAlert('You can use HTML, such as <strong>bold</strong>, <em>italics</em>, and <u>underline</u>!');
				});
				
				$(".alert_style_example").click( function() {
					$.alerts.dialogClass = $(this).attr('id'); // set custom style class
					jAlert('This is the custom class called &ldquo;style_1&rdquo;', 'Custom Styles', function() {
						$.alerts.dialogClass = null; // reset to default
					});
				});
			});
			
//********************* Auto TAB (Input) *********************//
	$(document).ready(function() {
		$('#autotab_example').submit(function() { return false; });
		$('#autotab_example :input').autotab_magic();
		// Number example
		$('#area_code, #number1, #number2').autotab_filter('numeric');
		$('#ssn1, #ssn2, #ssn3').autotab_filter('numeric');
		// Text example
		$('#text1, #text2, #text3').autotab_filter('text');
		// Alpha example
		$('#alpha1, #alpha2, #alpha3, #alpha4, #alpha5').autotab_filter('alpha');
		// Alphanumeric example
		$('#alphanumeric1, #alphanumeric2, #alphanumeric3, #alphanumeric4, #alphanumeric5').autotab_filter({ format: 'alphanumeric', uppercase: true });
		$('#regex').autotab_filter({ format: 'custom', pattern: '[^0-9\.]' });
	});
	
	
 
//********************* Sweet Tooltip *********************//	
$(document).ready(function() {
	
	$('.sweet-tooltip').bind('mouseover', function() {
		
		tooltip				= $(this);
		tooltipText 		= tooltip.attr('data-text-tooltip');
		tooltipClassName	= tooltip.attr('data-style-tooltip');
		tooltipClass		= '.' + tooltipClassName;
		
		if(tooltip.hasClass('showed-tooltip')) return false;
		
		tooltip.addClass('showed-tooltip')
				 .after('<div class="'+tooltipClassName+'">'+tooltipText+'</div>');

		
		tooltipPosTop 	= tooltip.offset().top - $(tooltipClass).outerHeight() - 10;
		tooltipPosLeft = tooltip.offset().left;
		tooltipPosLeft = tooltipPosLeft - (($(tooltipClass).outerWidth()/2) - tooltip.outerWidth()/2);
		
		$(tooltipClass).css({ 'left': tooltipPosLeft, 'top': tooltipPosTop })
							.animate({'opacity':'1', 'marginTop':'0'}, 500);
		
	}).bind('mouseout', function() {
		
		$(tooltipClass).animate({'opacity':'0', 'marginTop':'-10px'}, 500, function() {
				
			$(this).remove();
			tooltip.removeClass('showed-tooltip');
				
		});
	});
});

//*********************  Hover Zoom   *********************//	
$(document).ready(function(){
        
            $('.red-mus').hoverMusic({
                overlayColor: '#cc0d00',
            });
            $('.orange-mus').hoverMusic({
                overlayColor: '#ff9000',
            });
			$('.pink-mus').hoverMusic({
                overlayColor: '#ac3a72',
            });
			$('.blue-mus').hoverMusic({
                overlayColor: '#1172a4',
            });
            
            $('.red').hoverZoom({
                overlayColor: '#cc0d00',
            });
            $('.orange').hoverZoom({
                overlayColor: '#ff9000',
            });
			$('.pink').hoverZoom({
                overlayColor: '#ac3a72',
            });
			$('.blue').hoverZoom({
                overlayColor: '#1172a4',
            });
            
});	

//*********************  Toggle   *********************//	
$(document).ready(function () {
     
    $('#toggle-view li').click(function () {
 
        var text = $(this).children('div.panel');
 
        if (text.is(':hidden')) {
            text.slideDown('200');
            $(this).children('span').html('-');     
        } else {
            text.slideUp('200');
            $(this).children('span').html('+');     
        }
         
    });
 
});
//*********************   Charts   *********************//	


//*Interacting with the data points *//
$(function () {
   	// CHARTS        

    $("table.chart2").each(function() {
        var colors = [];
        $("table.chart thead th:not(:first)").each(function() {
            colors.push($(this).css("color"));
        });
        $(this).graphTable({
            series: 'columns',  position: 'replace',width: '100%', height: '250px', colors: colors
        }, {  xaxis: {  tickSize: 1,  },
			yaxis: {
				 max: 1000,
				min:200,
            }	,	series: {
				points: {show: true },
                lines: { show: true, fill: false, steps: false },
			}
        });
    });
	
	

    function showTooltip(x, y, contents) {
        $('<div id="tooltip" >' + contents + '</div>"').css({
            position: 'absolute',
            display: 'none',
            top: y -13,
            left: x + 10
        }).appendTo("body").show();
    }

    var previousPoint = null;
    $(".chart_flot").bind("plothover", function(event, pos, item) {
												
        $("#x").text(pos.x);
        $("#y").text(pos.y);

        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;

			$(this).attr('title',item.series.label);
			$(this).trigger('click');
                $("#tooltip").remove();
                var x = item.datapoint[0],
                    y = item.datapoint[1];

                showTooltip(item.pageX, item.pageY,  "<p>Info for a day</p><b>" + item.series.label + "</b> : " + y);
            }
        }  else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });
});




/* Updating graphs real-time */
$(function () {
    // we use an inline data source in the example, usually data would
    // be fetched from a server
    var data = [], totalPoints = 300;
    function getRandomData() {
        if (data.length > 0)
            data = data.slice(1);

        // do a random walk
        while (data.length < totalPoints) {
            var prev = data.length > 0 ? data[data.length - 1] : 50;
            var y = prev + Math.random() * 10 - 5;
            if (y < 0)
                y = 0;
            if (y > 100)
                y = 100;
            data.push(y);
        }

        // zip the generated y values with the x values
        var res = [];
        for (var i = 0; i < data.length; ++i)
            res.push([i, data[i]])
        return res;
    }

    // setup control widget
    var updateInterval = 1000;
    $("#updateInterval").val(updateInterval).change(function () {
        var v = $(this).val();
        if (v && !isNaN(+v)) {
            updateInterval = +v;
            if (updateInterval < 1)
                updateInterval = 1;
            if (updateInterval > 2000)
                updateInterval = 2000;
            $(this).val("" + updateInterval);
        }
    });

    // setup plot
    var options = {
        series: { shadowSize: 0 }, // drawing is faster without shadows
        yaxis: { min: 0, max: 120 },
        xaxis: { show: false },
		
   colors: ["#2686d2"],
			series: {
					   lines: { 
							lineWidth: 1, 
							fill: true,
							fillColor: { colors: [ { opacity: 0.5 }, { opacity: 1.0 } ] },
							steps: false ,
							show:true
	
						},points: {show: false }
				   }
		};
    var plot = $.plot($(".autoUpdate"), [ getRandomData() ], options);

    function update() {
        plot.setData([ getRandomData() ]);
        // since the axes don't change, we don't need to call plot.setupGrid()
        plot.draw();
        
        setTimeout(update, updateInterval);
    }

    update();
});

//* BAR *//


$(function () {
    var previousPoint;
    var d1 = [];
    for (var i = 0; i <= 10; i += 1)
        d1.push([i, parseInt(Math.random() * 30)]);
 
    var d2 = [];
    for (var i = 0; i <= 10; i += 1)
        d2.push([i, parseInt(Math.random() * 30)]);
 
    var d3 = [];
    for (var i = 0; i <= 10; i += 1)
        d3.push([i, parseInt(Math.random() * 30)]);
 
    var ds = new Array();
 
    ds.push({
        data:d1,

        bars: {
            show: true, 
            barWidth: 0.2, 
            order: 1,
            lineWidth : 2
        }
		
    });
    ds.push({
        data:d2,
        bars: {
            show: true, 
            barWidth: 0.2, 
            order: 2
        }
    });
    ds.push({
        data:d3,
        bars: {
            show: true, 
            barWidth: 0.2, 
            order: 3
        }
    });
                
    //tooltip function
    function showTooltip(x, y, contents, areAbsoluteXY) {
        var rootElt = 'body';
	
        $('<div id="tooltip" class="tooltip-with-bg">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            'z-index':'1010',
            top: y,
            left: x,
			border: '1px solid #258dde',
            padding: '2px',
            'background-color': '#ffffff',
        }).prependTo(rootElt).show();
    }
                
    //Display graph
    $.plot($(".bars"), ds, {
        grid:{
            hoverable:true
        }
    });

    //Display horizontal graph
    var d1_h = [];
    for (var i = 0; i <= 5; i += 1)
        d1_h.push([parseInt(Math.random() * 30),i ]);

    var d2_h = [];
    for (var i = 0; i <= 5; i += 1)
        d2_h.push([parseInt(Math.random() * 30),i ]);

    var d3_h = [];
    for (var i = 0; i <= 5; i += 1)
        d3_h.push([ parseInt(Math.random() * 30),i]);
                
    var ds_h = new Array();
    ds_h.push({
        data:d1_h,
        bars: {
            horizontal:true, 
            show: true, 
            barWidth: 0.2, 
            order: 1,
            lineWidth : 2
			
        }
    });
ds_h.push({
    data:d2_h,
    bars: {
        horizontal:true, 
        show: true, 
        barWidth: 0.2, 
        order: 2
    }
});
ds_h.push({
    data:d3_h,
    bars: {
        horizontal:true, 
        show: true, 
        barWidth: 0.2, 
        order: 3
    }
});

 
//add tooltip event
$(".bars").bind("plothover", function (event, pos, item) {
    if (item) {
        if (previousPoint != item.datapoint) {
            previousPoint = item.datapoint;
 
            //delete de precedente tooltip
            $('.tooltip-with-bg').remove();
 
            var x = item.datapoint[0];
 
            //All the bars concerning a same x value must display a tooltip with this value and not the shifted value
            if(item.series.bars.order){
                for(var i=0; i < item.series.data.length; i++){
                    if(item.series.data[i][3] == item.datapoint[0])
                        x = item.series.data[i][0];
                }
            }
 
            var y = item.datapoint[1];
 
            showTooltip(item.pageX+5, item.pageY+5,x + " = " + y);
 
        }
    }
    else {
        $('.tooltip-with-bg').remove();
        previousPoint = null;
    }
 
});
 


/* Pie charts */
	
	$(function () {
		var data = [];
		var series = Math.floor(Math.random()*10)+1;
		for( var i = 0; i<series; i++)
		{
			data[i] = { label: "Series"+(i+1), data: Math.floor(Math.random()*100)+1 }
		}
	
	$.plot($("#graph1"), data, 
	{
			series: {
				pie: { 
					show: true,
					radius: 1,
					label: {
						show: true,
						radius: 2/3,
						formatter: function(label, series){
							return '<div style="font-size:11px;text-align:center;padding:2px;color:white;">'+label+'<br/>'+Math.round(series.percent)+'%</div>';
						},
						threshold: 0.1
					}
				}
			},
			legend: {
				show: false
			},
			grid: {
				hoverable: false,
				clickable: true
			},
	});
	$("#interactive").bind("plothover", pieHover);
	$("#interactive").bind("plotclick", pieClick);
	
	$.plot($("#graph2"), data, 
	{
			series: {
				pie: { 
					show: true,
					radius:300,
					label: {
						show: true,
						formatter: function(label, series){
							return '<div style="font-size:11px;text-align:center;padding:2px;color:white;">'+label+'<br/>'+Math.round(series.percent)+'%</div>';
						},
						threshold: 0.1
					}
				}
			},
			legend: {
				show: false
			},
			grid: {
				hoverable: false,
				clickable: true
			}
	});
	$("#interactive").bind("plothover", pieHover);
	$("#interactive").bind("plotclick", pieClick);
	});
	
	function pieHover(event, pos, obj) 
	{
		if (!obj)
					return;
		percent = parseFloat(obj.series.percent).toFixed(2);
		$("#hover").html('<span style="font-weight: bold; color: '+obj.series.color+'">'+obj.series.label+' ('+percent+'%)</span>');
	}
	function pieClick(event, pos, obj) 
	{
		if (!obj)
					return;
		percent = parseFloat(obj.series.percent).toFixed(2);
		alert(''+obj.series.label+': '+percent+'%');
	}

	
});
//////////////////////
