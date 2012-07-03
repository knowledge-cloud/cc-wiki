
if(jQuery)(
    function($){jQuery.extend(jQuery.fn,{
                spWidget:function(options) {
                    $(this).each(function(){
                        var settings = $.extend({
                        id                  : $(this).attr('id'),
                        //widgetguid          : $(this).attr('guid'),
                        template            : '',
                        basepath            : '/extensions/ccgroup2/widget/loadwidget.php',
                        //path                : $(this).attr('url'),
                        replaced            : false,
                        onComplete         : function() {}
                        }, options);
                        if($(this).attr('execed')==true) return ;
                        $(this).css('display','none');
                        $(this).attr("execed", true);
			$(this).after('<div id="spWidget_' + $(this).attr('id') + '_content"></div>');
                        $("#spWidget_" + $(this).attr('id') + "_content").load(settings.basepath,{wg:settings.id,tp:settings.template},function(){
                            if(typeof(settings.onComplete) == 'function')
                                settings.onComplete("spWidget_"+settings.id+"_content");
                        });
                    });
                }
        })
})(jQuery);    