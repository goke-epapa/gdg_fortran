/**
 * Created with JetBrains PhpStorm.
 * User: epapa
 * Date: 9/20/13
 * Time: 4:53 AM
 * To change this template use File | Settings | File Templates.
 */
var editor = ace.edit("code");
editor.setTheme("ace/theme/twilight");
editor.getSession().setMode("ace/mode/java");
var textarea = document.getElementsByTagName("textarea")[1];
textarea.style.display = "none";
editor.getSession().setValue(textarea.innerHTML);
editor.getSession().on("change", function () {
    textarea.innerHTML = editor.getSession().getValue();
});
editor.setFontSize(16);
editor.getSession().on("changeAnnotation", function () {

    var annot = editor.getSession().getAnnotations();

   /* for (var key in annot) {
        if (annot.hasOwnProperty(key))
            console.log("[" + annot[key][0].row + " , " + annot[key][0].column + "] - \t" + annot[key][0].text);
    }*/

});
editor.renderer.onResize(true);
$(document).ready(function () {
	var matricNo = prompt("Enter your MATRIC Number to continue.");
	
	if(matricNo && matricNo.length > 0){
	loadFile(matricNo);	    
	$("#submit").unbind("click").on("click", function () {
		var lab_no = $("#lab_no").val();
 	
		if(lab_no.length > 0){
					
			$("#matric").val(matricNo);
			$("#lab_no").val(lab_no);
			var url = "./shell.php";
			var contents = $("#form").serialize();
			$("#success").hide();
			$("#source_link").hide();
			$("#error").hide();
			$.post(url, contents, function (data) {
			    if(data.status){
				//console.log(data.result);
				$("#success").html(">> " + data.result).fadeIn();
				$("#source_link").attr("href",data.link).fadeIn();
			    }else{
				$("#error").html("Error>>\n" + data.error_message).fadeIn();
			    }
			},"json");
			return false;
		}else{
		   alert("Please Select the Lab Number to submit.");
		}
	    });
	}else{
	   alert("Matric Number is not specified");
	   window.location.reload();
	}

$("#clear_btn").unbind("clear").on("click", function () {
    editor.getSession().setValue("");
    $("#success").hide();
    $("#source_link").hide();
    $("#error").hide();
    $("#stdin").val("");
});
$("#lab_no").on('change',function(){
 	loadFile (matricNo);
});
$("#save_btn").unbind("click").bind("click", function () {
    var labNo = $("#lab_no").val();	
	if(typeof matricNo != "undefined" && matricNo.length > 0 && typeof labNo  != "undefined" && labNo.length > 0){
		
		var data = {
			action: "save",
			student_no: matricNo,
			lab_no: labNo,
			code: $("#form").serialize()
		};
		var url = "./Save.php";
		
	    $.post(url, data, function(moyin){
		alert(moyin);
	    });
	}else{
	    alert("Error. Not selected.");
}
});

});

function loadFile (matricNo){
 	var labNo = $("#lab_no").val();	
	if(typeof matricNo != "undefined" && matricNo.length > 0 && typeof labNo  != "undefined" && labNo.length > 0){
		
		var data = {
			action: "get",
			student_no: matricNo,
			lab_no: labNo,
			code: ""
		};
		var url = "./Save.php";
		
	    $.post(url, data, function(moyin){
		
	if(moyin.length > 0){
		editor.getSession().setValue(moyin);
		}else{
			editor.getSession().setValue("");
}
	    });
	}else{
	    alert("Error. Not selected.");
}
};

