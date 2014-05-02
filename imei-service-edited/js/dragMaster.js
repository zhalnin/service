/**
 * Created by zhalnin on 26/04/14.
 */
function DragObject( element, elem_resize, iframe_resize ) {
    element.dragObject = this;

    dragMaster.makeDraggable(element);

    this.onDragStart = function( offset ) {
        var s = element.style;
//        s.position = 'absolute';
        mouseOffset = offset;
    };

    this.onDragMove = function(x, y, startY ) {


        console.log( AM.Position.fullHeight(elem_resize) );
//        console.log( AM.Position.fullHeight(iframe_resize) );
//        console.log( startY );
        console.log( y - startY );


        elem_resize.style.height =  AM.Position.fullHeight(elem_resize) + ( y - startY ) - mouseOffset.y + 'px';
//        elem_resize.style.height = y - mouseOffset.y + 'px';
//        iframe_resize.style.height = y - mouseOffset.y - 60 + 'px';
    };

    this.toString = function() {
        return element.id;
    };
}

var dragMaster = (function() {

    var dragObject,
        mouseDownAt,
        startY;

    function mouseDown(e) {
//        console.log('mouseDown');
        e = AM.Event.fixEventMouse(e);
        if(e.which != 1 ) return;
        mouseDownAt = { x: e.pageX , y: e.pageY, element: this };
        startY = e.pageY;
        addDocumentEventHandlers();

        return false;
    }

    function mouseMove(e) {
//        console.log('mouseMove');
        e = AM.Event.fixEventMouse(e);
        if( mouseDownAt ) {
//            if( Math.abs(mouseDownAt.x - e.pageX) < 5 && Math.abs(mouseDownAt.y - e.pageY ) < 5 ) {
//                return false;
//            }
            var elem = mouseDownAt.element;
            dragObject = elem.dragObject;
            var mouseOffset = AM.Event.getMouseOffset( elem, mouseDownAt.x, mouseDownAt.y );
            mouseDownAt = null;


            dragObject.onDragStart( mouseOffset );
        }


//        console.log(e.pageY);
        dragObject.onDragMove(e.pageX, e.pageY, startY );
        return false;
    }

    function mouseUp() {
        console.log('mouseUp');
        if( ! dragObject ) {
            mouseDownAt = null
        }
        removeDocumentEventHandlers();
    }

    function addDocumentEventHandlers() {
        AM.DOM.$('iframe_redactor').onmouseover = mouseUp;
        document.onmousemove = mouseMove;
        window.onmouseup = mouseUp;
        document.ondragstart = document.body.onselectstart = function() { return false; }
    }

    function removeDocumentEventHandlers() {
        document.onmousemove = window.onmouseup = document.ondragstart = document.body.onselectstart = null;
    }


    return  {
        makeDraggable: function( element ) {
//            console.log(element);
            element.onmousedown = mouseDown;
        }
    }
}());