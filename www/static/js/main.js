// FooTable

    var tables = {};

    tables.pagesize = {};

    tables.pagesize.bind = function(){
        $('.pagesize-container a').on('click', function(e){
            var pagesize = $(this).data('page-size'),
                options = $(this).siblings('a'),
                table = $(this).parents('.table-container').find('.footable');

            $(this).addClass('active');
            options.removeClass('active');

            table
                .data('page-size', pagesize)
                .data('footable')
                .redraw();

            e.preventDefault();
        });
    }


// Nav

    nav = {};

    nav.bind = function(){
        var button_open = $('#nav-button-open'),
            button_close = $('#nav-button-close'),
            body = $('body');

        button_open.on('click', function(){
            body
                .removeClass('sidebar-closed')
                .addClass('sidebar-open');
        });

        button_close.on('click', function(){
            body
                .removeClass('sidebar-open')
                .addClass('sidebar-closed');
        });
    }


// Document ready

    $(document).ready(function(){
        nav.bind();
        tables.pagesize.bind();
    });
