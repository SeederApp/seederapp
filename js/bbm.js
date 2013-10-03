function registerForBBM(){
    blackberry.event.addEventListener('onaccesschanged', function (accessible, status) {
            if (status === 'unregistered') {
                blackberry.bbm.platform.register({
                        uuid: 'acf208c3-cb23-44d5-b8a2-d2ecad9a6f2c'
                });
            } else if (status === 'allowed') {
                bbm.registered = accessible;
            }
    }, false);

}


function inviteOverBBM() {
    blackberry.bbm.platform.users.inviteToDownload();
}