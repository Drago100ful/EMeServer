function Decoder(bytes, port) {

    function getData(bytes) {
        const data = [];

        for (let i = 0; i < 48; i++) {
            if (i % 2 === 0) {
                data.push(bytes[i] * 256 + bytes[i + 1]);
            }
        }

        data.push(bytes[49] * 256 + bytes[50]);

        return Object.assign({}, data);

    }

    return getData(bytes);
}
