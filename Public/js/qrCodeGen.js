function genQrCode(data) {
    data = JSON.stringify(data)
    data = encodeURIComponent(data)
    let qrCode = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=" + data;


    return qrCode
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