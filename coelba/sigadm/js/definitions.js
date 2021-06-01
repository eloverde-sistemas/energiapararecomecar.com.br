jQuery(document).ready(function() {

//	Coloca a flexinha nos sub-menus
	$( ".page-sidebar-menu-hover-submenu ul.sub-menu" ).siblings('a').each(function(){
		$(this).append('<i class="fa fa-angle-right right hidden-sm"></i>');
	});
	
	$('.date-picker').datepicker({
		rtl: Metronic.isRTL(),
		orientation: "left",
		autoclose: true
	});
	
//	Cheat barra de rolagem modal
	$('body').on('show.bs.modal', '.modal', function() {
		$('body').addClass("modal-open-noscroll");
	}).on('hide.bs.modal', '.modal', function() {
		$('body').removeClass("modal-open-noscroll");
	});

	if($.fn.timepicker){
	
		$('.timepicker-24').timepicker({
			autoclose: true,
			minuteStep: 5,
			showSeconds: false,
			showMeridian: false
		});
		// handle input group button click
		$('.timepicker').parent('.input-group').on('click', '.input-group-btn', function(e){
			e.preventDefault();
			$(this).parent('.input-group').find('.timepicker').timepicker('showWidget');
		});
	}
	
	if ($('.wysihtml5').size() > 0) {
		$('.wysihtml5').wysihtml5({
			"stylesheets": ["metronic/assets/global/plugins/bootstrap-wysihtml5/wysiwyg-color.css"]
		});
	}
	
	$('.seta, .void').click(function(event){
		event.preventDefault ? event.preventDefault() : event.returnValue = false;
	});
	
	if($.fn.DataTable){
		$.extend(true, $.fn.DataTable.TableTools.classes, {
			"container": "btn-group tabletools-btn-group pull-right",
			"buttons": {
				"normal": "btn btn-sm default",
				"disabled": "btn btn-sm default disabled"
			}
		});
/*	
	//	Ordenação por data 'dd/mm/aaaa'
		$.fn.dataTable.moment = function ( format, locale ) {
			var types = $.fn.dataTable.ext.type;
		 
			// Add type detection
			types.detect.unshift( function ( d ) {
				return moment( d, format, locale, true ).isValid() ?
					'moment-'+format :
					null;
			} );
		 
			// Add sorting method - use an integer for the sorting
			types.order[ 'moment-'+format+'-pre' ] = function ( d ) {
				return moment( d, format, locale, true ).unix();
			};
		};
*/
	}
});