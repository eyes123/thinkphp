// JavaScript Document

$(document).ready(function(){
	
	var _expand = $("input[name ^= 'expand_']");//展开 闭合按钮
	var _edit = $("input[name ^= 'edit_']");//编辑按钮				
	var _save = $("input[name ^= 'save_']");//保存按钮				
	var _del = $("input[name ^= 'del_']");//删除按钮
	var _add = $("input[name ^= 'add_']");//添加按钮
	var _addroot = $("input[name = 'addroot']");//添加按钮
	var html = "<li class='node'>";
		html += "<ul>";
		html += "<li class='node'>";
		html += "<ul>";
		html += "<li class='item'>";
		html += "<input id='expand_0' name='expand_' type='button' value='' />";
		html += "</li>";
		html += "<li class='item'>";
		html += "<input id='name_' name='name_' type='text' value='' />";
		html += "</li>";
		html += "<li class='item'>";
		html += "<input id='order_no_' name='order_no_' type='text' value='0' />";
		html += "</li>";
		html += "<li class='item'>";
		html += "<input id='path_' name='path_' type='text' value='' />";
		html += "</li>";
		html += "<li class='item'>";
		html += "<input id='save_' onclick='saveNode()' name='save_' type='button' value='' />";
		html += "<input id='del_' onclick='delNode(this)' name='del_' type='button' value='' />";
		html += "</li>";
		html += "</ul>";
		html += "</li>";
		html += "</ul>";
		html += "</li>";	

	var htmlroot = "<li class='node'>";
		htmlroot += "<ul>";
		htmlroot += "<li class='item'>";
		htmlroot += "<input id='expand_0' name='expand_' type='button' value='' />";
		htmlroot += "</li>";
		htmlroot += "<li class='item'>";
		htmlroot += "<input id='name_' name='name_' type='text' value='' />";
		htmlroot += "</li>";
		htmlroot += "<li class='item'>";
		htmlroot += "<input id='order_no_' name='order_no_' type='text' value='0' />";
		htmlroot += "</li>";
		htmlroot += "<li class='item'>";
		htmlroot += "<input id='path_' name='path_' type='text' value='' />";
		htmlroot += "</li>";
		htmlroot += "<li class='item'>";
		htmlroot += "<input id='save_' onclick='saveNode()' name='save_' type='button' value='' />";
		htmlroot += "<input id='del_' onclick='delNode(this)' name='del_' type='button' value='' />";
		htmlroot += "</li>";
		htmlroot += "</ul>";
		htmlroot += "</li>";
			
	_expand.click(function(){
			//expand_0 展开
			//expand_1 闭合
			if($(this).attr('id') == 'expand_0')//如果是展开的 
			{
				$(this).attr('id','expand_1');
				$(this).parent().parent().children("li[class='node']").hide();
			}
			else 
			{
				$(this).attr('id','expand_0');
				$(this).parent().parent().children("li[class='node']").show();
			}
		});

	_edit.click(function(){
			$('#hf_id').val($(this).attr('id'));
			window.location = window.location.href+'/function_opt/fi/'+$(this).attr('id');
//			$("form").attr('method','get');
////			$("form").attr('action',window.location.href.substring(0,window.location.href.length-12)+'system_treeinfo/');
//			$("form").attr('action',window.location.href+'/function_opt');
//			$("form").submit();
		});	
				
	_save.click(function(){
			$('#hf_id').val($(this).attr('id'));
			$("form").attr('action',window.location.href+'/save');
			$("form").submit();
		});

	_add.click(function(){
			if($('#hf_id').val() != '') //如果已经点击了一次添加动作
			{
				art.dialog({
					title:'提示：',
					content:'请先保存您已添加的节点。',
					time:3000
				});
				return;
			}
			$(this).parent().after(html);
			$('#hf_id').val($(this).attr('id'));
			$(this).attr("class","add_0");
			$(this).attr("disabled","disabled");
		});
		
	_addroot.click(function(){
			if($('#hf_id').val() != '') //如果已经点击了一次添加动作
			{
				art.dialog({
					title:'提示：',
					content:'请先保存您已添加的节点。',
					time:3000
				});
				return;
			}
			$('#hf_id').val(0);
			$('form>ul').append(htmlroot);
			$(this).attr("disabled","disabled");
		});
			
	_del.click(function(){
			$('#hf_id').val($(this).attr('id'));
			art.dialog({
				title: '提示：',
				content: '您确定要删除吗？',
				okValue:'确定',
				ok:function(){
					$("form").attr('action',window.location.href+'/delete');			
					$("form").submit();
				},
				cancelValue:'取消',
				cancel:true
			});
		});	
});

//新添加的节点删除操作
function delNode(obj){
		$('#addroot').removeAttr("disabled");//启用添加
		$('.add_0').removeAttr("disabled");//启用添加
		$('.add_0').attr("class","add_1");//更改图片
		$('#hf_id').val('');//移除隐藏字段值
		$(obj).closest("ul>li[class='node']").remove();
	}
//保存新添加的节点
function saveNode(){
		$("form").attr('action',window.location.href+'/insert');
		$("form").submit();
	}