function genQrCode(data) {
    data = JSON.stringify(data)
    var qr = new QRCode("qrcode", data)
    let src = null;

    return new Promise(resolve => {
        setTimeout(() => {
            src = $("#qrcode > img")[0].src
            resolve(src)
        }, 500);
    })
}

function scanQrCode() {
    let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });

    Instascan.Camera.getCameras().then(cameras => {
        if (cameras.length > 0) {
            scanner.start(cameras[0]);
        } else {
            alert("Não existe câmera no dispositivo!");
            return false;
        }
    });

    return new Promise(resolve => {
        scanner.addListener('scan', function (content) {
            resolve(content)
            scanner.stop()
        });
    })
}