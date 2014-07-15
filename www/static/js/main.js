// Quicksearch

    quicksearch = {};

    quicksearch.matcher = function(strs){

        return function(q, cb) {
            var matches, substrRegex;

            // an array that will be populated with substring matches
            matches = [];

            // regex used to determine if a string contains the substring `q`
            substrRegex = new RegExp(q, 'i');

            // iterate through the pool of strings and for any string that
            // contains the substring `q`, add it to the `matches` array
            $.each(strs, function(i, str) {
                if (substrRegex.test(str)) {
                    // the typeahead jQuery plugin expects suggestions to a
                    // JavaScript object, refer to typeahead docs for more info
                    matches.push({ value: str });
                }
            });

            cb(matches);
        };

    }

    quicksearch.bind = function(){

        var states = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
            'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
            'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
            'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
            'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
            'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
            'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
            'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
            'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
        ];

        $('#quicksearch .input').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'states',
            displayKey: 'value',
            source: quicksearch.matcher(states),
            templates: {
                empty: [
                    '<div class="empty-message">',
                    'No quicksearch matches found',
                    '</div>'
                ].join('\n')
            }
        });
    }


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
        quicksearch.bind();
        tables.pagesize.bind();
    });
