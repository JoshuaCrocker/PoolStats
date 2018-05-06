/**
 * Load Bootstrap and Modernizr (just checking Input Types)
 */

require('./bootstrap');
require('./modernizr');

/**
 * Register date pickers using the Pikaday library
 */
if (!Modernizr.inputtypes.date) {
    var Pikaday = require('pikaday');

    $('input[type=date]').each(function(el) {
        new Pikaday({ field: this });
    });
}
