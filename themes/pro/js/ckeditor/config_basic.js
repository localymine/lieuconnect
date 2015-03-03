/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function(config) {
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    config.uiColor = '#1BBAE1';

    // http://ckeditor.com/latest/samples/plugins/toolbar/toolbar.html
    // Toolbar configuration generated automatically by the editor based on config.toolbarGroups.
    config.toolbar = [
        {name: 'document', groups: ['mode', 'document', 'doctools'], items: ['Source', '-', 'Templates']},
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']},
        {name: 'colors', items: ['TextColor', 'BGColor']},
    ];
    
    config.enterMode = CKEDITOR.ENTER_BR;
    config.toolbarCanCollapse = true;
    config.height = 100;
};
