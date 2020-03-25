
const applicationServerPrivateKey = '-RNcl0JrHIBEtJQam_TCtSXTS9TqaC4_PoBFPHD3MI8';
/* eslint-enable max-len */
const applicationServerPublicKey = 'BIxJlV4yegrfHtcd7aG1Qdh2mntfDCX0V2NKkdR8w9SN9ohuVvfxuArbwSuPghUIWqCJ1qDalCVhvjL1M7-YBdo';
  
if ("Notification" in window && "PushManager" in window) {
    
    if(Notification.permission === 'granted' && "serviceWorker" in navigator){
        console.log('Service worker is running');
        subscribeUserToPush();
    }
    if(Notification.permission === 'granted' && !("serviceWorker" in navigator)){
       navigator.serviceWorker.register('/serviceWorker.js');
    }
    
}


function requestPermission() {
  return new Promise(function(resolve, reject) {
    const permissionResult = Notification.requestPermission(function(result) {
      // Handling deprecated version with callback.
      resolve(result);
    });

    if (permissionResult) {
      permissionResult.then(resolve, reject);
    }
  })
  .then(function(permissionResult) {
    if (permissionResult !== 'granted') {
      throw new Error('Permission not granted.');
    }else if(permissionResult === 'granted'){
        navigator.serviceWorker.register('/serviceWorker.js');
        subscribeUserToPush();
    }
  });
}
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (var i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
function subscribeUserToPush() {
    
    navigator.serviceWorker.ready.then(function(reg){

        if(!reg){
            var subscribeOptions = {
              userVisibleOnly: true,
              applicationServerKey: urlBase64ToUint8Array(applicationServerPublicKey)
            };

            reg.pushManager.subscribe(subscribeOptions).then(
            
                function(pushSubscription) {
                    if (!pushSubscription) {
                        // Set appropriate app states.
                        return;
                    }
                    console.log('PushSubscription: ', JSON.stringify(pushSubscription));
                    return pushSubscription;
                }
            ).catch(function (err) {
                console.log('error in subcription .. '+ err);
            });
        }
    });
    
    navigator.serviceWorker.addEventListener('message', function(event) {
      console.log('Received a message from service worker: ', event.data);
    });
}
