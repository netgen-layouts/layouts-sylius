(function() {
    if(document.querySelector('.ng-layouts-app')) {
        const layoutApp = document.querySelector('.ng-layouts-app');
        const layoutContent = layoutApp.querySelector('.layouts-content');
        let newHeight = window.innerHeight - layoutContent.getBoundingClientRect().top;
        layoutContent.style.minHeight = newHeight + 'px';
    }
})();
