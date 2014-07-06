// Nav

    nav = {};

    nav.bind = function(){
        var button_open = $('#nav-button-open'),
            button_close = $('#nav-button-close'),
            sidebar = $('#sidebar'),
            body = $('body');

        button_close.show();
        button_open.hide();

        button_open.on('click', function(){
            sidebar.show();
            button_close.show();
            button_open.hide();
            body
                .removeClass('sidebar-closed')
                .addClass('sidebar-open');
        });

        button_close.on('click', function(){
            sidebar.hide();
            button_close.hide();
            button_open.show();
            body
                .removeClass('sidebar-open')
                .addClass('sidebar-closed');
        });
    }


// Document ready

    $(document).ready(function(){
        nav.bind();
    });
