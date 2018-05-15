(function () {
    tinymce.create('tinymce.plugins.leadpages_leadboxes', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init: function (ed, url) {

            var leadboxesArray = window.leadboxes;
            var leadboxesMenu = [];

            jQuery(leadboxesArray).each(function (index, leadbox) {
                leadboxesMenu.push({
                    'text': leadbox.name,
                    'onclick': function () {
                        return_text = '[leadpages_leadbox leadbox_id=' + leadbox.xor_id + '] [/leadpages_leadbox]';
                        ed.insertContent(return_text);
                    }
                });
            });

            ed.addButton('add_leadbox', {
                title: 'Leadboxes',
                type: 'menubutton',
                cmd: 'addLeadboxShortCode',
                icon: 'leadbox_shortcode_icon',
                menu: leadboxesMenu,
            });

        },

        /**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl: function (n, cm) {
            return null;
        },

        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo: function () {
            return {
                longname: 'Leadbox Buttons',
                author: 'Brandon Braner',
                authorurl: 'https://leadpages.net',
                infourl: 'https://leadpages.net',
                version: "0.1"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('leadpages_leadboxes', tinymce.plugins.leadpages_leadboxes);
})();