/**
 * Created with JetBrains PhpStorm.
 * User: user
 * Date: 9/17/13
 * Time: 3:50 AM
 * To change this template use File | Settings | File Templates.
 */

/**
 * Initializes the Fortran UI, and binds events handlers
 */
Fortran.logic = {
    hideAllBut : function(but){
        var obj = Fortran.config;
        for(var key in obj){
            if(obj[key] != but){
                $(obj[key]).hide();
            }
        }
        $(but).fadeIn();
    }
}