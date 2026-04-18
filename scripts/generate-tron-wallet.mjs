import pkg from "tronweb";

const { TronWeb } = pkg;

const tronWeb = new TronWeb({
    fullHost: "https://api.trongrid.io"
});

async function generateWallet() {
    try {

        const account = await tronWeb.createAccount();

        const result = {
            address: account.address.base58,
            address_hex: account.address.hex,
            privateKey: account.privateKey,
            publicKey: account.publicKey
        };

        console.log(JSON.stringify(result));

    } catch (error) {

        console.error("Wallet generation error:", error);
        process.exit(1);

    }
}

generateWallet();