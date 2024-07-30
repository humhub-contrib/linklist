humhub.module('linklist', function (module, require, $) {
	var client = require('client');

	var init = function () {
		// set niceScroll to linklist
		$(".panel-linklist-widget .linklist-body .scrollable-content-container").niceScroll({
			cursorwidth: "7",
			cursorborder:"",
			cursorcolor:"#555",
			cursoropacitymax:"0.2",
			railpadding:{top:0,right:3,left:0,bottom:0}
		});
		$(".panel-linklist-widget .linklist-body .scrollable-content-container").getNiceScroll().resize();

		$(".toggle-view-mode a").on("click", function(e) {
			e.preventDefault();
			if(jQuery(this).data('enabled')) {
				jQuery(this).data('enabled', false);
				$(".linklist-editable").hide();
				$(".linklist-categories").sortable('disable');
				$(".linklist-links").sortable('disable');
			}
			else {
				jQuery(this).data('enabled', true);
				$(".linklist-editable").show();
				$(".linklist-categories").sortable('enable');
				$(".linklist-links").sortable('enable');
			}
		});
	}

	var removeCategory = function(event) {
		client.post(event);

		const categoryId = $(event.$target).data('category_id');

		$("#linklist-category_" + categoryId).remove();
		$("#linklist-widget-category_" + categoryId).remove();
		if($(".panel-linklist-widget").find(".media").length == 0) {
			$(".panel-linklist-widget").remove();
		}
	}

	var removeLink = function(event) {
		client.post(event);

		const linkId = $(event.$target).data('link_id');
		const categoryId = $(event.$target).data('category_id');

		$("#linklist-link_" + linkId).remove();
		$("#linklist-widget-link_" + linkId).remove();
		if($("#linklist-widget-category_" + categoryId).find("li").length == 0) {
			$("#linklist-widget-category_" + categoryId).remove();
		}
		if($(".panel-linklist-widget").find(".media").length == 0) {
			$(".panel-linklist-widget").remove();
		}
	}

	module.export({
		removeCategory: removeCategory,
		removeLink: removeLink,
		init,
		initOnPjaxLoad: true,
	});
});
