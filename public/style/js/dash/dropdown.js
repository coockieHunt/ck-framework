let class_parent = '.sidenav .dropdown';
let class_child = '.dropdown-content';
let show_hide_time = 150;

$(class_parent).click(function (event) {
    let dropdown_content = $(this).find(class_child);
    if (dropdown_content.is(":visible")) {
        dropdown_content.hide(show_hide_time);
    } else {
        dropdown_content.show(show_hide_time);
    }
});

