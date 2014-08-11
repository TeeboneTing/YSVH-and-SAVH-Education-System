$(document).ready(function(){
$.datepicker.regional['zh-TW']={
   dayNames:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"],
   dayNamesMin:["日","一","二","三","四","五","六"],
   monthNames:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
   monthNamesShort:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],
   prevText:"上",
   nextText:"下",
   weekHeader:"週"
};
$.datepicker.setDefaults($.datepicker.regional["zh-TW"]);
$("#datepicker1").datepicker({dateFormat:"yymmdd",
	onSelect: function(dateText, inst) {
		dateText = dateText-19110000;
		dateText = dateText.toString();
		dateText = dateText.slice(0,3)+"/"+dateText.slice(3,5)+"/"+dateText.slice(5,7); 
		$(this).val(dateText);
	},    
	showMonthAfterYear:true});
$("#datepicker2").datepicker({dateFormat:"yymmdd",
	onSelect: function(dateText, inst) {
		dateText = dateText-19110000;
		dateText = dateText.toString();
		dateText = dateText.slice(0,3)+"/"+dateText.slice(3,5)+"/"+dateText.slice(5,7); 
		$(this).val(dateText);
	},    
	showMonthAfterYear:true});
$("#datepicker3").datepicker({dateFormat:"yymmdd",
	onSelect: function(dateText, inst) {
		dateText = dateText-19110000;
		dateText = dateText.toString();
		dateText = dateText.slice(0,3)+"/"+dateText.slice(3,5)+"/"+dateText.slice(5,7); 
		$(this).val(dateText);
	},    
	showMonthAfterYear:true});
$("#datepicker4").datepicker({dateFormat:"yymmdd",
	onSelect: function(dateText, inst) {
		dateText = dateText-19110000;
		dateText = dateText.toString();
		dateText = dateText.slice(0,3)+"/"+dateText.slice(3,5)+"/"+dateText.slice(5,7); 
		$(this).val(dateText);
	},    
	showMonthAfterYear:true});



$("#select-inday").click(function(){
	$("#databox").empty();
	var fromDate = $("input[name='from']").val();
	var toDate = $("input[name='to']").val();
	if (!fromDate || !toDate) { alert("請輸入日期搜尋！"); }
	else{ // Submit to get_new_employ/ with POST method
		$('#loadingIMG').show();
		$.post( "../get_new_employ/", {from: fromDate,to: toDate})
		 .done(function( data ) {
			$('#loadingIMG').hide();
			// Show data on div #databox
			var table_content = '\
				<form name="import" method="POST" action="">\
				<table class="table table-striped"><tr>\
				<td><b><input type="checkbox" id="clickAll"> 全選/全不選</b></td>\
				<td><b>姓名</b></td>\
				<td><b>身分證字號</b></td>\
			    <td><b>部門</b></td>\
				<td><b>到職日期</b></td>\
				<td><b>院區</b></td></tr>';
			data = $.parseJSON(data);
			for(var i=0; i<data.length; i++){
				table_content += "<tr>";
				table_content += "<td><input type='checkbox' name='"+data[i]['EPID']+"' id='check' value='"+data[i]['BRANCHNO']+"'></td>";
				table_content += "<td>"+data[i]['EPNAME']+"</td>";
				table_content += "<td>"+data[i]['IDCARDNO']+"</td>";
				if(data[i]['BRANCHNO'] != 2){
					$.ajax({
						url: "../get_DPNAME/"+data[i]['BRANCHNO']+"/"+data[i]['DPNO'],
						async: false, // 迫使其同步處裡，才能照順序執行javascript
						success: function(data){
							table_content += "<td>"+data+"</td>";
						}
					});
				
				}// End of if data[i]['BRANCHNO'] != 2
				else{ // 雙重帳號者同時抓兩院職位
					var ysvh_dpno; var savh_dpno;
					$.ajax({
						url: "../get_remote_employ_by_ID/0/"+data[i]['EPID'],
						async: false,
						success: function(data){
							data = $.parseJSON(data);
							ysvh_dpno = data['DPNO'];
						}
					});
					
					$.ajax({
						url: "../get_remote_employ_by_ID/1/"+data[i]['EPID'],
						async: false,
						success: function(data){
							data = $.parseJSON(data);
							savh_dpno = data['DPNO'];
						}
					});
					
					
					var ysvh_dpname; var savh_dpname;
					$.ajax({
						url: "../get_DPNAME/0/"+ysvh_dpno,
						async: false,
						success: function(data){ysvh_dpname = data;}
					});
					
					$.ajax({
						url: "../get_DPNAME/1/"+savh_dpno,
						async: false,
						success: function(data){savh_dpname = data;}
					});
					
					table_content += "<td>員山: "+ysvh_dpname+" <br> 蘇澳: "+savh_dpname+" </td>";
				}// End of else
				
				table_content += "<td>"+data[i]['INDAY']+"</td>";
				if(data[i]['BRANCHNO'] == 0)
					{table_content += "<td>員山</td>";}
				else if(data[i]['BRANCHNO'] == 1)
					{table_content += "<td>蘇澳</td>";}
				else
					{table_content += "<td> <select name='"+data[i]['EPID']+"-branch"+"' class='input-medium'><option value='' selected>(請選擇院區)</option> <option value='0'>員山</option> <option value='1'>蘇澳</option></select></td>";}
				table_content += "</tr>";
			} // End of for data loop
			table_content += "</table>";
			table_content += '<div class="span4 offset4">';
			table_content += '<input id="import-employ" class="btn btn-danger" type="submit" value="匯入"> ';
			table_content += '</div>';
			table_content += "</form>";
			$("#databox").append(table_content);
			
			// 全選/全不選按鈕函式
			$("#clickAll").click(function() {
				if($("#clickAll").prop("checked")){
					$("input[id='check']").each(function() {
						$(this).prop("checked", true);
					});
				}
				else{
					$("input[id='check']").each(function() {
						$(this).prop("checked", false);
					});           
				}
			}); 
			
		});// End of $.post()
		
	} // End of else
	
}); // End of function $("#select-inday").click()

// 換院區要換部門
$("#branch").change(function(){
	$("#dpnames").empty();
	var select_content = "";
	$.ajax({
		url: "../get_all_DPNAME/"+$("#branch").val(),
		async: false,
		success: function(data){
			data = $.parseJSON(data);
			for(var key in data)
				{select_content += "<option value='"+data[key]['DPNO']+"'>"+data[key]['DPNAME']+"</option>";}
			$("#dpnames").append(select_content);
		}// End of success function
	});// End of ajax
});// End of function $("#branch").change()

$("#save_change").click(function(){
	
	$.post( "../update_employ_DB", 
			{ 
				IDCARDNO: $("#idcardno").text(), 
				DPNO: $("#dpnames").val(), 
				BRANCHNO: $("#branch").val(),
				ADMIN: $("#admin").val()
			} )
	 .done(function( data ) { 
		 if(data == "1")
			 {alert( "已更新完成！" );}
		 else
			 {alert("出現錯誤，請再試一次。");}
	});
	
});

$("select[name='topic_type']").change(function() {
	if($("select[name='topic_type']").val() == "1"){
		$('#myModal').modal('show');
	}
	if($("select[name='topic_type']").val() == "0"){
		$("#group_topic_content").empty();
		$("#modify_group_topic").hide();
		$("input[name='group_order']").val("");
		$("#group_order").hide();
		$("input[name='from']").prop('readonly', false);
		$("input[name='to']").prop('readonly', false);
	}
});

$("#group_topic_yes").click(function(){
	$("#group_topic_content").empty();
	if($("#group_topic").val()){
		$("#group_topic_content").text($("#group_topic").val()+"-");
		$("input[name='group_topic_show']").val($("#group_topic").val());
		$("#group_order").show();
		$("input[name='from']").val($("#datepicker3").val());
		$("input[name='to']").val($("#datepicker4").val());
		$("input[name='from']").prop('readonly', true);
		$("input[name='to']").prop('readonly', true);
		//增加一個修改按鈕
		$("#modify_group_topic").show();
	}
});

//如果是群組課程，顯示其節數與修改群組主題按鈕
if($("select[name='topic_type']").val() == '1'){
	$("#modify_group_topic").show();
	$("#group_order").show();
}

//群組主題 自動完成
$("#group_topic").autocomplete({source: "get_group_topic", delay: 0});

//講師自動完成與新增講師
$("#new_speaker_container").hide();

$("#speaker").autocomplete({
	source: "get_speaker", 
	delay: 0,
	change: function(event,ui){$("#new_speaker_container").hide();}
});

$("#speaker").change(function(){
	$("#loading").show();
	
	// 先檢查有沒有這個講師，如果沒有，顯示新增按鈕
	$.get("check_speaker", {speaker: $("#speaker").val()})
	 .done(function(data){
		$("#loading").hide();
		if(data == "0"){
			$("#new_speaker_container").show();
			$(".blah").val(""); //清空新增講師欄位資料
			$("input[name='speaker_name']").val($("#speaker").val().split(" ")[0]);
		}
		else{
			$("#new_speaker_container").hide();
			$("#speaker").empty();
			$("#speaker").val(data);
		}
	 });
	
});

// 儲存講師資料
$("#save_speaker").click(function(){
	$.post("add_speaker",
		{
			NAME: $("input[name='speaker_name']").val(),
			IDCARDNO: $("input[name='speaker_idcardno']").val(),
			WORKEXP: $("textarea[name='speaker_workexp']").val(),
			CURRENTWORK: $("input[name='speaker_currentwork']").val(),
			EDUCATION: $("input[name='speaker_education']").val()
		})
	 .done(function(data){
		 // 填入剛剛新增的講師資料到表格內
		 $("#speaker").val(data);
	 });
});


//全選/全不選按鈕函式
$("#clickAll").click(function() {
	if($("#clickAll").prop("checked")){
		$("input[id='check']").each(function() {
			$(this).prop("checked", true);
		});
	}
	else{
		$("input[id='check']").each(function() {
			$(this).prop("checked", false);
		});           
	}
}); 

$("input[name='signin_id']").focus();

// 簽到時修改假別
$(".leave_type").click(function(){
	var type = $("select[name='"+this.id+"']").val();
	var id = this.id;
	$.post("change_leave_type",
			{
				course_id: $("input[name='course_id']").val(),
				IDCARDNO: this.id,
				leave_type: type
			})
		 .done(function(data){
			 if (data == type){
				 alert("修改成功！");
				 $("#"+id).html("已修改");
			 }else
				 {alert("資料庫錯誤，修改失敗！");}
		 });
});

$(".delete_course").click(function(){
	if(!confirm("確定要刪除課程?\n請注意已報名的名單也會刪除。")) {$(this).prop('href',document.URL);}
});

$("#delete_selected").click(function(){
	var url = "delete_course?c_id=";
	$("#check:checked").each(function(){
		url += $(this).val()+",";
	});
	$(this).prop('href',url.substring(0,url.length-1));
});


$("#modify_group_course").click(function(){
	
	$.post("modify_group_course",
			{
				g_id: $("input[name='g_id']").val(),
				group_topic: $("#group_topic").val(),
				from: $("#datepicker3").val(),
				to: $("#datepicker4").val()
			})
		 .done(function(data){
			 alert("修改完成！");
			 if (data == "done"){
				 $("#group_container").empty();
				 $("#group_date_container").empty();
				 $("#group_container").html($("#group_topic").val());
				 $("#group_date_container").html($("#datepicker3").val()+"~"+$("#datepicker4").val());
			 }else
				 {alert("資料庫錯誤，修改失敗！");}
		 });
});


// 新增類別項目
$(".new_course_type").click(function(){
	var last_id = $("tbody tr:nth-last-child(2) td input").attr("name").split("-")[0];
	last_id = (parseInt(last_id)+1).toString();
	
	var str = '<tr>'+
		'<td><input type="text" name="'+ last_id +'-type_name" class="input-medium"></td>'+
		'<td><input type="text" name="'+ last_id +'-GROUP" class="input-medium"></td>'+
		'<td><input type="text" name="'+ last_id +'-YSVH_EPID" class="input-medium"></td>'+
		'<td><input type="text" name="'+ last_id +'-SAVH_EPID" class="input-medium"></td>'+
		'<td></td>' +
		'</tr>';
			
	$("tbody tr:nth-last-child(2)").after(str);
});


// 移除類別項目
$(".remove_course_type").click(function(){
	if(confirm("確定要刪除課程類別?")) {
		$(this).parent().parent().remove();
	
		var c_id = $(this).attr('id');
		$.post("delete_course_type", { id: c_id })
		.done(function(data){
			if (data != "1") {alert("資料庫錯誤，修改失敗！");}
		});
	}
});

// 修改簽到簽退時間
$(".modify-time").click(function(){
	$id = $(this).attr("id");
	
	$ids = $id.split("-");
	if($ids[0] === "in"){ // 修改簽到時間
		$.post("modify_time", 
			{ 
				id: $ids[1] ,
				c_id: $("input[name='course_id']").val(),
				intime: $("input[name='"+ $id +"']").val()
			})
		.done(function(data){
			if (data != "1") {alert("資料庫錯誤，修改失敗！");}
			else {
				alert("修改完成！");
				$("#"+$id).html("已修改");
			}
		});
		
	}else{ // 修改簽退時間
		$.post("modify_time", 
			{ 
				id: $ids[1] ,
				c_id: $("input[name='course_id']").val(),
				outtime: $("input[name='"+ $id +"']").val()
			})
		.done(function(data){
			if (data != "1") {alert("資料庫錯誤，修改失敗！");}
			else {
				alert("修改完成！");
				$("#"+$id).html("已修改");
			}
		});
	}
	
	
});

});// End of jQuery scope