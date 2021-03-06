var Monkey = {};
Monkey.util = {
    version: '0.1',
    render: function(data){
        var HTML = '';
        for(var i=0,j=data.length;i<j;i++){
            HTML += [
                '<div class="item">',
                '  <div class="shadow"></div>',
                '  <div class="data">',
                '    <div class="time">'+data[i].time+'</div>',
                '    <a href="'+data[i].link+'"><img src="'+data[i].picture+'"></a>',
                '    '+data[i].message,
                '  </div>',
                '</div>'
            ].join('');
        }
        return HTML;
    },
};
Monkey.module = {
    test: function(dModule){

        $(dModule).bind('keypress', function(e) {

            var code = (e.keyCode ? e.keyCode : e.which);
            if(code == 13) { //Enter keycode
                e.preventDefault();
                //ajax search
                $.ajax({
                    url:"recall.php?q="+$(dModule).val(),
                    error: function() {
                        // 
                    },
                    success: function(response) {
                        //render post
                        if(response && response.post){
                            var HTML = Monkey.util.render(response.post);
                            $('.box .post').html(HTML);
                        }
                    }
                })
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
