const jQuery = require('jquery');

(function ($) {

    $(document).ready(function() {
       let buttonHome = $('#theme-home');
       let buttonAway = $('#theme-away');
       let buttonThird = $('#theme-third');
       let styleLink = $('#wenger-lives-on-sass-css');
       let themeDir = 'http://arsenalthessaloniki.local/wp-content/themes/wenger-lives-on/dist/'

       buttonHome.on('click', function() {
           styleLink.attr('href', `${themeDir}style-home.css`);
       });

        buttonAway.on('click', function() {
            styleLink.attr('href', `${themeDir}style-away.css`);
        });

        buttonThird.on('click', function() {
            styleLink.attr('href', `${themeDir}style-third.css`);
        });
    });
})(jQuery);