(function() {
    var layoutContent = $('.ng-layouts-app .layouts-content');
    var mainContent = $('#content');
    var newHeight = window.innerHeight - layoutContent.offset().top;
    newHeight = newHeight - (mainContent.innerWidth() - mainContent.width())/2;

    layoutContent.css('min-height', newHeight + 'px');
})();
