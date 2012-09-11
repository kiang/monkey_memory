var Monkey = {};
Monkey.module = {
    version: '0.1',
    test: function(dModule){

        $(dModule).bind('keypress', function(e) {

            var code = (e.keyCode ? e.keyCode : e.which);
            if(code == 13) { //Enter keycode
                e.preventDefault();
                //ajax search
                
            }
        });


    }
};
(function(){
    var doWhileExist = function(ModuleID,objFunction){
        var dTarget = document.getElementById(ModuleID);
        if(dTarget){
            objFunction(dTarget);
        }                
    };
    doWhileExist('test',Monkey.module.test);
})();
