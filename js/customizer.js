console.log('customizer js')

function toggleControlVisibility(what){
    let controls    = Object.entries(wp.customize.settings.controls).filter(([key, value]) => key.includes(what));

    controls.forEach(control => {
        console.log(control[0])
        if(wp.customize.control( control[0] ).active() ){
            console.log(control[1].active)
            wp.customize.control( control[0] ).deactivate()
        }else{
            console.log(control[1].active)
            wp.customize.control( control[0] ).activate()
        }
    });
}

function addListeners(){
    if(wp.customize.settings == undefined){
        setTimeout(addListeners, 100);
        return;
    }

    let postTypeControls    = Object.entries(wp.customize.settings.controls).filter(([key, value]) => key.includes('posttypes'));

    postTypeControls.forEach(name =>{
        wp.customize.control( name[0], function( control ) {
            control.container[0].addEventListener('change', ev => {
                console.log(ev.target);
                toggleControlVisibility(ev.target.dataset.customizeSettingLink.replace('posttypes', 'categories'));
                toggleControlVisibility(ev.target.dataset.customizeSettingLink.replace('posttypes', 'labels'));
            });
        });
    });
}

document.addEventListener('DOMContentLoaded', ev => {
    setTimeout(addListeners, 100);

});