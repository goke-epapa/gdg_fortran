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

    for (var key in annot) {
        if (annot.hasOwnProperty(key))
            console.log("[" + annot[key][0].row + " , " + annot[key][0].column + "] - \t" + annot[key][0].text);
    }

});
editor.renderer.onResize(true);
$(document).ready(function () {
    $("#submit").unbind("click").on("click", function () {
        var url = "./shell.php";
        var contents = $("#form").serialize();
        $("#success").hide();
        $("#source_link").hide();
        $("#error").hide();
        $.post(url, contents, function (data) {
            if(data.status){
                console.log(data.result);
                $("#success").html(">> " + data.result).fadeIn();
                $("#source_link").attr("href",data.link).fadeIn();
            }else{
                $("#error").html("Error>>\n" + data.error_message).fadeIn();
            }
        },"json");
        return false;
    });
});
$("#clear_btn").unbind("clear").on("click", function () {
    editor.getSession().setValue("");
    $("#success").hide();
    $("#source_link").hide();
    $("#error").hide();
    $("#stdin").val("");
});