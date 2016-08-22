/**
 * @author ZhangHuihua@msn.com
 * 
 */
(function($){
	$.fn.extend({

		/**
		 * options: reverse[true, false], eventType[click, hover], currentIndex[default index 0]
		 * 			stTab[tabs selector], stTabPanel[tab panel selector]
		 * 			ajaxClass[ajax load], closeClass[close tab]
		 */ 
		tabs: function (options){
			var op = $.extend({reverse:false, eventType:"click", currentIndex:0, stTabHeader:"> .tabsHeader", stTab:">.tabsHeaderContent>ul", stTabPanel:"> .tabsContent", ajaxClass:"j-ajax", closeClass:"close", prevClass:"tabsLeft", nextClass:"tabsRight"}, options);
			return this.each(function(){
				initTab($(this), op);
			});
		},
		tabs_add: function(options){
			var op = $.extend({reverse:false, eventType:"click", currentIndex:-1, stTabHeader:"> .tabsHeader", stTab:">.tabsHeaderContent>ul", stTabPanel:"> .tabsContent", ajaxClass:"j-ajax", closeClass:"close", prevClass:"tabsLeft", nextClass:"tabsRight"}, options);
			$(this).click(function(){
				var liStyle = $('.tabsHeaderContent').find('li[class*=selected]').find("span").attr('style');
				if(liStyle)
				{
					//alertMsg.error('请先保存前一个子模板再增加新的子模板！');
					//return false;
				}
				var numLi = $('.tabsHeaderContent > ul > li').length;
				if(numLi > 4){
					alertMsg.error('不能继续添加，只能有五个子模板！');
					return false;
				}
				var jT = $(this).parents("div.tabs");
				var jTabContent = jT.find(".tabsContent");
				jT.find(".tabsHeaderContent>ul").append("<li><a href='javascript:;'><span style='color:#ff0000;'>new</span></a></li>");
				jTabContent.append("<div></div>");
				$("div:last",jTabContent).loadUrl($(this).attr("rel"));
				initTab(jT, op);
			});
		}
	});
	function initTab(jT,op){
		var jSelector = jT.add($("> *", jT));
		var jTabHeader = $(op.stTabHeader, jSelector);
		var jTabs = $(op.stTab + " li", jTabHeader);
		var jGroups = $(op.stTabPanel + " > *", jSelector);

		jTabs.unbind().find("a").unbind();
		jTabHeader.find("."+op.prevClass).unbind();
		jTabHeader.find("."+op.nextClass).unbind();
		
		jTabs.each(function(iTabIndex){
			if (op.currentIndex == iTabIndex) $(this).addClass("selected");
			else $(this).removeClass("selected");
			
			if (op.eventType == "hover") $(this).hover(function(event){switchTab(jT, iTabIndex, op)});
			else $(this).click(function(event){switchTab(jT, iTabIndex, op)});

			$("a", this).each(function(){
				if ($(this).hasClass(op.ajaxClass)) {
					$(this).click(function(event){
						var jGroup = jGroups.eq(iTabIndex);
						if (this.href) jGroup.loadUrl(this.href,{},function(){
							jGroup.find("[layoutH]").layoutH();
						});
						event.preventDefault();
					});
					if (op.currentIndex == iTabIndex) { $(this).trigger("click"); }
					
				} else if ($(this).hasClass(op.closeClass)) {
					$(this).click(function(event){
						jTabs.eq(iTabIndex).remove();
						jGroups.eq(iTabIndex).remove();
						if (iTabIndex == op.currentIndex) {
							op.currentIndex = (iTabIndex+1 < jTabs.size()) ? iTabIndex : iTabIndex - 1;
						} else if (iTabIndex < op.currentIndex){
							op.currentIndex = iTabIndex;
						}
						initTab(jT);
						return false;
					});
				}
			});
		});

		switchTab(jT, op.currentIndex, op);
	}
	
	function switchTab(jT, iTabIndex, op){
		var jSelector = jT.add($("> *", jT));
		var jTabHeader = $(op.stTabHeader, jSelector);
		var jTabs = $(op.stTab + " li", jTabHeader);
		var jGroups = $(op.stTabPanel + " > *", jSelector);
		
		var jTab = jTabs.eq(iTabIndex);
		var jGroup = jGroups.eq(iTabIndex);
		if (op.reverse && (jTab.hasClass("selected") )) {
			jTabs.removeClass("selected");
			jGroups.hide();
		} else {
			op.currentIndex = iTabIndex;
			jTabs.removeClass("selected");
			jTab.addClass("selected");
			
			jGroups.hide().eq(op.currentIndex).show();
		}
		
		if (!jGroup.attr("inited")){
			jGroup.attr("inited", 1000).find("input[type=text]").filter("[alt]").inputAlert();
		}
	}
})(jQuery);