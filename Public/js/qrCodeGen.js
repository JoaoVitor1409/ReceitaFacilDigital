$(document).ready(() => {
    $(document).on("click", "#qrCode", () => {
        let scanner = new Instascan.Scanner({ video: document.getElementById("preview") });
        scanner.addListener('scan', function (content) {
            alert("Escaneou o conteudo: " + content)
            window.open(content, "_blank")
        });

        Instascan.Camera.getCameras().then(cameras => {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error("Não existe câmera!")
            }
        })
    })
})
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