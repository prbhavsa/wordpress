jQuery(document).ready(function($){$(".pp_group-profile div.pp-group_members, .pp_net_group-profile div.pp-group_members, .permissions_page_pp-edit-permissions div.pp-group_members").show();$("#pp-add-permissions").show();$("#pp_current_roles").show();$("a.pp-show-groups").click(function(){$('#userprofile_groupsdiv_pp').show();return false;});$(".pp-member-type > a").click(function(){$(".pp-member-type > a").parent().removeClass('agp-selected_agent').addClass('agp-unselected_agent');$(this).parent().removeClass('agp-unselected_agent').addClass('agp-selected_agent');$('div.pp-member-type').hide();pp_show_class($(this).attr('class'),$);return false;});var all_roledata=[];var eid=-1;$(".pp-add-permissions > a").click(function(){$(".pp-add-permissions > a").parent().removeClass('agp-selected_agent').addClass('agp-unselected_agent');$(this).parent().removeClass('agp-unselected_agent').addClass('agp-selected_agent');$('div.pp-add-permissions').hide();pp_show_class($(this).attr('class'),$);});$("#pp_save_roles input.button-primary").click(function(){$('input[name="member_csv"]').val($("input#member_csv").val());$('input[name="group_name"]').val($("input#group_name").val());$('input[name="description"]').val($("input#description").val());$("#pp_new_submission_msg").html(ppCred.submissionMsg);$("#pp_new_submission_msg").show();});$('#agent-profile #submit').click(function(e){$('#pp_review_roles').hide();$('#pp_add_role').remove();});$(document).on('click',"#pp_tbl_role_selections .pp_clear",function(e){var eid=$(this).closest('tr').find('input[name="pp_eid[]"]').val();if(typeof all_roledata[eid]!='undefined'){delete all_roledata[eid];}
$(this).closest('tr').remove();e.stopPropagation();});$('#pp_add_site_role').bind('click',function(){$('div.pp-ext-promo').hide();var newrow='',trackdata='',duplicate=false,any_added=false;var conds=$('#pp_cond_ui').find('input[name="pp_select_cond[]"]:checked');if(conds.length==0){$('#pp_site_selection_msg').html(ppCred.noConditions);$('#pp_site_selection_msg').addClass('pp-error-note');$('#pp_site_selection_msg').show();return false;}
$('.pp-save-roles').show();$(conds).each(function(){id=agp_escape_id(this.id);var lbl=$('#pp_add_role label[for="'+id+'"]');trackdata=$('select[name="pp_select_type"]').val()
+'|'+$('select[name="pp_select_role"]').val()
+'|'+$('#'+id).val()
if($.inArray(trackdata,all_roledata)!=-1){duplicate=true;}else{eid++;all_roledata[eid]=trackdata;newrow='<tr>'
+'<td>'+$('select[name="pp_select_type"] option:selected').html()+'</td>'
+'<td>'+$('select[name="pp_select_role"] option:selected').html()+'</td>'
+'<td>'+lbl.html()+'</td>'
+'<td><div class="pp_clear"><a href="javascript:void(0)" class="pp_clear">'+ppCred.clearRole+'</a></div>'
+'<input type="hidden" name="pp_eid[]" value="'+eid+'" />'
+'<input type="hidden" name="pp_add_role['+eid+'][type]" value="'+$('select[name="pp_select_type"]').val()+'" />'
+'<input type="hidden" name="pp_add_role['+eid+'][role]" value="'+$('select[name="pp_select_role"]').val()+'" />'
+'<input type="hidden" name="pp_add_role['+eid+'][attrib_cond]" value="'+$('#'+id).val()+'" />';+'</td>'
+'</tr>';$('#pp_tbl_role_selections tbody').append(newrow);any_added=true;}});if(duplicate&&!any_added){$('#pp_site_selection_msg').html(ppCred.alreadyRole);$('#pp_site_selection_msg').addClass('pp-error-note');$('#pp_site_selection_msg').show();}else{$('#pp_site_selection_msg').html(ppCred.pleaseReview);$('#pp_site_selection_msg').removeClass('pp-error-note');$('#pp_site_selection_msg').show();}
return false;});var reload_roles=function(){if($('select[name="pp_select_type"]').val())
ajax_ui('get_role_options',draw_roles);else
$('.pp-select-role').hide();}
var reload_conditions=function(){if($('select[name="pp_select_type"]').val()&&$('select[name="pp_select_role"]').val())
ajax_ui('get_conditions_ui',draw_conditions);else
$('.pp-select-cond').hide();}
$('select[name="pp_select_role"]').bind('change',reload_conditions);$('select[name="pp_select_type"]').change(function(){$('#pp_add_role .postbox').hide();if($(this).val()=='site'){$('.pp-add-site-role').show();}
reload_roles();});var draw_roles=function(data,txtStatus){sel=$('select[name="pp_select_role"]');sel.html(data);sel.triggerHandler('change');if(sel.children().length){$('.pp-select-role').show();$('.pp-add-site-role').show();}else{$('.pp-select-role').hide();$('.pp-add-site-role').hide();}
ajax_ui_done();}
var draw_conditions=function(data,txtStatus){dv=$('#pp_cond_ui');dv.html(data);if(dv.children().length>1)
$('.pp-select-cond').show();else
$('.pp-select-cond').hide();if($('.pp-select-cond input:checkbox').length==1){$('.pp-select-cond input:checkbox').prop('checked',true);}
ajax_ui_done();}
var ajax_ui=function(op,handler,item_id){$('#pp_add_role select').prop('disabled',true);$('#pp_add_role_waiting').show();if(typeof item_id=='undefined')
item_id=0;var data={'pp_ajax_ui':op,'pp_src_name':'post','pp_object_type':$('select[name="pp_select_type"]').val(),'pp_role_name':$('select[name="pp_select_role"]').val(),'pp_item_id':item_id};$.ajax({url:ppCred.ajaxurl,data:data,dataType:"html",success:handler,error:ajax_ui_failure});}
var ajax_ui_done=function(){$('#pp_add_role select').prop('disabled',false);$('#pp_add_role_waiting').hide();}
var ajax_ui_failure=function(data,txtStatus){$('#pp_add_role .waiting').hide();return;}
$('#pp_current_roles input').click(function(e){$('div.pp-role-bulk-edit').show();});$('#pp_current_roles .pp_check_all').click(function(e){$(this).closest('td').find('input[name="pp_edit_role[]"][disabled!="true"]').prop('checked',$(this).is(':checked'));});var current_roles_ajax_done=function(){$('#pp_current_roles input.submit-edit-item-role').prop('disabled',false);$('#pp_current_roles .waiting').hide();}
var remove_roles_done=function(data,txtStatus){current_roles_ajax_done();if(!data)
return;var startpos=data.indexOf('<!--ppResponse-->');var endpos=data.indexOf('<--ppResponse-->');if((startpos==-1)||(endpos<=startpos))
return;data=data.substr(startpos+17,endpos-startpos-17);var deleted_ass_ids=data.split('|');$.each(deleted_ass_ids,function(index,value){cbid=$('#pp_current_roles input[name="pp_edit_role[]"][value="'+value+'"]').attr('id');$('#'+cbid).closest('label').remove();});}
$('#pp_current_roles input.submit-edit-item-role').click(function(e){var action=$('div.pp-role-bulk-edit select').val();if(!action){alert(ppCred.noAction);return false;}
var selected_ids=new Array();$('#pp_current_roles').find('input[type="checkbox"]:checked').each(function(){selected_ids.push($(this).attr('value'));});$(this).prop('disabled',true);$(this).closest('div').find('.waiting').show();switch(action){case'remove':ajax_submit('roles_remove',remove_roles_done,selected_ids.join('|'));break
default:break}
return false;});var ajax_submit=function(op,handler,ass_ids){if(!ass_ids)
return;var data={'pp_ajax_submission':op,'agent_type':ppCred.agentType,'agent_id':ppCred.agentID,'pp_ass_ids':ass_ids};$.ajax({url:ppCred.ajaxurl,data:data,dataType:"html",success:handler,error:ajax_submit_failure});}
var ajax_submit_failure=function(data,txtStatus){return;}});