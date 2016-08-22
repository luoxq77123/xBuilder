/**
 * @author Roger Wu
 * @version 1.0
 * added extend property oncheck
 */
 (function($){
 	$.extend($.fn, {
		jTree:function(options) {
			var op = $.extend({checkFn:null, selected:"selected", exp:"expandable", coll:"collapsable", firstExp:"first_expandable", firstColl:"first_collapsable", lastExp:"last_expandable", lastColl:"last_collapsable", folderExp:"folder_expandable", folderColl:"folder_collapsable", endExp:"end_expandable", endColl:"end_collapsable",file:"file",ck:"checked", unck:"unchecked"}, options);
			return this.each(function(){
				var $this = $(this);
				var cnum = $this.children().length;
				$(">li", $this).each(function(){
					var $li = $(this);
					
					var first = $li.prev()[0]?false:true;
					var last = $li.next()[0]?false:true; 
					$li.genTree({
						icon:$this.hasClass("treeFolder"),
						ckbox:$this.hasClass("treeCheck"),
						options: op,
						level: 0,
						exp:(cnum>1?(first?op.firstExp:(last?op.lastExp:op.exp)):op.endExp),
						coll:(cnum>1?(first?op.firstColl:(last?op.lastColl:op.coll)):op.endColl),
						showSub:(!$this.hasClass("collapse") && ($this.hasClass("expand") || (cnum>1?(first?true:false):true))),
						isLast:(cnum>1?(last?true:false):true)
					});
				});
				setTimeout(function(){
					if($this.hasClass("treeCheck")){
						var checkFn = eval($this.attr("oncheck"));
						if(checkFn && $.isFunction(checkFn)) {
							$("div.ckbox", $this).each(function(){
								var ckbox = $(this);
								ckbox.click(function(){
									var checked = $(ckbox).hasClass("checked");
									var items = [];
									if(checked){
										var tnode = $(ckbox).parent().parent();
										var boxes = $("input", tnode);
										if(boxes.size() > 1) {
											$(boxes).each(function(){
												items[items.length] = {name:$(this).attr("name"), value:$(this).val(), text:$(this).attr("text")};
											});
										} else {
											items = {name:boxes.attr("name"), value:boxes.val(), text:boxes.attr("text")};
										}		
									}								
									checkFn({checked:checked, items:items});														
								});
							});
						}
					}
					$("a", $this).click(function(event){
						$("div." + op.selected, $this).removeClass(op.selected);
						$("a").removeClass('uploadSelect');
						var parent = $(this).parent().addClass(op.selected);
						$(".ckbox",parent).trigger("click");
						event.stopPropagation();
						$(document).trigger("click");
						if (!$(this).attr("target")) return false;
						$("div.more",$this).removeClass(op.selected);
						if($("div." + op.selected, $this).parent().parent().attr("id") == 'videoTree')
						{
							//alert($("div." + op.selected, $this).parent().html());
						}
					});
					
					//角色权限，分类权限处使用
					$("span", $this).parent().click(function(event){
						
						$("div." + op.selected, $this).removeClass(op.selected);
						var parent = $(this).addClass(op.selected);
						var sTarget = $(this).find("span").attr("target");
						var sValue = $(this).find("span").attr("tvalue");
						
						if (sTarget)
						{
							if ($("#"+sTarget, $('#editCategoryPremission').parent()).size() == 0)
							{
								$('#editCategoryPremission').parent().prepend('<input id="'+sTarget+'" type="hidden" name="data[cid]" />');
							}
							$("#"+sTarget, $('#editCategoryPremission').parent()).val(sValue);
							$("#permissionSubmit").css('display','block');
							$("div.role_category_tag_name").html($(this).html()+"权限设置");
						}

						$.ajax({
							type:'GET',
							url:'Roles/getCategoryPermissions/'+$(this).find("span").attr("tvalue")+'/'+$("input[name='data[id]']").val(),
							data:"",
							dataType:'script',
							cache:false,
							success:function(data){
								if(data == '')
								{
									$("#s1").removeClass("s1");
									$("#s1").addClass("move_s1");
									$("#s2").removeClass("s2");
									$("#s2").addClass("move_s2");
									
									$("#s4").removeClass("s4");
									$("#s4").addClass("move_s4");
									$("#s5").removeClass("s5");
									$("#s5").addClass("move_s5");
									$("#s6").removeClass("s6");
									$("#s6").addClass("move_s6");
									return false;
								}
								//权限：1浏览，2视频处理，3对外下载，4创建子分类，5删除分类，6编辑分类
								var arrPermissions = data.split(',');
								//浏览权限
								if($.inArray('1',arrPermissions) == -1)
								{
									$("#s1").removeClass("s1");
									$("#s1").addClass("move_s1");
								}else
								{
									$("#s1").removeClass("move_s1");
									$("#s1").addClass("s1");
								}
								//视频处理权限
								if($.inArray('2',arrPermissions) == -1)
								{
									$("#s2").removeClass("s2");
									$("#s2").addClass("move_s2");
								}else
								{
									$("#s2").removeClass("move_s2");
									$("#s2").addClass("s2");
								}
								//创建子分类权限
								if($.inArray('4',arrPermissions) == -1)
								{
									$("#s4").removeClass("s4");
									$("#s4").addClass("move_s4");
								}else
								{
									$("#s4").removeClass("move_s4");
									$("#s4").addClass("s4");
								}
								//删除分类权限
								if($.inArray('5',arrPermissions) == -1)
								{
									$("#s5").removeClass("s5");
									$("#s5").addClass("move_s5");
								}else
								{
									$("#s5").removeClass("move_s5");
									$("#s5").addClass("s5");
								}
								//编辑分类权限
								if($.inArray('6',arrPermissions) == -1)
								{
									$("#s6").removeClass("s6");
									$("#s6").addClass("move_s6");
								}else
								{
									$("#s6").removeClass("move_s6");
									$("#s6").addClass("s6");
								}
							},
							error: DWZ.ajaxError
						});
						//$("input[statusId='categoryPermissions"+sValue+"']").attr('disabled',false);
						//$("input[statusId!='categoryPermissions"+sValue+"']").attr('disabled',true);
						$("input[name='data[id]']").attr('disabled', false);
						$("input[name='data[cid]']").attr('disabled', false);
						$(".ckbox",parent).trigger("click");
						event.stopPropagation();
						$(document).trigger("click");
						if (!$(this).find("span").attr("target")) return false;
					});
					
				},1);
			});
		},
		subTree:function(op, level) {
			return this.each(function(){
				$(">li", this).each(function(){
					var $this = $(this);
					
					var isLast = ($this.next()[0]?false:true);
					$this.genTree({
						icon:op.icon,
						ckbox:op.ckbox,
						exp:isLast?op.options.lastExp:op.options.exp,
						coll:isLast?op.options.lastColl:op.options.coll,
						options:op.options,
						level:level,
						space:isLast?null:op.space,
						showSub:op.showSub,
						isLast:isLast
					});
				});
			});
		},
		genTree:function(options) {
			var op = $.extend({icon:options.icon,ckbox:options.ckbox,exp:"", coll:"", showSub:false, level:0, options:null, isLast:false}, options);
			return this.each(function(){
				var node = $(this);
				var tree = $(">ul", node);
				var parent = node.parent().prev();
				var checked = 'unchecked';
				var cid = null;
				var addurl = null;
				var editurl = null;
				var deleteurl = null;
				
				if(op.ckbox) {
					if($(">.checked",parent).size() > 0) checked = 'checked';
				}

				if (tree.size()>0) {
					node.children(":first").wrap("<div></div>");
					//大类的折叠图标
					$(">div", node).prepend("<div class='" + (op.showSub ? op.coll : op.exp) + "'></div>"+(op.ckbox ?"<div class='ckbox " + checked + "'></div>":"")+(op.icon?"<div class='"+ (op.showSub ? op.options.folderColl : op.options.folderExp) +"'></div>":""));
					//$(">div", node).prepend("<div class='" + (op.exp) + "'></div>"+(op.ckbox ?"<div class='ckbox " + checked + "'></div>":"")+(op.icon?"<div class='"+ (op.showSub ? op.options.folderColl : op.options.folderExp) +"'></div>":""));
					//获取分类ID，操作动作的属性
					
					cid = $(">div>a", node).attr('categoryid');
					addurl = $(">div>a", node).attr('addurl');
					deleteurl = $(">div>a", node).attr('deleteurl');
					editurl = $(">div>a", node).attr('editurl');

					//自定义弹出框的宽高 20141110 by lh
					var customHeight = $(">div>a", node).attr('height');
					var customWidth = $(">div>a", node).attr('width');
					customHeight = (customHeight==undefined)?'':'height="' +customHeight + '"';
					customWidth = (customWidth==undefined)?'':'width="' +customWidth + '"';

					var controllerHtml = "";
					if(deleteurl != undefined){
						controllerHtml += "<a href='"+deleteurl+'/'+cid+"' class='deletes' target='ajaxTodo' title='确定要删除吗?'></a>";
					}

					if(addurl != undefined){
						controllerHtml += "<a href='"+addurl+'/'+cid+"' class='adds' target='dialog' mask='true' maxable='false' title='添加'></a>";
					}

					if(editurl != undefined){
						controllerHtml += "<a "+customWidth+" "+customHeight+" href='"+editurl+'/'+cid+"' class='updates' target='dialog' mask='true' maxable='false' title='修改'></a>";
					}
					
					$(">div", node).append("<div class='more'>"+controllerHtml+"</div>");
					//op.showSub ? tree.show() : tree.hide();
					tree.hide();
					$(">div>div:first,>div>a", node).click(function(){
						var $fnode = $(">li:first",tree);
						if($fnode.children(":first").isTag('a')) tree.subTree(op, op.level + 1);
						var $this = $(this);
						var isA = $this.isTag('a');
						var $this = isA?$(">div>div", node).eq(op.level):$this;
						if (!isA || tree.is(":hidden")) {
							$this.toggleClass(op.exp).toggleClass(op.coll);
							if (op.icon) {
								$(">div>div:last", node).toggleClass(op.options.folderExp).toggleClass(op.options.folderColl);
							}
						}
						(tree.is(":hidden"))?tree.slideDown("fast"):(isA?"":tree.slideUp("fast"));
						return false;
					});
					addSpace(op.level, node);
					if(op.showSub) tree.subTree(op, op.level + 1);
				}else{
					if(node.hasClass("selected")){
						node.removeClass("selected");
						node.children().wrap("<div class='selected'></div>");
					}else{
						node.children().wrap("<div></div>");
					}
					
					$(">div", node).prepend("<div class='node'></div>"+(op.ckbox?"<div class='ckbox "+checked+"'></div>":"")+(op.icon?"<div class='file'></div>":""));
					cid = $(">div>a", node).attr('categoryid');
					addurl = $(">div>a", node).attr('addurl');
					deleteurl = $(">div>a", node).attr('deleteurl');
					editurl = $(">div>a", node).attr('editurl');

					//自定义弹出框的宽高 20141110 by lh
					var customHeight = $(">div>a", node).attr('height');
					var customWidth = $(">div>a", node).attr('width');
					customHeight = (customHeight==undefined)?'':'height="' +customHeight + '"';
					customWidth = (customWidth==undefined)?'':'width="' +customWidth + '"';

					if(deleteurl != undefined && editurl != undefined)
					{
						if(addurl == undefined)
						{
							$(">div", node).append("<div class='more'><a href='"+deleteurl+'/'+cid+"' class='deletes' target='ajaxTodo' title='确定要删除吗?'></a><a "+customWidth+" "+customHeight+" href='"+editurl+'/'+cid+"' class='updates' target='dialog' mask='true' maxable='false' title='修改'></a></div>");
						}else
						{
							$(">div", node).append("<div class='more'><a href='"+deleteurl+'/'+cid+"' class='deletes' target='ajaxTodo' title='确定要删除吗?'></a><a href='"+addurl+'/'+cid+"' class='adds' target='dialog' mask='true' maxable='false' title='添加'></a><a href='"+editurl+'/'+cid+"' class='updates' target='dialog' mask='true' maxable='false' title='修改'></a></div>");
						}
					}
					/*if(deleteurl != undefined && editurl != undefined){
						if(op.level == 0){
							$(">div", node).append("<div class='more'><a href='"+deleteurl+'/'+cid+"' class='deletes' target='ajaxTodo' title='确定要删除吗?'></a><a href='"+addurl+'/'+cid+"' class='adds' target='dialog' mask='true' maxable='false' title='添加'></a><a href='"+editurl+'/'+cid+"' class='updates' target='dialog' mask='true' maxable='false' title='修改'></a></div>");
						}else{
							$(">div", node).append("<div class='more'><a href='"+deleteurl+'/'+cid+"' class='deletes' target='ajaxTodo' title='确定要删除吗?'></a><a href='"+editurl+'/'+cid+"' class='updates' target='dialog' mask='true' maxable='false' title='修改'></a></div>");
						}
					}*/
					addSpace(op.level, node);
					if(op.isLast)$(node).addClass("last");
				}
				if (op.ckbox) node._check(op);
				$(">div",node).mouseover(function(){
					$(this).addClass("hover");
				}).mouseout(function(){
					$(this).removeClass("hover");
				});
				if($.browser.msie)
					$(">div",node).click(function(){
						//$("a", this).trigger("click");
						return false;
					});
			});
			function addSpace(level,node) {
				if (level > 0) {					
					var parent = node.parent().parent();
					var space = !parent.next()[0]?"indent":"line";
					var plist = "<div class='" + space + "'></div>";
					if (level > 1) {
						var next = $(">div>div", parent).filter(":first");
						var prev = "";
						while(level > 1){
							prev = prev + "<div class='" + next.attr("class") + "'></div>";
							next = next.next();
							level--;
						}
						plist = prev + plist;
					}
					$(">div", node).prepend(plist);
				}
			}
		},
		_check:function(op) {
			var node = $(this);
			var ckbox = $(">div>.ckbox", node);
			var $input = node.find("a");
			var tname = $input.attr("tname"), tvalue = $input.attr("tvalue");
			var attrs = "text='"+$input.text()+"' ";
			if (tname) attrs += "name='"+tname+"' ";
			if (tvalue) attrs += "value='"+tvalue+"' ";
			
			ckbox.append("<input type='checkbox' style='display:none;' " + attrs + "/>").click(function(){
				var cked = ckbox.hasClass("checked");
				var aClass = cked?"unchecked":"checked";
				var rClass = cked?"checked":"unchecked";
				ckbox.removeClass(rClass).removeClass(!cked?"indeterminate":"").addClass(aClass);
				$("input", ckbox).attr("checked", !cked);
				$(">ul", node).find("li").each(function(){
					var box = $("div.ckbox", this);
					box.removeClass(rClass).removeClass(!cked?"indeterminate":"").addClass(aClass)
					   .find("input").attr("checked", !cked);
				});
				$(node)._checkParent();
				return false;
			});
			var cAttr = $input.attr("checked") || false;
			if (cAttr) {
				ckbox.find("input").attr("checked", true);
				ckbox.removeClass("unchecked").addClass("checked");
				$(node)._checkParent();
			}
		},
		_checkParent:function(){
			if($(this).parent().hasClass("tree")) return;
			var parent = $(this).parent().parent();
			var stree = $(">ul", parent);
			var ckbox = stree.find(">li>a").size()+stree.find("div.ckbox").size();
			var ckboxed = stree.find("div.checked").size();
			var aClass = (ckboxed==ckbox?"checked":(ckboxed!=0?"indeterminate":"unchecked"));
			var rClass = (ckboxed==ckbox?"indeterminate":(ckboxed!=0?"checked":"indeterminate"));
			$(">div>.ckbox", parent).removeClass("unchecked").removeClass("checked").removeClass(rClass).addClass(aClass);
			parent._checkParent();
		}
	});
})(jQuery);