humhub.module('bbd', function (module, require, $) {
	var client = require('client');

	var init = function () {
		// set niceScroll to bbd
		$(".panel-bbd-widget .bbd-body .scrollable-content-container").niceScroll({
			cursorwidth: "7",
			cursorborder:"",
			cursorcolor:"#555",
			cursoropacitymax:"0.2",
			railpadding:{top:0,right:3,left:0,bottom:0}
		});
		$(".panel-bbd-widget .bbd-body .scrollable-content-container").getNiceScroll().resize();

		$(".toggle-view-mode a").on("click", function(e) {
			e.preventDefault();
			if(jQuery(this).data('enabled')) {
				jQuery(this).data('enabled', false);
				$(".bbd-editable").hide();
				$(".bbd-floors").sortable('disable');
				$(".bbd-businesses").sortable('disable');
			}
			else {
				jQuery(this).data('enabled', true);
				$(".bbd-editable").show();
				$(".bbd-floors").sortable('enable');
				$(".bbd-businesses").sortable('enable');
			}
		});
	}

	var removeFloor = function(event) {
		client.post(event);

		const floorId = $(event.$target).data('floor_id');

		$("#bbd-floor_" + floorId).remove();
		$("#bbd-widget-floor_" + floorId).remove();
		if($(".panel-bbd-widget").find(".media").length == 0) {
			$(".panel-bbd-widget").remove();
		}
	}

	var removeBusiness = function(event) {

				
		client.post(event);

		const businessId = $(event.$target).data('business_id');
		const floorId = $(event.$target).data('floor_id');

		$("#bbd-business_" + businessId).remove();
		$("#bbd-widget-business_" + businessId).remove();
		if($("#bbd-widget-floor_" + floorId).find("li").length == 0) {
			$("#bbd-widget-floor_" + floorId).remove();
		}
		if($(".panel-bbd-widget").find(".media").length == 0) {
			$(".panel-bbd-widget").remove();
		}
	}
	var approveBusiness = function(event) {
		client.post(event);
		const businessId = $(event.$target).data('business_id');
		$("#bbd-business_" + businessId).find("a.span").addClass('title');
		$("#bbd-business_" + businessId).find("li").hide();
	 
	
	}

	
	var upapproveBusiness = function(event) {
		client.post(event);
		const businessId = $(event.$target).data('business_id');
		
		$("#bbd-business_" + businessId).find("a.span").removeClass('title');
		$("#bbd-business_" + businessId).find("li").show();
	
	
	}

	module.export({
		removeFloor: removeFloor,
		removeBusiness: removeBusiness,
		approveBusiness: approveBusiness,
		upapproveBusiness: upapproveBusiness,
		init,
		initOnPjaxLoad: true,
	});
});
