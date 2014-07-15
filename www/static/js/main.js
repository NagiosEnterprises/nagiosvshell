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

    nav.open = function(){
        $('body')
            .removeClass('sidebar-closed')
            .addClass('sidebar-open');
    }

    nav.close = function(){
        $('body')
            .removeClass('sidebar-open')
            .addClass('sidebar-closed');
    }

    nav.get_cookie = function(){
        return $.cookie('vshell2_nav');
    }

    nav.set_cookie = function(value){
        $.cookie('vshell2_nav', value, { expires: 365, path: '/' });
    }

    nav.bind = function(){
        var button_open = $('#nav-button-open'),
            button_close = $('#nav-button-close');

        button_open.on('click', function(){
            nav.set_cookie('open');
            nav.open();
        });

        button_close.on('click', function(){
            nav.set_cookie('close');
            nav.close();
        });
    }

    nav.load = function(){
        var mobile_breakpoint = '480',
            is_mobile, was_open;

        is_mobile = $(window).width() < mobile_breakpoint ? true : false;
        was_open = nav.get_cookie() == 'open' ? true : false;

        nav.bind();

        if( is_mobile ){
            nav.close();
        }else{
            if( was_open ){
                nav.open();
            }else{
                nav.close();
            }
        }
    }


// Document ready

    $(document).ready(function(){
        nav.load();
        tables.pagesize.bind();
    });
