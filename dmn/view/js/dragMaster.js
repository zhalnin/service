/**
 * Created by zhalnin on 26/04/14.
 */
function DragMove( element ) {
    element.dragElement = this;
    dragMaster.makeDraggable( element );

    this.onStart = function(offset ) {
        mouseOffset = offset;
    };

    this.onMove = function( x, y, width, height ) {
        element.style.height = height + y - mouseOffset.y - AM.Position.getElementTop(element) + 'px';
//        element.style.width = width + x - mouseOffset.x - AM.Position.getElementLeft(element) + 'px';
        AM.DOM.$('iframe_redactor').style.height = height + y - mouseOffset.y - AM.Position.getElementTop(element) - 60 + 'px';
//        AM.DOM.$('iframe_redactor').style.width = width + x - mouseOffset.x - AM.Position.getElementLeft(element) + 'px';
        if( parseInt(element.style.height ) <= 260 ) {
            element.style.height = 260 + 'px';
            AM.DOM.$('iframe_redactor').style.height = 200 + 'px';
        }
    };

}







var dragMaster = (function() {
    var dragElement,
        mousePoint,
        height,
        width;

    function mouseDown( e ) {
        AM.DOM.$('iframe_redactor').style.visibility = 'hidden';
        e = AM.Event.fixEventMouse( e );
        if(e.which != 1 ) { return; }
        mousePoint = { x: e.pageX, y: e.pageY, element: this };
        width = AM.Position.fullWidth(this);
        height = AM.Position.fullHeight(this);
        addDocumentEventHandlers();
        return false;
    }


    function mouseMove( e ) {
        e = AM.Event.fixEventMouse( e );
        if( mousePoint ) {
            var elem = mousePoint.element;
            dragElement = elem.dragElement;
            var mouseOffset = AM.Event.getMouseOffset( elem, mousePoint.x, mousePoint.y );
            mousePoint = null;
            dragElement.onStart( mouseOffset );
        }

        dragElement.onMove(e.pageX, e.pageY, width, height );
        return false;
    }



    function mouseUp( ) {
        AM.DOM.$('iframe_redactor').style.visibility = 'visible';
        if( !dragElement ) {
            mousePoint = null;
        }
        removeDocumentEventHandlers();
    }



    function addDocumentEventHandlers() {
        document.onmousemove = mouseMove;
        document.onmouseup = mouseUp;

    }

    function removeDocumentEventHandlers() {
        document.onmousemove = document.onmouseup = null;

    }

    return {
        makeDraggable: function( element ) {
            element.onmousedown = mouseDown;
        }
    }
}());