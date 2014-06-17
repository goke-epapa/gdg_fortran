/**
 * Created with JetBrains PhpStorm.
 * User: MICROSOFt
 * Date: 7/31/13
 * Time: 1:44 PM
 * To change this template use File | Settings | File Templates.
 */
/**
 * Here the entire App is initialized
 */

/**
 * Initializes the App
 */
(function () {
    $(document).ready(function () {
        $("#links").off("click").on("click","a",function(){
            var link = this.getAttribute("href");
            Fortran.logic.hideAllBut(link);
        });
    });
})();

