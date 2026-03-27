/* global window, Image, jQuery */
/**
 * @author 360Learning
 * @author Catalin Dogaru (https://github.com/cdog - http://code.tutsplus.com/tutorials/how-to-create-a-jquery-image-cropping-plugin-from-scratch-part-i--net-20994)
 * @author Adrien David-Sivelle (https://github.com/AdrienDS - Refactoring, Multiselections & Mobile compatibility)
 */
(function($) {
    $.imageArea = function(parent, id) {

        var options = parent.options,
            $image = parent.$image,
            $trigger = parent.$trigger,
            $outline,
            $selection,
            $pointcenter,
            $pointneck,

            $pointrighttop,
            $pointrightbottom,

            $line1,
            $line2,
            $line3,
            $line4,

            $resizeHandlers = {},
            $btDelete,
            pointCenterMove = false,
            pointNeckMove = false,
            pointRightTopMove = false,
            pointRightBottomMove = false,
            resizeHorizontally = true,
            resizeVertically = true,
            edited = true,
            touched = false,
            selectionOffset = [0, 0],
            selectionOrigin = [0, 0],
            area = {
                id: id,
                fi_idx : '',
                gp_idx : '',
                annotationName: '',
                annotation:0,
                x: 0,
                y: 0,
                z: 0,
                height: 0,
                width: 0,
                org_width : 0,
                org_height : 0,
                center_x:0,
                center_y:0,
                center_x2:0,
                center_y2:0,
                center_x3:0,
                center_y3:0,
                center_x4:0,
                center_y4:0,
                sel_mode:'',
                sel_class:'',
                sel_type:'',
                sel_value:'',
                sel_barrier:'',
                sel_laneloc:''
            },
            blur = function () {
                area.z = 0;
                refresh("blur");
            },
            focus = function () {
                parent.blurAll();
                area.z = 100;
                refresh();
            },
            getData = function () {
                return area;
            },
            fireEvent = function (event) {
                $image.trigger(event, [area.id, parent.areas()]);
            },
            cancelEvent = function (e) {
                var event = e || window.event || {};
                event.cancelBubble = true;
                event.returnValue = false;
                event.stopPropagation && event.stopPropagation(); // jshint ignore: line
                event.preventDefault && event.preventDefault(); // jshint ignore: line
            },
            off = function() {
                $.each(arguments, function (key, val) {
                    on(val);
                });
            },
            on = function (type, handler) {
                var browserEvent, mobileEvent;
               
                switch (type) {
                    case "start":
                        browserEvent = "mousedown";
                        mobileEvent = "touchstart";
                        break;
                    case "move":
                        browserEvent = "mousemove";
                        mobileEvent = "touchmove";
                        break;
                    case "stop":
                        browserEvent = "mouseup";
                        mobileEvent = "touchend";
                        break;
                    default:
                        return;
                }
                if (handler && jQuery.isFunction(handler)) {
                    $(window.document).on(browserEvent, handler).on(mobileEvent, handler);
                } else {
                    $(window.document).off(browserEvent).off(mobileEvent);
                }
            },
            
            updateLineAllSelection = function () {

                var center_x =  area.center_x;
                var center_y =  area.center_y;
                var mouse_x =  area.center_x3;
                var mouse_y = area.center_y3;
                var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
                var degrees = (radians * (180 / Math.PI) * -1);
                
                //x,x1, y,y1 거리 구하기 
                var height_size = Math.sqrt(Math.pow(Math.abs(mouse_x-center_x),2) + Math.pow(Math.abs(mouse_y-center_y), 2));
                $line1.css({
                    width: 2,
                    height: height_size,
                    left: area.center_x,
                    top: area.center_y,
                    '-webkit-transform' : 'rotate('+degrees+'deg)',
                    '-moz-transform' : 'rotate('+degrees+'deg)',  
                    '-ms-transform' : 'rotate('+degrees+'deg)',  
                    '-o-transform' : 'rotate('+degrees+'deg)',  
                        'transform' : 'rotate('+degrees+'deg)',  
                        'transform-origin': 'top left'
                });
                $line1.show();  

                var center_x =  area.center_x3;
                var center_y =  area.center_y3;
                var mouse_x =  area.center_x4 ;
                var mouse_y = area.center_y4;
                var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
                var degrees = (radians * (180 / Math.PI) * -1);
                
                //x,x1, y,y1 거리 구하기 
                var height_size = Math.sqrt(Math.pow(Math.abs(mouse_x-center_x),2) + Math.pow(Math.abs(mouse_y-center_y), 2));
                $line2.css({
                    width: 2,
                    height: height_size,
                    left: area.center_x3 ,
                    top: area.center_y3 ,
                    '-webkit-transform' : 'rotate('+degrees+'deg)',
                    '-moz-transform' : 'rotate('+degrees+'deg)',  
                    '-ms-transform' : 'rotate('+degrees+'deg)',  
                    '-o-transform' : 'rotate('+degrees+'deg)',  
                        'transform' : 'rotate('+degrees+'deg)',  
                        'transform-origin': 'top left'
                });
                $line2.show();

                var center_x =  area.center_x2;
                var center_y =  area.center_y2;
                var mouse_x =  area.center_x4 ;
                var mouse_y = area.center_y4;
                var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
                var degrees = (radians * (180 / Math.PI) * -1);
                
                //x,x1, y,y1 거리 구하기 
                var height_size = Math.sqrt(Math.pow(Math.abs(mouse_x-center_x),2) + Math.pow(Math.abs(mouse_y-center_y), 2));
                $line3.css({
                    width: 2,
                    height: height_size,
                    left: area.center_x2 ,
                    top: area.center_y2,
                    '-webkit-transform' : 'rotate('+degrees+'deg)',
                    '-moz-transform' : 'rotate('+degrees+'deg)',  
                    '-ms-transform' : 'rotate('+degrees+'deg)',  
                    '-o-transform' : 'rotate('+degrees+'deg)',  
                        'transform' : 'rotate('+degrees+'deg)',  
                        'transform-origin': 'top left'
                });
                $line3.show();


                var center_x =  area.center_x;
                var center_y =  area.center_y;
                var mouse_x =  area.center_x2;
                var mouse_y = area.center_y2;
                var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
                var degrees = (radians * (180 / Math.PI) * -1);
                
                //x,x1, y,y1 거리 구하기 
                var height_size = Math.sqrt(Math.pow(Math.abs(mouse_x-center_x),2) + Math.pow(Math.abs(mouse_y-center_y), 2));
                $line4.css({
                    width: 2,
                    height: height_size,
                    left: area.center_x ,
                    top: area.center_y,
                    '-webkit-transform' : 'rotate('+degrees+'deg)',
                    '-moz-transform' : 'rotate('+degrees+'deg)',  
                    '-ms-transform' : 'rotate('+degrees+'deg)',  
                    '-o-transform' : 'rotate('+degrees+'deg)',  
                        'transform' : 'rotate('+degrees+'deg)',  
                        'transform-origin': 'top left'
                });
                $line4.show();


            },
            updateLineSelection = function () {
                
                    var center_x =  area.center_x;
                    var center_y =  area.center_y;
                    var mouse_x =  area.center_x2;
                    var mouse_y = area.center_y2;
                    var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
                    var degrees = (radians * (180 / Math.PI) * -1);
                    
                    //x,x1, y,y1 거리 구하기 
                    var height_size = Math.sqrt(Math.pow(Math.abs(mouse_x-center_x),2) + Math.pow(Math.abs(mouse_y-center_y), 2));
                    $line1.css({
                        width: 2,
                        height: height_size,
                        left: area.center_x,
                        top: area.center_y,
                        '-webkit-transform' : 'rotate('+degrees+'deg)',
                        '-moz-transform' : 'rotate('+degrees+'deg)',  
                        '-ms-transform' : 'rotate('+degrees+'deg)',  
                        '-o-transform' : 'rotate('+degrees+'deg)',  
                            'transform' : 'rotate('+degrees+'deg)',  
                            'transform-origin': 'top left'
                    });

                    var plus_mouse_x = 0;
                    if(area.center_x > (area.x + area.width / 2)) {
                        plus_mouse_x = area.width -2;
                    }


                    var center_x =  area.center_x;
                    var center_y =  area.center_y;
                    var mouse_x =  area.x + plus_mouse_x;
                    var mouse_y = area.y;
                    var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
                    var degrees = (radians * (180 / Math.PI) * -1);
                    
                    //x,x1, y,y1 거리 구하기 
                    var height_size = Math.sqrt(Math.pow(Math.abs(mouse_x-center_x),2) + Math.pow(Math.abs(mouse_y-center_y), 2));
                    $line2.css({
                        width: 2,
                        height: height_size,
                        left: area.center_x,
                        top: area.center_y,
                        '-webkit-transform' : 'rotate('+degrees+'deg)',
                        '-moz-transform' : 'rotate('+degrees+'deg)',  
                        '-ms-transform' : 'rotate('+degrees+'deg)',  
                        '-o-transform' : 'rotate('+degrees+'deg)',  
                            'transform' : 'rotate('+degrees+'deg)',  
                            'transform-origin': 'top left'
                    });


            
                    var center_x =  area.center_x2;
                    var center_y =  area.center_y2;
                    var mouse_x =  area.x + plus_mouse_x;
                    var mouse_y = area.y + area.height - 4;
                    var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
                    var degrees = (radians * (180 / Math.PI) * -1);
                    
                    //x,x1, y,y1 거리 구하기 
                    var height_size = Math.sqrt(Math.pow(Math.abs(mouse_x-center_x),2) + Math.pow(Math.abs(mouse_y-center_y), 2));
                    $line3.css({
                        width: 2,
                        height: height_size,
                        left: area.center_x2,
                        top: area.center_y2,
                        '-webkit-transform' : 'rotate('+degrees+'deg)',
                        '-moz-transform' : 'rotate('+degrees+'deg)',  
                        '-ms-transform' : 'rotate('+degrees+'deg)',  
                        '-o-transform' : 'rotate('+degrees+'deg)',  
                            'transform' : 'rotate('+degrees+'deg)',  
                            'transform-origin': 'top left'
                    });
            },

            updatePointSelection = function () {
                if(pointNeckMove ==true  || pointRightTopMove ==true  || pointRightBottomMove ==true ) return;
                var option = this;
                pointCenterMove =true ;
                //가운데 포인트 표시하기 
               
                 $pointcenter.css({
                     left: area.center_x-4,
                     top: area.center_y -4
                 });

                 if(area.sel_mode == '6') {
                    updateLineSelection();
                 } else if(area.sel_mode == '7') {
                    updateLineAllSelection();
                 }
            },

            updatePointNeckSelection = function () {
                if(pointCenterMove ==true || pointRightTopMove ==true  || pointRightBottomMove ==true  ) return;
               
                var option = this;
                pointNeckMove =true ;

                 $pointneck.css({
                     left: area.center_x2 -4,
                     top: area.center_y2 -4
                 });

                 if(area.sel_mode == '6') {
                    updateLineSelection();
                 }else if(area.sel_mode == '7') {
                    updateLineAllSelection();
                 }

            },

            updatePointRightTopSelection = function () {
                if(pointCenterMove ==true || pointNeckMove ==true  || pointRightBottomMove ==true  ) return;
               
                var option = this;
                pointRightTopMove =true ;

                 $pointrighttop.css({
                     left: area.center_x3 -4,
                     top: area.center_y3 - 4
                 });


                 if(area.sel_mode == '6') {
                    updateLineSelection();
                 }else if(area.sel_mode == '7') {
                    updateLineAllSelection();
                 }
                 
            },

            updatePointRightBottomSelection = function () {
                if(pointCenterMove ==true || pointNeckMove ==true  || pointRightTopMove ==true  ) return;
               
                var option = this;
                pointRightBottomMove =true ;
               
                 $pointrightbottom.css({
                     left: area.center_x4-4,
                     top: area.center_y4-4
                 });

                 if(area.sel_mode == '6') {
                    updateLineSelection();
                 }else if(area.sel_mode == '7') {
                    updateLineAllSelection();
                 }
            },


            updateSelection = function () {
                if(pointCenterMove ==true || pointNeckMove ==true || pointRightTopMove ==true || pointRightBottomMove ==true ) return;
                
                
                if (area.sel_mode == ''  || area.sel_mode == '1' || area.sel_mode == '2' || area.sel_mode == '4' || area.sel_mode == '5' ) {
                  
                    $outline.css({
                        cursor: "default",
                        width: area.width,
                        height: area.height,
                        left: area.x,
                        top: area.y,
                        "z-index": area.z
                    });
    
                    // Update the selection layer
                    $selection.css({
                        backgroundPosition : ( - area.x - 1) + "px " + ( - area.y - 1) + "px",
                        cursor : options.allowMove ? "move" : "default",
                        width: (area.width - 2 > 0) ? (area.width - 2) : 0,
                        height: (area.height - 2 > 0) ? (area.height - 2) : 0,
                        left : area.x + 1,
                        top : area.y + 1,
                        "z-index": area.z + 2
                    });

                    //가운데 포인트 표시하기 
                 
                    var calc_x = 0;
                    var calc_y = 0;
                    var calc_x2 = 0;
                    var calc_y2 = 0;
                    
                    if(area.annotation==0) {
                        calc_x = ((area.x -4) + (area.width/ 2))  ;
                        calc_y =  ((area.y-4) + (area.height/2))  ;

                        calc_x2 = ((area.x -4) + (area.width/ 2))  ;
                        calc_y2 =  ((area.y-4) + (area.height/4))  ;

                        
                        area.center_x = (area.width/ 2) -4;
                        area.center_y = (area.height/ 2) - 4;
                        
                        area.center_x2 = (area.width/ 2) -4;
                        area.center_y2 = (area.height/ 4) - 4;


                    } else {
                        calc_x = area.x +  area.center_x;
                        calc_y = area.y +  area.center_y;
                        calc_x2 = area.x +  area.center_x2;
                        calc_y2 = area.y +  area.center_y2;
                    }
                    
                    // //center_x, center_right
                    $pointcenter.css({
                        left: calc_x,
                        top: calc_y
                    });
                    $pointcenter.show();
                   
                    if(area.sel_mode == '4' || area.sel_mode == '5') {
                        $pointneck.css({
                            left:calc_x2,
                            top: calc_y2
                        });
                        $pointneck.show();
                    } else {
                        $pointneck.hide();
                    }
                   
                

                } else if (area.sel_mode == '3') {
                   
                    
                   
                    var center_x =  area.x;
                    var center_y =  area.y;
                    var mouse_x =  (area.x+area.width) ;
                    var mouse_y = (area.y + area.height);
                    var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
                    var degrees = (radians * (180 / Math.PI) * -1);
                    
                    
                    //x,x1, y,y1 거리 구하기 
                    var height_size = Math.sqrt(Math.pow(Math.abs(mouse_x-center_x),2) + Math.pow(Math.abs(mouse_y-center_y), 2));
                    var width_size = 3;
    
                    $outline.css({
                        cursor: "default",
                        width: width_size,
                        height: height_size,
                        left: selectionOrigin[0],
                        top: selectionOrigin[1],
                        '-webkit-transform' : 'rotate('+degrees+'deg)',
                        '-moz-transform' : 'rotate('+degrees+'deg)',  
                        '-ms-transform' : 'rotate('+degrees+'deg)',  
                        '-o-transform' : 'rotate('+degrees+'deg)',  
                            'transform' : 'rotate('+degrees+'deg)',  
                            'transform-origin': 'top left'
                    });
    
                    // Update the selection layer
                    $selection.css({
                        backgroundPosition : ( - area.x - 1) + "px " + ( - area.y - 1) + "px",
                        cursor : options.allowMove ? "move" : "default",
                        width: width_size,
                        height: height_size,
                        left: selectionOrigin[0],
                        top: selectionOrigin[1],
                        '-webkit-transform' : 'rotate('+degrees+'deg)',
                        '-moz-transform' : 'rotate('+degrees+'deg)',  
                        '-ms-transform' : 'rotate('+degrees+'deg)',  
                        '-o-transform' : 'rotate('+degrees+'deg)',  
                        'transform' : 'rotate('+degrees+'deg)',  
                        'transform-origin': 'top left',
                        "z-index": area.z + 2
                    });

                    $pointcenter.hide();
                    $pointneck.hide();

                    $('div[id^="pointerline_area_'+ area.id+'"]').each(function(){
                        $(this).remove();
                    });
                    var nCnt = 0;
                    for(var i=0;i<20;i++){
                        $pointline = $("<div style='background-color:#00ff00;border-radius:50%; z-index:999; width:5px;height:5px;' id=\"pointerline_area_"+area.id+"_"+i+"\"  />")
                        .css({
                            position : "absolute"
                        })
                        .insertAfter($trigger);

                        var mouse_x = (selectionOrigin[0] + area.width /19 * i );
                        var mouse_y = (selectionOrigin[1] + area.height /19 * i );
                        
                        if(i==0){
                            mouse_x = selectionOrigin[0];
                            mouse_y = selectionOrigin[1];
                        } else{
                        }
                        $pointline.css({
                            left: mouse_x ,
                            top: mouse_y 
                        });
                    }



                }else if(area.sel_mode == '6'){

                    $outline.css({
                        cursor: "default",
                        width: area.width,
                        height: area.height,
                        left: area.x,
                        top: area.y,
                        "z-index": area.z
                    });
    
                    // Update the selection layer
                    $selection.css({
                        backgroundPosition : ( - area.x - 1) + "px " + ( - area.y - 1) + "px",
                        cursor : options.allowMove ? "move" : "default",
                        width: (area.width - 2 > 0) ? (area.width - 2) : 0,
                        height: (area.height - 2 > 0) ? (area.height - 2) : 0,
                        left : area.x + 1,
                        top : area.y + 1,
                        "z-index": area.z + 2
                    });


                    var calc_x = 0;
                    var calc_y = 0;
                    var calc_x2 = 0;
                    var calc_y2 = 0;
                    
                    //calc_x = (area.x+ area.width ) - (area.width /2);
                    //calc_y = (area.y+ area.height ) - (area.height /2);

                    if(area.annotation==0) {
                        
                        calc_x = area.x -  area.width /2;
                        calc_y = area.y  ;
    
                        calc_x2 = area.x -  area.width /2;
                        calc_y2 = area.y + area.height ;

                        area.center_x = calc_x;
                        area.center_y = calc_y;
                        
                        area.center_x2 = calc_x2;
                        area.center_y2 = calc_y2;
                        

                    } else {
                        calc_x = area.center_x;
                        calc_y = area.center_y;
                        calc_x2 = area.center_x2;
                        calc_y2 = area.center_y2;
                    }


                    $pointcenter.css({
                        left: calc_x -2,
                        top: calc_y -2
                    });
                    $pointcenter.show();

                    $pointneck.css({
                        left: calc_x2 - 2,
                        top: calc_y2 - 2
                    });
                    $pointneck.show();
                    

                    
                    var center_x =  calc_x;
                    var center_y =  calc_y;
                    var mouse_x =  calc_x2;
                    var mouse_y = calc_y2;
                    var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
                    var degrees = (radians * (180 / Math.PI) * -1);
                    
                    //x,x1, y,y1 거리 구하기 
                    var height_size = Math.sqrt(Math.pow(Math.abs(mouse_x-center_x),2) + Math.pow(Math.abs(mouse_y-center_y), 2));
                    $line1.css({
                        width: 2,
                        height: height_size,
                        left: calc_x,
                        top: calc_y,
                        '-webkit-transform' : 'rotate('+degrees+'deg)',
                        '-moz-transform' : 'rotate('+degrees+'deg)',  
                        '-ms-transform' : 'rotate('+degrees+'deg)',  
                        '-o-transform' : 'rotate('+degrees+'deg)',  
                            'transform' : 'rotate('+degrees+'deg)',  
                            'transform-origin': 'top left'
                    });
                    $line1.show();  

                    var plus_mouse_x = 0;
                    if(calc_x > (area.x + area.width / 2)) {
                        plus_mouse_x = area.width -2;
                    }
                  
                    var center_x =  calc_x;
                    var center_y =  calc_y;
                    var mouse_x =  area.x + plus_mouse_x;
                    var mouse_y = area.y;
                    var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
                    var degrees = (radians * (180 / Math.PI) * -1);
                    
                    //x,x1, y,y1 거리 구하기 
                    var height_size = Math.sqrt(Math.pow(Math.abs(mouse_x-center_x),2) + Math.pow(Math.abs(mouse_y-center_y), 2));
                    $line2.css({
                        width: 2,
                        height: height_size,
                        left: calc_x ,
                        top: calc_y ,
                        '-webkit-transform' : 'rotate('+degrees+'deg)',
                        '-moz-transform' : 'rotate('+degrees+'deg)',  
                        '-ms-transform' : 'rotate('+degrees+'deg)',  
                        '-o-transform' : 'rotate('+degrees+'deg)',  
                            'transform' : 'rotate('+degrees+'deg)',  
                            'transform-origin': 'top left'
                    });
                    $line2.show();


                    var center_x =  calc_x2;
                    var center_y =  calc_y2;
                    var mouse_x =  area.x + plus_mouse_x;
                    var mouse_y = area.y + area.height - 4;
                    var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
                    var degrees = (radians * (180 / Math.PI) * -1);
                    
                    //x,x1, y,y1 거리 구하기 
                    var height_size = Math.sqrt(Math.pow(Math.abs(mouse_x-center_x),2) + Math.pow(Math.abs(mouse_y-center_y), 2));
                    $line3.css({
                        width: 2,
                        height: height_size,
                        left: calc_x2 ,
                        top: calc_y2,
                        '-webkit-transform' : 'rotate('+degrees+'deg)',
                        '-moz-transform' : 'rotate('+degrees+'deg)',  
                        '-ms-transform' : 'rotate('+degrees+'deg)',  
                        '-o-transform' : 'rotate('+degrees+'deg)',  
                            'transform' : 'rotate('+degrees+'deg)',  
                            'transform-origin': 'top left'
                    });
                    $line3.show();

                }else if(area.sel_mode == '7'){
                    




                    var calc_x = 0;
                    var calc_y = 0;
                    var calc_x2 = 0;
                    var calc_y2 = 0;
                    var calc_x3 = 0;
                    var calc_y3 = 0;
                    var calc_x4 = 0;
                    var calc_y4 = 0;
                    
                    //calc_x = (area.x+ area.width ) - (area.width /2);
                    //calc_y = (area.y+ area.height ) - (area.height /2);
                    
                    if(area.annotation==0) {
                        
                        calc_x = area.x;
                        calc_y = area.y;
    
                        calc_x2 = area.x ;
                        calc_y2 = area.y + area.height ;


                        calc_x3 = area.x + area.width ;
                        calc_y3 = area.y ;

                        calc_x4 = area.x + area.width ;
                        calc_y4 = area.y + area.height ;
                        
                        area.center_x = calc_x;
                        area.center_y = calc_y;
                        
                        area.center_x2 = calc_x2;
                        area.center_y2 = calc_y2;

                        area.center_x3 = calc_x3;
                        area.center_y3 = calc_y3;

                        area.center_x4 = calc_x4;
                        area.center_y4 = calc_y4;


                    } else {
                     
                        calc_x = area.center_x;
                        calc_y = area.center_y;
                        calc_x2 = area.center_x2;
                        calc_y2 = area.center_y2;
                        
                        calc_x3 = area.center_x3;
                        calc_y3 = area.center_y3;
                        calc_x4 = area.center_x4;
                        calc_y4 = area.center_y4;
                    }
                    
                    
                   
                    $pointcenter.css({
                        left: calc_x -4,
                        top: calc_y -4
                    });
                    $pointcenter.show();

                    $pointneck.css({
                        left: calc_x2 - 4,
                        top: calc_y2 - 4
                    });
                    $pointneck.show();


                    $pointrighttop.css({
                        left: calc_x3 - 4,
                        top: calc_y3 - 4
                    });
                    $pointrighttop.show();

                    $pointrightbottom.css({
                        left: calc_x4 - 4,
                        top: calc_y4 - 4
                    });
                    $pointrightbottom.show();




                    
                    var center_x =  calc_x;
                    var center_y =  calc_y;
                    var mouse_x =  calc_x3;
                    var mouse_y = calc_y3;
                    var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
                    var degrees = (radians * (180 / Math.PI) * -1);
                    
                    //x,x1, y,y1 거리 구하기 
                    var height_size = Math.sqrt(Math.pow(Math.abs(mouse_x-center_x),2) + Math.pow(Math.abs(mouse_y-center_y), 2));
                    $line1.css({
                        width: 2,
                        height: height_size,
                        left: calc_x,
                        top: calc_y,
                        '-webkit-transform' : 'rotate('+degrees+'deg)',
                        '-moz-transform' : 'rotate('+degrees+'deg)',  
                        '-ms-transform' : 'rotate('+degrees+'deg)',  
                        '-o-transform' : 'rotate('+degrees+'deg)',  
                            'transform' : 'rotate('+degrees+'deg)',  
                            'transform-origin': 'top left'
                    });
                    $line1.show();  

                  
                  
                    var center_x =  calc_x3;
                    var center_y =  calc_y3;
                    var mouse_x =  calc_x4 ;
                    var mouse_y = calc_y4;
                    var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
                    var degrees = (radians * (180 / Math.PI) * -1);
                    
                    //x,x1, y,y1 거리 구하기 
                    var height_size = Math.sqrt(Math.pow(Math.abs(mouse_x-center_x),2) + Math.pow(Math.abs(mouse_y-center_y), 2));
                    $line2.css({
                        width: 2,
                        height: height_size,
                        left: calc_x3 ,
                        top: calc_y3 ,
                        '-webkit-transform' : 'rotate('+degrees+'deg)',
                        '-moz-transform' : 'rotate('+degrees+'deg)',  
                        '-ms-transform' : 'rotate('+degrees+'deg)',  
                        '-o-transform' : 'rotate('+degrees+'deg)',  
                            'transform' : 'rotate('+degrees+'deg)',  
                            'transform-origin': 'top left'
                    });
                    $line2.show();


                    var center_x =  calc_x2;
                    var center_y =  calc_y2;
                    var mouse_x =  calc_x4;
                    var mouse_y = calc_y4;
                    var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
                    var degrees = (radians * (180 / Math.PI) * -1);
                    
                    //x,x1, y,y1 거리 구하기 
                    var height_size = Math.sqrt(Math.pow(Math.abs(mouse_x-center_x),2) + Math.pow(Math.abs(mouse_y-center_y), 2));
                    $line3.css({
                        width: 2,
                        height: height_size,
                        left: calc_x2 ,
                        top: calc_y2,
                        '-webkit-transform' : 'rotate('+degrees+'deg)',
                        '-moz-transform' : 'rotate('+degrees+'deg)',  
                        '-ms-transform' : 'rotate('+degrees+'deg)',  
                        '-o-transform' : 'rotate('+degrees+'deg)',  
                            'transform' : 'rotate('+degrees+'deg)',  
                            'transform-origin': 'top left'
                    });
                    $line3.show();



                    var center_x =  calc_x;
                    var center_y =  calc_y;
                    var mouse_x =  calc_x2;
                    var mouse_y = calc_y2;
                    var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
                    var degrees = (radians * (180 / Math.PI) * -1);
                    
                    //x,x1, y,y1 거리 구하기 
                    var height_size = Math.sqrt(Math.pow(Math.abs(mouse_x-center_x),2) + Math.pow(Math.abs(mouse_y-center_y), 2));
                    $line4.css({
                        width: 2,
                        height: height_size,
                        left: calc_x ,
                        top: calc_y,
                        '-webkit-transform' : 'rotate('+degrees+'deg)',
                        '-moz-transform' : 'rotate('+degrees+'deg)',  
                        '-ms-transform' : 'rotate('+degrees+'deg)',  
                        '-o-transform' : 'rotate('+degrees+'deg)',  
                            'transform' : 'rotate('+degrees+'deg)',  
                            'transform-origin': 'top left'
                    });
                    $line4.show();



                    
                    $outline.css({
                        cursor: "default",
                        width: area.width,
                        height: area.height,
                        left: area.x,
                        top: area.y,
                        'opacity':'0.05',
                        "z-index": area.z
                    });
    
                    // Update the selection layer
                    $selection.css({
                        backgroundPosition : ( - area.x - 1) + "px " + ( - area.y - 1) + "px",
                        cursor : options.allowMove ? "move" : "default",
                        width: (area.width - 2 > 0) ? (area.width - 2) : 0,
                        height: (area.height - 2 > 0) ? (area.height - 2) : 0,
                        left : area.x + 1,
                        top : area.y + 1,
                        'opacity':'0.05',
                        "z-index": area.z + 2
                    });
                    


                        // $("#line2_area_" + area.id).css({ 'backgroundColor': annotation_text[color_index][0].color});
                        // $("#line3_area_" + area.id).css({ 'backgroundColor': annotation_text[color_index][0].color});
                        // $("#line4_area_" + area.id).css({ 'backgroundColor': annotation_text[color_index][0].color});
                        // $("#pointercenter_area_" + area.id).css({ 'backgroundColor': annotation_text[color_index][0].color});
                        // $("#pointerneck_area_" + area.id).css({ 'backgroundColor': annotation_text[color_index][0].color});

                        // $("#pointerrighttop_area_" + area.id).css({ 'backgroundColor': annotation_text[color_index][0].color});
                        // $("#pointerrightbottom_area_" + area.id).css({ 'backgroundColor': annotation_text[color_index][0].color});
                  



                }
               
            },
            updateResizeHandlers = function (show) {
                if (! options.allowResize) {
                    return;
                }
                if (show) {
                    $.each($resizeHandlers, function(name, $handler) {
                        var top,
                            left,
                            semiwidth = Math.round($handler.width() / 2),
                            semiheight = Math.round($handler.height() / 2),
                            vertical = name[0],
                            horizontal = name[name.length - 1];

                        if (vertical === "n") {             // ====== North* ======
                            top = - semiheight;

                        } else if (vertical === "s") {      // ====== South* ======
                            top = area.height - semiheight - 1;

                        } else {                            // === East & West ===
                            top = Math.round(area.height / 2) - semiheight - 1;
                        }

                        if (horizontal === "e") {           // ====== *East ======
                            left = area.width - semiwidth - 1;

                        } else if (horizontal === "w") {    // ====== *West ======
                            left = - semiwidth;

                        } else {                            // == North & South ==
                            left = Math.round(area.width / 2) - semiwidth - 1;
                        }

                        $handler.css({
                            display: "block",
                            left: area.x + left,
                            top: area.y + top,
                            "z-index": area.z + 1
                        });
                    });
                } else {
                    $(".select-areas-resize-handler").each(function() {
                        $(this).css({ display: "none" });
                    });
                }
            },
            updateBtDelete = function (visible) {
                if ($btDelete) {

                    var left_width = 0;
                    left_width = (( area.x + area.width) + 1);
                     if( left_width >= $image.width() - area.x){
                         left_width = (( area.x + area.width) + 1) - (area.width ) ;
                     }
               
                    $btDelete.css({
                        display: visible ? "block" : "none",
                        left:left_width,
                        //left: area.x + area.width + 1,
                        top: area.y - $btDelete.outerHeight() - 1,
                        "z-index": area.z + 1
                    });
                }
            },
            updateCursor = function (cursorType) {
                $outline.css({
                    cursor: cursorType
                });

                $selection.css({
                    cursor: cursorType
                });

                $pointcenter.css({
                    cursor: cursorType
                });

                $pointneck.css({
                    cursor: cursorType
                });

                $pointrighttop.css({
                    cursor: cursorType
                });

                $pointrightbottom.css({
                    cursor: cursorType
                });




            },
            refresh = function(sender) {
                switch (sender) {
                    case "startSelection":
                        
                        parent._refresh();
                        updateSelection();
                        updateResizeHandlers();
                        updateBtDelete(true);
                        break;

                    case "pickSelection":

                    case "pickResizeHandler":
                        updateResizeHandlers();
                        break;

                    case "resizeSelection":
                        updateSelection();
                        updateResizeHandlers();
                        updateCursor("crosshair");
                        updateBtDelete(true);
                        break;

                    case "movePointCenterSelection":
                        updatePointSelection();

                    case "movePointNeckSelection":
                        updatePointNeckSelection();
                    
                    case "movePointRightTopSelection":
                        updatePointRightTopSelection();

                    case "movePointRightBottomSelection":
                        updatePointRightBottomSelection();
                    case "moveSelection":
                        updateSelection();
                        updateResizeHandlers();
                        updateCursor("move");
                        updateBtDelete(true);
                        break;

                    case "blur":
                        updateSelection();
                        updateResizeHandlers();
                        updateBtDelete();
                        break;

                    //case "releaseSelection":
                    default:
                        updateSelection();
                        updateResizeHandlers(true);
                        updateBtDelete(true);
                }
            },
            startSelection  = function (event) {
                
                cancelEvent(event);

                // Reset the selection size
                area.width = options.minSize[0];
                area.height = options.minSize[1];
                focus();

                
                on("move", resizeSelection);
                on("stop", releaseSelection);

                // Get the selection origin
                selectionOrigin = getMousePosition(event);
                if (selectionOrigin[0] + area.width > $image.width()) {
                    selectionOrigin[0] = $image.width() - area.width;
                }
                if (selectionOrigin[1] + area.height > $image.height()) {
                    selectionOrigin[1] = $image.height() - area.height;
                }
                // And set its position
                area.x = selectionOrigin[0];
                area.y = selectionOrigin[1];

                area.fi_idx = $image.attr('fi_idx');
                area.org_width = $image.attr('org_width');
                area.org_height = $image.attr('org_height');
                
                area.center_x = 0;
                area.center_y = 0;
                area.center_x2 = 0;
                area.center_y2 = 0;
                area.center_x3 = 0;
                area.center_y3 = 0;
                area.center_x4 = 0;
                area.center_y4 = 0;

                area.sel_mode = $('#sel_mode option:selected').val();
                area.sel_class = $('#sel_class option:selected').val();
                area.sel_type = $('#sel_type option:selected').val();
                area.sel_value = $('#sel_value option:selected').val();
                area.sel_barrier = $('#sel_barrier option:selected').val();
                area.sel_laneloc = $('#sel_laneloc option:selected').val();

                
                
                
                refresh("startSelection");



            },
            pointcenterSelection = function(Event){


                cancelEvent(event);
                focus();
                on("move", movePointCenterSelection);
                on("stop", releasePointCenterSelection);

                var mousePosition = getMousePosition(event);

                // Get the selection offset relative to the mouse position
                selectionOffset[0] = mousePosition[0] - area.x;
                selectionOffset[1] = mousePosition[1] - area.y;


                refresh("pointcenterSelection");

            },
            pointneckSelection = function(Event){

                
                cancelEvent(event);
                focus();
                on("move", movePointNeckSelection);
                on("stop", releasePointNeckSelection);

                // var mousePosition = getMousePosition(event);

                // // Get the selection offset relative to the mouse position
                // selectionOffset[0] = mousePosition[0] - area.x;
                // selectionOffset[1] = mousePosition[1] - area.y;


                refresh("pointneckSelection");

            },
            pointrighttopSelection = function(Event){

                
                cancelEvent(event);
                focus();
                on("move", movePointRightTopSelection);
                on("stop", releasePointRightTopSelection);

                // var mousePosition = getMousePosition(event);

                // // Get the selection offset relative to the mouse position
                // selectionOffset[0] = mousePosition[0] - area.x;
                // selectionOffset[1] = mousePosition[1] - area.y;


                refresh("pointrighttopSelection");

            },


            pointrightbottomSelection = function(Event){

                
                cancelEvent(event);
                focus();
                on("move", movePointRightBottomSelection);
                on("stop", releasePointRightBottomSelection);

                // var mousePosition = getMousePosition(event);

                // // Get the selection offset relative to the mouse position
                // selectionOffset[0] = mousePosition[0] - area.x;
                // selectionOffset[1] = mousePosition[1] - area.y;


                refresh("pointrighttopSelection");

            },



            pickSelection = function (event) {
                
                $('#sel_annotation').val(area.annotation);
                $('#sel_mode').val(area.sel_mode);

                currId = area.id;

                selModeChange(area.sel_mode);

                setTimeout(function(){

                    $('#sel_class').val(area.sel_class);
                    selClassChange(area.sel_class);

                    setTimeout(function(){

                        $('#sel_type').val(area.sel_type);
                        selTypeChange(area.sel_type);

                        $('#sel_value').val(area.sel_value);


                        $('#sel_barrier').val(area.sel_barrier);
                        $('#sel_laneloc').val(area.sel_laneloc);

                        
                    },50);


                },50);


                options.touched = true;
                cancelEvent(event);
                focus();
                on("move", moveSelection);
                on("stop", releaseSelection);

                var mousePosition = getMousePosition(event);

                // Get the selection offset relative to the mouse position
                selectionOffset[0] = mousePosition[0] - area.x;
                selectionOffset[1] = mousePosition[1] - area.y;

                refresh("pickSelection");

                
            },
            pickResizeHandler = function (event) {
                
                cancelEvent(event);
                focus();

                var card = event.target.className.split(" ")[1];
                if (card[card.length - 1] === "w") {
                    selectionOrigin[0] += area.width;
                    area.x = selectionOrigin[0] - area.width;
                }
                if (card[0] === "n") {
                    selectionOrigin[1] += area.height;
                    area.y = selectionOrigin[1] - area.height;
                }
                if (card === "n" || card === "s") {
                    resizeHorizontally = false;
                } else if (card === "e" || card === "w") {
                    resizeVertically = false;
                }

                on("move", resizeSelection);
                on("stop", releaseSelection);

                refresh("pickResizeHandler");

                
            },
            resizeSelection = function (event) {

                cancelEvent(event);
                focus();


                if (area.sel_mode == '' || area.sel_mode == '1' || area.sel_mode == '2' || area.sel_mode == '4' || area.sel_mode == '5'
                    || area.sel_mode == '6' || area.sel_mode == '7') {

                    var mousePosition = getMousePosition(event);

                    // Get the selection size
                    var height = mousePosition[1] - selectionOrigin[1],
                        width = mousePosition[0] - selectionOrigin[0];

                    // If the selection size is smaller than the minimum size set it to minimum size
                    if (Math.abs(width) < options.minSize[0]) {
                        width = (width >= 0) ? options.minSize[0] : - options.minSize[0];
                    }
                    if (Math.abs(height) < options.minSize[1]) {
                        height = (height >= 0) ? options.minSize[1] : - options.minSize[1];
                    }
                    // Test if the selection size exceeds the image bounds
                    if (selectionOrigin[0] + width < 0 || selectionOrigin[0] + width > $image.width()) {
                        width = - width;
                    }
                    if (selectionOrigin[1] + height < 0 || selectionOrigin[1] + height > $image.height()) {
                        height = - height;
                    }
                    // Test if the selection size is bigger than the maximum size (ignored if minSize > maxSize)
                    if (options.maxSize[0] > options.minSize[0] && options.maxSize[1] > options.minSize[1]) {
                        if (Math.abs(width) > options.maxSize[0]) {
                            width = (width >= 0) ? options.maxSize[0] : - options.maxSize[0];
                        }

                        if (Math.abs(height) > options.maxSize[1]) {
                            height = (height >= 0) ? options.maxSize[1] : - options.maxSize[1];
                        }
                    }

                    // Set the selection size
                    if (resizeHorizontally) {
                        area.width = width;
                    }
                    if (resizeVertically) {
                        area.height = height;
                    }
                    // If any aspect ratio is specified
                    if (options.aspectRatio) {
                        // Calculate the new width and height
                        if ((width > 0 && height > 0) || (width < 0 && height < 0)) {
                            if (resizeHorizontally) {
                                height = Math.round(width / options.aspectRatio);
                            } else {
                                width = Math.round(height * options.aspectRatio);
                            }
                        } else {
                            if (resizeHorizontally) {
                                height = - Math.round(width / options.aspectRatio);
                            } else {
                                width = - Math.round(height * options.aspectRatio);
                            }
                        }
                        // Test if the new size exceeds the image bounds
                        if (selectionOrigin[0] + width > $image.width()) {
                            width = $image.width() - selectionOrigin[0];
                            height = (height > 0) ? Math.round(width / options.aspectRatio) : - Math.round(width / options.aspectRatio);
                        }

                        if (selectionOrigin[1] + height < 0) {
                            height = - selectionOrigin[1];
                            width = (width > 0) ? - Math.round(height * options.aspectRatio) : Math.round(height * options.aspectRatio);
                        }

                        if (selectionOrigin[1] + height > $image.height()) {
                            height = $image.height() - selectionOrigin[1];
                            width = (width > 0) ? Math.round(height * options.aspectRatio) : - Math.round(height * options.aspectRatio);
                        }

                        // Set the selection size
                        area.width = width;
                        area.height = height;
                    }

                    if (area.width < 0) {
                        area.width = Math.abs(area.width);
                        area.x = selectionOrigin[0] - area.width;
                    } else {
                        area.x = selectionOrigin[0];
                    }
                    if (area.height < 0) {
                        area.height = Math.abs(area.height);
                        area.y = selectionOrigin[1] - area.height;
                    } else {
                        area.y = selectionOrigin[1];
                    }
                    

                } else if (area.sel_mode == '3') {


                    var mousePosition = getMousePosition(event);
                
                    // Get the selection size
                    var height = mousePosition[1] - selectionOrigin[1],
                        width = mousePosition[0] - selectionOrigin[0];


                    area.x = selectionOrigin[0];
                    area.y = selectionOrigin[1];
                    area.width = mousePosition[0] - area.x;
                    area.height = mousePosition[1] - area.y;
                }
                fireEvent("changing");
                refresh("resizeSelection");
            },
            movePointCenterSelection = function (event) {
                
                cancelEvent(event);
                if (! options.allowMove) {
                    return;
                }
                focus();

                pointCenterMove = true;

                var mousePosition = getMousePosition(event);
                moveToPointCenter({
                    x: mousePosition[0],
                    y: mousePosition[1]
                });

                fireEvent("changing");
            },

            movePointNeckSelection = function (event) {
                
                
                cancelEvent(event);
                if (! options.allowMove) {
                    return;
                }
                focus();

                pointNeckMove = true;

                var mousePosition = getMousePosition(event);
                moveToPointNeck({
                    x: mousePosition[0],
                    y: mousePosition[1]
                });

                fireEvent("changing");
            },


            movePointRightTopSelection = function (event) {
                
                
                cancelEvent(event);
                if (! options.allowMove) {
                    return;
                }
                focus();

                pointRightTopMove = true;

                var mousePosition = getMousePosition(event);
                moveToPointRightTop({
                    x: mousePosition[0],
                    y: mousePosition[1]
                });

                fireEvent("changing");
            },


            movePointRightBottomSelection = function (event) {
                
                
                cancelEvent(event);
                if (! options.allowMove) {
                    return;
                }
                focus();

                pointRightBottomMove = true;

                var mousePosition = getMousePosition(event);
                moveToPointRightBottom({
                    x: mousePosition[0],
                    y: mousePosition[1]
                });

                fireEvent("changing");
            },

            

            moveSelection = function (event) {
                options.touched = false;

                cancelEvent(event);
                if (! options.allowMove) {
                    return;
                }
                focus();

                var mousePosition = getMousePosition(event);
                moveTo({
                    x: mousePosition[0] - selectionOffset[0],
                    y: mousePosition[1] - selectionOffset[1]
                });

                fireEvent("changing");
            },
            moveTo = function (point) {
               
                // Set the selection position on the x-axis relative to the bounds
                // of the image
                if (point.x > 0) {
                    if (point.x + area.width < $image.width()) {
                        area.x = point.x;
                    } else {
                        area.x = $image.width() - area.width;
                    }
                } else {
                    area.x = 0;
                }
                // Set the selection position on the y-axis relative to the bounds
                // of the image
                if (point.y > 0) {
                    if (point.y + area.height < $image.height()) {
                        area.y = point.y;
                    } else {
                        area.y = $image.height() - area.height;
                    }
                } else {
                    area.y = 0;
                }
                refresh("moveSelection");
            },
            moveToPointCenter = function (point) {
                
                // Set the selection position on the x-axis relative to the bounds
                // of the image
                if (point.x > 0) {
                    // if (point.x + area.width < $image.width()) {
                    //     area.center_x = point.x;
                    // } else {
                    //     area.center_x = $image.width() - area.width;
                    // }
                    area.center_x = point.x;
                } else {
                    area.center_x = 0;
                }
                // Set the selection position on the y-axis relative to the bounds
                // of the image
                if (point.y > 0) {
                    // if (point.y + area.height < $image.height()) {
                    //     area.center_y = point.y;
                    // } else {
                    //     area.center_y = $image.height() - area.height;
                    // }
                    area.center_y = point.y;
                } else {
                    area.center_y = 0;
                }
                // area.center_x = area.width /2 + area.center_x;
                // area.center_y = area.height / 2 + area.center_y ;
                
               //console.log(point.x, point.y, area.center_x, area.center_y);

                refresh("movePointCenterSelection");
            },
            moveToPointNeck = function (point) {
                
                if (point.x > 0) {
                    area.center_x2 = point.x;
                } else {
                    area.center_x2 = 0;
                }
                if (point.y > 0) {
                    area.center_y2 = point.y;
                } else {
                    area.center_y2 = 0;
                }
                
                refresh("movePointNeckSelection");
            },
            moveToPointRightTop = function (point) {
                
                if (point.x > 0) {
                    area.center_x3 = point.x;
                } else {
                    area.center_x3 = 0;
                }
                if (point.y > 0) {
                    area.center_y3 = point.y;
                } else {
                    area.center_y3 = 0;
                }
                
                refresh("movePointRightTopSelection");
            },
            moveToPointRightBottom = function (point) {
                
                if (point.x > 0) {
                    area.center_x4 = point.x;
                } else {
                    area.center_x4 = 0;
                }
                if (point.y > 0) {
                    area.center_y4 = point.y;
                } else {
                    area.center_y4 = 0;
                }
                
                refresh("movePointRightBottomSelection");
            },


            releaseSelection = function (event) {
                cancelEvent(event);
                
                off("move", "stop");

                

                // Update the selection origin
                selectionOrigin[0] = area.x;
                selectionOrigin[1] = area.y;

                // Reset the resize constraints
                resizeHorizontally = true;
                resizeVertically = true;

                fireEvent("changed");
                refresh("releaseSelection");

                //console.log('change');
                var selValue = $('#sel_annotation  option:selected').val();
                
                if(selValue != ''){
                    area.annotation = selValue;
                    area.annotationName = annotation_text[selValue][0].text;
                    area.gp_idx = currGroup;
                }
                

                editer = false;
                displayObjects();
                
               
            },
            releasePointCenterSelection = function (event) {
                cancelEvent(event);
                
                off("move", "stop");

                
                if(area.sel_mode == '6' || area.sel_mode == '7') {
                    area.center_x = area.center_x;
                    area.center_y = area.center_y;


                    area.center_x2 = area.center_x2;
                    area.center_y2 = area.center_y2;


                    area.center_x3 = area.center_x3;
                    area.center_y3 = area.center_y3;
                    area.center_x4 = area.center_x4;
                    area.center_y4 = area.center_y4;


                    
                } else {
                    area.center_x = area.center_x - area.x;
                    area.center_y = area.center_y - area.y;
                }
                
                fireEvent("changed");

                refresh("releasePointCenterSelection");

                pointCenterMove = false;

                
            },

            releasePointNeckSelection = function (event) {
                cancelEvent(event);
                
                off("move", "stop");

                
                resizeHorizontally = true;
                resizeVertically = true;

                if(area.sel_mode == '6' || area.sel_mode == '7') {
                    area.center_x2 = area.center_x2;
                    area.center_y2 = area.center_y2;

                    area.center_x3 = area.center_x3;
                    area.center_y3 = area.center_y3;
                    area.center_x4 = area.center_x4;
                    area.center_y4 = area.center_y4;
                    
                } else {
                    area.center_x2 = area.center_x2 - area.x;
                    area.center_y2 = area.center_y2 - area.y;
                }
                
                

                fireEvent("changed");

                refresh("releasePointNeckSelection");

                pointNeckMove = false;

            },
            releasePointRightTopSelection = function (event) {
                cancelEvent(event);
                
                off("move", "stop");

                resizeHorizontally = true;
                resizeVertically = true;

                if(area.sel_mode == '6' || area.sel_mode == '7') {
                    area.center_x2 = area.center_x2;
                    area.center_y2 = area.center_y2;

                    area.center_x3 = area.center_x3;
                    area.center_y3 = area.center_y3;
                    area.center_x4 = area.center_x4;
                    area.center_y4 = area.center_y4;
                } else {
                    area.center_x2 = area.center_x2 - area.x;
                    area.center_y2 = area.center_y2 - area.y;
                }
                
                fireEvent("changed");
                refresh("releasePointRightTopSelection");

                pointRightTopMove = false;

            },
            releasePointRightBottomSelection = function (event) {
                cancelEvent(event);
                
                off("move", "stop");

                
                resizeHorizontally = true;
                resizeVertically = true;

                if(area.sel_mode == '6' || area.sel_mode == '7') {
                    area.center_x2 = area.center_x2;
                    area.center_y2 = area.center_y2;

                    area.center_x3 = area.center_x3;
                    area.center_y3 = area.center_y3;
                    area.center_x4 = area.center_x4;
                    area.center_y4 = area.center_y4;
                } else {
                    area.center_x2 = area.center_x2 - area.x;
                    area.center_y2 = area.center_y2 - area.y;
                }
                
                

                fireEvent("changed");

                refresh("releasePointRightBottomSelection");

                pointRightBottomMove = false;

            },






            deleteSelection = function (event) {
                
                cancelEvent(event);
                $selection.remove();
                $outline.remove();
                $pointneck.remove();
                $pointrighttop.remove();
                $pointrightbottom.remove();


                $line1.remove();
                $line2.remove();
                $line3.remove();
                $line4.remove();
                $pointcenter.remove();
                $.each($resizeHandlers, function(card, $handler) {
                    $handler.remove();
                });
                
                $btDelete.remove();
                parent._remove(id);
                fireEvent("changed");

                $('div[id^="pointerline_area_"]').each(function(){
                    $(this).remove();
                });

                displayObjects();
                
            },
            getElementOffset = function (object) {
                var offset = $(object).offset();

                return [offset.left, offset.top];
            },
            getMousePosition = function (event) {
                var imageOffset = getElementOffset($image);

                if (! event.pageX) {
                    if (event.originalEvent) {
                        event = event.originalEvent;
                    }

                    if(event.changedTouches) {
                        event = event.changedTouches[0];
                    }

                    if(event.touches) {
                        event = event.touches[0];
                    }
                }
                var x = event.pageX - imageOffset[0],
                    y = event.pageY - imageOffset[1];

                x = (x < 0) ? 0 : (x > $image.width()) ? $image.width() : x;
                y = (y < 0) ? 0 : (y > $image.height()) ? $image.height() : y;

                return [x, y];
            };

        // Initialize an outline layer and place it above the trigger layer
        $outline = $("<div class=\"select-areas-outline\" id=\"outline_area_"+area.id+"\"  />")
            .css({
                opacity : options.outlineOpacity,
                position : "absolute"
            })
            .insertAfter($trigger);
        
        
       //box 가운데 포인트를 잡아준다.center_x, center_y 
        ///var color_index = parseInt(area.annotation) + 1;
       
        //console.log(annotation_text[area.annotation])
        var color_index = 1;
        if ( $('#sel_annotation').val() != ''){
            color_index = parseInt($('#sel_annotation').val());
        }
      

        $pointcenter = $("<div style='display:none;border-radius:50%;z-index:999;cursor:move; width:8px;height:8px;' id=\"pointercenter_area_"+area.id+"\"  />")
            .css({
                position : "absolute",
                'backgroundColor': annotation_text[color_index][0].color
            })
            .insertAfter($trigger);
        
        $pointcenter.mousedown(pointcenterSelection).bind("touchstart", pointcenterSelection);

        $pointneck = $("<div style='display:none;border-radius:50%; z-index:999;cursor:move; width:8px;height:8px;' id=\"pointerneck_area_"+area.id+"\"  />")
            .css({
                position : "absolute",
                'backgroundColor': annotation_text[color_index][0].color
            })
            .insertAfter($trigger);
        $pointneck.mousedown(pointneckSelection).bind("touchstart", pointneckSelection);

        $pointrighttop = $("<div style='display:none;border-radius:50%; z-index:999;cursor:move; width:8px;height:8px;' id=\"pointerrighttop_area_"+area.id+"\"  />")
            .css({
                position : "absolute",
                'backgroundColor': annotation_text[color_index][0].color
            })
            .insertAfter($trigger);
        $pointrighttop.mousedown(pointrighttopSelection).bind("touchstart", pointrighttopSelection);

        $pointrightbottom = $("<div style='display:none;border-radius:50%; z-index:999;cursor:move; width:8px;height:8px;' id=\"pointerrightbottom_area_"+area.id+"\"  />")
            .css({
                position : "absolute",
                'backgroundColor': annotation_text[color_index][0].color
            })
            .insertAfter($trigger);
        $pointrightbottom.mousedown(pointrightbottomSelection).bind("touchstart", pointneckSelection);


        $line1 = $("<div style='display:none; z-index:999;' id=\"line1_area_"+area.id+"\"  />")
            .css({
                position : "absolute",
                'backgroundColor': annotation_text[color_index][0].color
            })
            .insertAfter($trigger);
            $line2 = $("<div style='display:none; z-index:999;' id=\"line2_area_"+area.id+"\"  />")
            .css({
                position : "absolute",
                'backgroundColor': annotation_text[color_index][0].color
            })
            .insertAfter($trigger);
            $line3 = $("<div style='display:none; z-index:999;' id=\"line3_area_"+area.id+"\"  />")
            .css({
                position : "absolute",
                'backgroundColor': annotation_text[color_index][0].color
            })
            .insertAfter($trigger);
            $line4 = $("<div style='display:none; z-index:999;' id=\"line4_area_"+area.id+"\"  />")
            .css({
                position : "absolute",
                'backgroundColor': annotation_text[color_index][0].color
            })
            .insertAfter($trigger);

        // Initialize a selection layer and place it above the outline layer
        $selection = $("<div id=\"background_area_"+area.id+"\"/>")
            //.addClass("select-areas-background-area")
            .css({
               // background : "#fff url(" + $image.attr("src") + ") no-repeat",
              //  backgroundSize : $image.width() + "px",
                position : "absolute"
            })
            .insertAfter($outline);

        // Initialize all handlers
        if (options.allowResize) {
            $.each(["nw", "n", "ne", "e", "se", "s", "sw", "w"], function (key, card) {
                $resizeHandlers[card] =  $("<div class=\"select-areas-resize-handler " + card + "\"/>")
                    .css({
                        opacity : 0.5,
                        position : "absolute",
                        cursor : card + "-resize"
                    })
                    .insertAfter($selection)
                    .mousedown(pickResizeHandler)
                    .bind("touchstart", pickResizeHandler);
            });
        }
        
        // initialize delete button
        if (options.allowDelete) {
            var bindToDelete = function ($obj) {
                $obj.click(deleteSelection)
                    .bind("touchstart", deleteSelection)
                    .bind("tap", deleteSelection);
                return $obj;
            };
            $btDelete = bindToDelete($("<div class=\"delete-area\" />"))
                .append(bindToDelete($("<div class=\"select-areas-delete-area\" />")))
                .insertAfter($selection);
        }

        if (options.allowMove) {
            $selection.mousedown(pickSelection).bind("touchstart", pickSelection);
        }

        focus();

        
       
        return {
            getData: getData,
            startSelection: startSelection,
            refresh: refresh,
            fireEvent:fireEvent,
            deleteSelection: deleteSelection,
            options: options,
            blur: blur,
            focus: focus,
            nudge: function (point) {
                point.x = area.x;
                point.y = area.y;
                if (point.d) {
                    point.y = area.y + point.d;
                }
                if (point.u) {
                    point.y = area.y - point.u;
                }
                if (point.l) {
                    point.x = area.x - point.l;
                }
                if (point.r) {
                    point.x = area.x + point.r;
                }
                moveTo(point);
                fireEvent("changed");

            },
            set: function (dimensions, silent) {
                area = $.extend(area, dimensions);
                selectionOrigin[0] = area.x;
                selectionOrigin[1] = area.y;
                if (! silent) {
                    fireEvent("changed");
                }

            
                $('#outline_area_'+ area.id).removeClass("select-areas-outline");
                //$("#background_area_" + area.id).removeClass("select-areas-background-area");
                //$("#background_area_" + area.id).addClass("select-areas-background-area-" + area.annotation);
                
               
                var color_index = parseInt(area.annotation) ;
               
                // var color_index = 1;
                // if ( $('#sel_annotation').val() != ''){
                //     color_index = parseInt($('#sel_annotation').val());
                // }
                    
                    if (area.sel_mode == '' || area.sel_mode == '1' || area.sel_mode == '2' || area.sel_mode == '4' || area.sel_mode == '5'
                    || area.sel_mode == '6' || area.sel_mode == '7') {
                        
                        $("#background_area_" + area.id).css({
                            'backgroundColor': annotation_text[color_index][0].color,
                            'opacity':'0.2',
                            'margin': '-1px 0 0 -1px',
                            'border': '2px solid ' + annotation_text[color_index][0].color
                        });
                       
                        $("#pointercenter_area_" + area.id).css({ 'backgroundColor': annotation_text[color_index][0].color});
                       
                        if(area.sel_mode=='7'){
                            $("#line1_area_" + area.id).css({ 'backgroundColor': annotation_text[color_index][0].color});
                            $("#line2_area_" + area.id).css({ 'backgroundColor': annotation_text[color_index][0].color});
                            $("#line3_area_" + area.id).css({ 'backgroundColor': annotation_text[color_index][0].color});
                            $("#line4_area_" + area.id).css({ 'backgroundColor': annotation_text[color_index][0].color});
                            $("#pointercenter_area_" + area.id).css({ 'backgroundColor': annotation_text[color_index][0].color});
                            $("#pointerneck_area_" + area.id).css({ 'backgroundColor': annotation_text[color_index][0].color});

                            $("#pointerrighttop_area_" + area.id).css({ 'backgroundColor': annotation_text[color_index][0].color});
                            $("#pointerrightbottom_area_" + area.id).css({ 'backgroundColor': annotation_text[color_index][0].color});
                        }

                    } else if(area.sel_mode=='3'){

                        var center_x =  area.x;
                        var center_y =  area.y;
                        var mouse_x =  (area.x+area.width) ;
                        var mouse_y = (area.y + area.height);
                        var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
                        var degrees = (radians * (180 / Math.PI) * -1);
                        var height_size = Math.sqrt(Math.pow(Math.abs(mouse_x-center_x),2) + Math.pow(Math.abs(mouse_y-center_y), 2));	

                        
                        $("#background_area_" + area.id).css({
                            'backgroundColor': annotation_text[color_index][0].color,
                            'opacity':'0.2',
                            'width':'3px',
                            'height':height_size,
                            'margin': '-1px 0 0 -1px',
                            'border': '1px solid ' + annotation_text[color_index][0].color,
                            '-webkit-transform' : 'rotate('+degrees+'deg)',
                            '-moz-transform' : 'rotate('+degrees+'deg)',  
                            '-ms-transform' : 'rotate('+degrees+'deg)',  
                            '-o-transform' : 'rotate('+degrees+'deg)',  
                            'transform' : 'rotate('+degrees+'deg)',  
                            'transform-origin': 'top left'
                        });

                     
                    }
                   
                
            },
            contains: function (point) {
                return (point.x >= area.x) && (point.x <= area.x + area.width) &&
                       (point.y >= area.y) && (point.y <= area.y + area.height);
            }
        };
    };


    $.imageSelectAreas = function() { };

    $.imageSelectAreas.prototype.init = function (object, customOptions) {

        var that = this,
            defaultOptions = {
                allowEdit: customOptions.edited,
                allowMove: customOptions.edited,
                pointCenterMove : false,
                allowResize: customOptions.edited,
                allowSelect: customOptions.edited,
                allowDelete: customOptions.edited,
                allowNudge: customOptions.edited,
                aspectRatio: 0,
                minSize: [0, 0],
                maxSize: [0, 0],
                width: 0,
                maxAreas: 0,
                outlineOpacity: 0.5,
                overlayOpacity: 0.2,
                areas: [],
                onChanging: null,
                onChanged: null,
                onSelect: null,
                touched : false
            };

        
            
        this.options = $.extend(defaultOptions, customOptions);

        if (! this.options.allowEdit) {
            this.options.allowSelect = this.options.allowMove = this.options.allowResize = this.options.allowDelete = false;
        }

        this._areas = {};

        // Initialize the image layer
        this.$image = $(object);
        
        this.ratio = 1;
        if (this.options.width && this.$image.width() && this.options.width !== this.$image.width()) {
            this.ratio = this.options.width / this.$image.width();
            this.$image.width(this.options.width);
        }

        if (this.options.onChanging) {
            this.$image.on("changing", this.options.onChanging);
        }
        if (this.options.onChanged) {

            
            this.$image.on("changed", this.options.onChanged);
        }
        if (this.options.onSelect) {
            this.$image.on("select", this.options.onSelect);
        }
        if (this.options.onLoaded) {
            this.$image.on("loaded", this.options.onLoaded);
        }

        // Initialize an image holder
        this.$holder = $("<div />")
            .css({
                position : "relative",
                width: this.$image.width(),
                height: this.$image.height()
            });

        // Wrap the holder around the image
        this.$image.wrap(this.$holder)
            .css({
                position : "absolute"
            });

        // Initialize an overlay layer and place it above the image
        this.$overlay = $("<div class=\"select-areas-overlay\" />")
            .css({
                opacity : this.options.overlayOpacity,
                position : "absolute",
                width: this.$image.width(),
                height: this.$image.height()
            })
            .insertAfter(this.$image);

        // Initialize a trigger layer and place it above the overlay layer
        this.$trigger = $("<div />")
            .css({
                backgroundColor : "#000000",
                opacity : 0,
                position : "absolute",
                width: this.$image.width(),
                height: this.$image.height()
            })
            .insertAfter(this.$overlay);
        
        $.each(this.options.areas, function (key, area) {
            that._add(area, true);
        });


        this.blurAll();
        this._refresh();

        if (this.options.allowSelect) {
            // Bind an event handler to the "mousedown" event of the trigger layer
            this.$trigger.mousedown($.proxy(this.newArea, this)).on("touchstart", $.proxy(this.newArea, this));
        }
        if (this.options.allowNudge) {
            $('html').keydown(function (e) { // move selection with arrow keys
                var codes = {
                        37: "l",
                        38: "u",
                        39: "r",
                        40: "d"
                    },
                    direction = codes[e.which],
                    selectedArea;

                if (direction) {
                    that._eachArea(function (area) {
                        if (area.getData().z === 100) {
                            selectedArea = area;
                            return false;
                        }
                    });
                    if (selectedArea) {
                        var move = {};
                        move[direction] = 1;
                        selectedArea.nudge(move);
                    }
                }
            });
        }
    };

    $.imageSelectAreas.prototype._refresh = function () {
       
        var nbAreas = this.areas().length;
        this.$overlay.css({
            display : nbAreas? "block" : "none"
        });
        // if (nbAreas) {
        //     this.$image.addClass("blurred");
        // } else {
        //     this.$image.removeClass("blurred");
        // }
        this.$trigger.css({
            cursor : this.options.allowSelect ? "crosshair" : "default"
        });
    };

    $.imageSelectAreas.prototype._eachArea = function (cb) {
        $.each(this._areas, function (id, area) {
            if (area) {
                return cb(area, id);
            }
        });
    };

    $.imageSelectAreas.prototype._remove = function (id) {
       
        delete this._areas[id];
        this._refresh();


        $('div[id^="select_"]').each(function(){
			$(this).hide();
        });
        currId = '-1';
        currAreas = [];
        $.each(this._areas, function (id, area) {
            if (area) {
                currAreas.push(area.getData());
            }
        });
    };

    $.imageSelectAreas.prototype.remove = function (id) {
        if (this._areas[id]) {
            this._areas[id].deleteSelection();
            
        }
    };

    $.imageSelectAreas.prototype.newArea = function (event) {
        
        var id = -1;
        this.blurAll();
        if (this.options.maxAreas && this.options.maxAreas <=  this.areas().length) {
            return id;
        }

        this._eachArea(function (area, index) {
            id = Math.max(id, parseInt(index, 10));
        });
        id+=1;

        this._areas[id] = $.imageArea(this, id);
        

        if (event) {

            // var chk1 = $('#sel_mode option:selected').val();
            // var chk2 = $('#sel_annotation option:selected').val();
            // if(chk1 =='' || chk2 =='') {
            //     this._areas[id].deleteSelection();
            //     alert('옵션을 먼저 선택해주세요!');
            //     return;
            // }

            var chk2 = $('#sel_annotation option:selected').val();
            if(chk2 =='') {
                this._areas[id].deleteSelection();
                alert('옵션을 먼저 선택해주세요!');
                return;
            }

        
            this._areas[id].startSelection(event);
        }
        return id;
    };

    $.imageSelectAreas.prototype.set = function (id, options, silent) {
        if (this._areas[id]) {
            options.id = id;
            this._areas[id].set(options, silent);
            this._areas[id].focus();
        }
    };

    $.imageSelectAreas.prototype._add = function (options, silent) {
        
        var id = this.newArea();
        this.set(id, options, silent);
    };

    $.imageSelectAreas.prototype.add = function (options) {
        var that = this;
        this.blurAll();
        if ($.isArray(options)) {
            $.each(options, function (key, val) {
                that._add(val);
            });
        } else {
            this._add(options);
        }
        this._refresh();
        if (! this.options.allowSelect && ! this.options.allowMove && ! this.options.allowResize && ! this.options.allowDelete) {
            this.blurAll();
        }
    };

    $.imageSelectAreas.prototype.reset = function () {
        var that = this;
        this._eachArea(function (area, id) {
            that.remove(id);
        });
        this._refresh();
    };

    $.imageSelectAreas.prototype.newreset = function () {
       
        this.blurAll();

        $('#sel_mode').val("");
        $('#sel_annotation').val("")
        $('#sel_type').empty();
        $('#sel_value').empty();
        $('#sel_class').empty();

        $('#barrier_div').hide();
        $('#laneloc_div').hide();


        $('#sel_class').append('<option value="">선택(Class)</option>');
        $('#sel_type').append('<option value="">선택(Type)</option>');
        $('#sel_value').append('<option value="">선택(Value)</option>');
        
        
        // $('div[id^="pointerline_area_"]').each(function(){
        //     $(this).remove();
        // });

        currId = '';


        
    };
    

    $.imageSelectAreas.prototype.destroy = function () {
        $('div[id^="select_"]').each(function(){
			$(this).hide();
        });
        
        this.reset();
        this.$holder.remove();
        this.$overlay.remove();
        this.$trigger.remove();
        this.$image.css("width", "").css("position", "").unwrap();
        this.$image.removeData("mainImageSelectAreas");
    };

    $.imageSelectAreas.prototype.areas = function () {
        
        var ret = [];
        //var nCnt = 0;
        this._eachArea(function (area) { 
            var obj = area.getData();
            //obj.id = nCnt; 
            ret.push(obj);
            //nCnt++;
        });
        return ret;
    };

    $.imageSelectAreas.prototype.annotationAreas = function (selValue) {
        console.log(selValue);
        this._areas[selValue].focus();
        
    };


    $.imageSelectAreas.prototype.relativeAreas = function () {
        var areas = this.areas(),
            ret = [],
            ratio = this.ratio,
            scale = function (val) {
                return Math.floor(val / ratio);
            };

        for (var i = 0; i < areas.length; i++) {
            ret[i] = $.extend({}, areas[i]);
            ret[i].x = scale(ret[i].x);
            ret[i].y = scale(ret[i].y);
            ret[i].width = scale(ret[i].width);
            ret[i].height = scale(ret[i].height);
        }
        return ret;
    };

    $.imageSelectAreas.prototype.blurAll = function () {
        this._eachArea(function (area) {
            area.blur();
        });
    };

    $.imageSelectAreas.prototype.contains  = function (point) {
        var res = false;
        this._eachArea(function (area) {
            if (area.contains(point)) {
                res = true;
                return false;
            }
        });
        return res;
    };
    

    $.selectAreas = function(object, options) {
        var $object = $(object);
        
        if (! $object.data("mainImageSelectAreas")) {
            var mainImageSelectAreas = new $.imageSelectAreas();
            mainImageSelectAreas.init(object, options);
            $object.data("mainImageSelectAreas", mainImageSelectAreas);
            $object.trigger("loaded");
        }
        return $object.data("mainImageSelectAreas");
    };

   

    $.fn.selectAreas = function(customOptions) {
        
        if ( $.imageSelectAreas.prototype[customOptions] ) { // Method call
            var ret = $.imageSelectAreas.prototype[ customOptions ].apply( $.selectAreas(this), Array.prototype.slice.call( arguments, 1 ));
            return typeof ret === "undefined" ? this : ret;

        } else if ( typeof customOptions === "object" || ! customOptions ) { // Initialization
            //Iterate over each object
            // this.each(function() {
            //     console.log
            //     var currentObject = this,
            //         image = new Image();

            //     // And attach selectAreas when the object is loaded
            //     image.onload = function() {
            //         $.selectAreas(currentObject, customOptions);
            //     };

            //     // Reset the src because cached images don"t fire load sometimes
            //     image.src = currentObject.src;

            // });
            // this.each(function(){
            //     var currentObject = this;
            //     console.log(currentObject);
            //    //imageArray[0];
            // });

            $.selectAreas(this, customOptions);
            
            return this;

        } else {
            $.error( "Method " +  customOptions + " does not exist on jQuery.selectAreas" );
        }
    };
}) (jQuery);
