// Messages

    var messages = (function($){

        var add = function(type, text){
            var container = $('#messages'),
                message;

            message = [
                '<div class="message ' + type + '">',
                '<strong>Error</strong> ' + text,
                '</div>'
            ].join('\n');

            $(message).appendTo(container);
        }

        return {
            error: function(text){
                add('error', text);
            }
        }

    })(jQuery);


// Quicksearch

    var quicksearch = (function($){

        var templates = {};

            templates.empty = [
                '<div class="empty-message">',
                'No quicksearch matches found',
                '</div>'
            ].join('\n');

            templates.suggestion = function(data){
                return [
                    '<div class="quicksearch-item">',
                        '<span class="type">' + data.type + '</span>',
                        '<span class="value">' + data.name + '</span>',
                    '</div>'
                ].join('\n');
            }

        var get_path = function(type, uri){
            return '/hosts/' + uri;
        }

        var init = function(data, callback){

            $('#quicksearch .input').typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            },
            {
                name: 'quicksearch',
                displayKey: 'name',
                source: matcher(data),
                templates: {
                    empty: templates.empty,
                    suggestion: templates.suggestion
                }
            });

            $('#quicksearch .input').on('typeahead:selected', function(e, item){
                callback(e, item);
            });

        }

        var matcher = function(items){

            return function(q, cb) {
                var matches = [],
                    re = new RegExp(q, 'i');

                $.each(items, function(i, item) {
                    var value = item.name;

                    if (re.test(value)) {
                        matches.push(item);
                    }
                });

                cb(matches);
            };

        }

        return {
            init: function(data, callback){
                init(data, callback);
            }
        }

    })(jQuery);


// FooTable

    var tables = {};

    tables.pagesize = {};

    tables.pagesize.bind = function(){
        $('body').on('click', '.pagesize-container a', function(e){
            var pagesize = $(this).data('page-size'),
                options = $(this).siblings('a'),
                table = $(this).parents('.table-container').find('.footable');

            e.preventDefault();

            $(this).addClass('active');
            options.removeClass('active');

            table
                .data('page-size', pagesize)
                .data('footable')
                .redraw();
        });
    }


// Nav

    var nav = (function($){

        var cookie_name = 'vshell2_nav',
            mobile_breakpoint = 580,
            button_open_name = '#nav-button-open',
            button_close_name = '#nav-button-close';

        var open = function(){
            $('body')
                .removeClass('sidebar-closed')
                .addClass('sidebar-open');
        }

        var close = function(){
            $('body')
                .removeClass('sidebar-open')
                .addClass('sidebar-closed');
        }

        var get_cookie = function(){
            return $.cookie(cookie_name);
        }

        var set_cookie = function(value){
            $.cookie(cookie_name, value, { expires: 365, path: '/' });
        }

        var is_mobile = function(){
            return $(window).width() < mobile_breakpoint
        }

        var was_open = function(){
            return get_cookie() === 'open';
        }

        var load = function(){
            if( is_mobile() ){
                close();
            }else{
                if( was_open() ){
                    open();
                }else{
                    close();
                }
            }
        }

        var bind = function(){
            $('body').on('click', button_open_name, function(e){
                e.preventDefault();
                set_cookie('open');
                open();
            });

            $('body').on('click', button_close_name, function(e){
                e.preventDefault();
                set_cookie('close');
                close();
            });
        }

        return {
            init: function(){
                load();
                bind();
            },
            open: function(){
                open();
            }
        }

    })(jQuery);


// Colorscheme

    var colorscheme = (function($){

        var cookie_name = 'vshell2_colorscheme',
            buttons = '.colorscheme-choice',
            default_scheme = 'colorscheme-dark';

        var click = function(){
            var color = get_cookie() || default_scheme;
            $('body')
                .removeClass('colorscheme-dark')
                .removeClass('colorscheme-blue')
                .addClass(color);
        }

        var get_cookie = function(){
            return $.cookie(cookie_name);
        }

        var set_cookie = function(value){
            $.cookie(cookie_name, value, { expires: 365, path: '/' });
        }

        var load = function(){
            click();
        }

        var bind = function(nav){
            $('body').on('click', buttons, function(e){
                var color = $(this).attr("title");
                e.preventDefault();
                set_cookie(color);
                nav.open();
                click();
            });
        }

        return {
            init: function(){
                bind(nav);
                load();
            }
        }

    })(jQuery);


// Document ready

    $(document).ready(function(){
        nav.init();
        colorscheme.init(nav);
        tables.pagesize.bind();
    });
