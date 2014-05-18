// Layout

    var layout = {};

    layout.update_column_heights = function(){
        var main = $('#main'),
            sidebar = $('#sidebar'),
            browser = $(window),
            heights = {};

        main.css('height', 'auto');
        sidebar.css('height', 'auto');

        heights.main = main.height();
        heights.sidebar = sidebar.height();
        heights.browser = browser.height();

        if ( heights.browser > heights.main && heights.browser > heights.sidebar ) {
            sidebar.css('min-height', heights.browser + 'px');
            main.css('height', heights.browser + 'px');
        } else if ( heights.main > heights.sidebar ) {
            sidebar.css('min-height', heights.main + 'px');
        } else {
            main.css('min-height', heights.sidebar + 'px');
        }
    }

    layout.bind = function(){
        $(window).on('resize', function(){ layout.update_column_heights(); });
        $(window).trigger('resize');
        setTimeout(function(){ $(window).trigger('resize'); }, 1000);
    }


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
        layout.bind();
    });
