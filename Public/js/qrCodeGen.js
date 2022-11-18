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