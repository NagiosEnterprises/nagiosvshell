    /*nav dropdown functions */

    /* browser detection */
    if (document.layers) {
        visible = "show";
        hidden = "hide";
    }
    if (document.all || document.getElementById) {
        visible = "visible";
        hidden = "hidden";
    }

    function showDropdown(id)
    {
        if (document.layers)
        {
            menu = document.layers[id];
        }

        if(document.getElementById)
        {
            menu = document.getElementById(id);
        }

        if(menu)
        {
            menu.style.visibility = visible;
        }

    }

    function hideDropdown(id)
    {
        menu = document.getElementById(id);
        if(menu)
        {
            menu.style.visibility = hidden;
        }

    }

    /*this function toggles the grids and configuration tables */
    function showHide(id)
    {
        var divID = "#"+id;
        $(divID).slideToggle("fast");
    }

    /*this function hides the grids and configuration tables that can be toggled*/
    function hide()
    {
        $(".hidden").hide();
    }
