import {RouteLocationRaw, Router} from 'vue-router'


export default class NavigatorService {

    static router: Router = null;


    static addTrans(text: string) {

        let transObject: string = localStorage.getItem('trans');
        let trans: string[] = [];
        if (transObject != null) {
            trans = JSON.parse(transObject);
        }
        trans = trans.filter((item,
                    index) => trans.indexOf(item) === index)
        trans.push(text);
        localStorage.setItem('trans', JSON.stringify(trans));

    }


    static getUnTrans(){
        try {
            let transObject: string = localStorage.getItem('trans');
            let trans = "";


            if (transObject != null) {
                let newTrans: string[] = JSON.parse(transObject);
                for (const t of newTrans) {
                    let n = t.replaceAll("_", " ");
                    n = n.charAt(0).toUpperCase() + n.slice(1);
                    trans += ('"' + t + '": "' + n + '",\n');
                }
                console.info(trans);
            }
        }catch(e){

        }
    }


    static setApp(router: Router) {
        this.router = router;
    }

    static async copyToClipboard(text: string): Promise<boolean> {
        function fallbackCopyTextToClipboard(text) {
            let success = false;
            const textArea = document.createElement("textarea");
            textArea.value = text;

            // Avoid scrolling to bottom
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                success = document.execCommand('copy');
            } catch (err) {
            }
            document.body.removeChild(textArea);
            return success;
        }

        if (!navigator.clipboard) {
            return fallbackCopyTextToClipboard(text);
        }
        await navigator.clipboard.writeText(text);
        return true;
    }

    static goBackOrRoute(route?: RouteLocationRaw) {
        console.info(window.history.state.back)
        if (window.history?.state?.back != null) {

            this.router.go(-1);
        }
        this.router.replace(route);
    }



}


export class DocsNavigation{

    private static base = "http://localhost:5173/";

    static goToFirebase() {
        this._to('setup/firebase')
    }

    static goToBackend() {
        this._to('setup/backend')
    }

    static goToRazorpay() {
        this._to('payments/razorpay')
    }

    static goToStripe() {
        this._to('payments/stripe')
    }

    static goToPaypal() {
        this._to('payments/paypal')
    }

    static goToFlutterwave() {
        this._to('payments/flutterwave')
    }

    private static _to(url) {
        window.open(this.base + url, "_blank");
    }
    static goToMail(){
        this._to('mail')
    }
    static goToSMS(){
        this._to('sms/twilio')
    }
    static goToOtherSetup(){
        this._to('other_setups/map')
    }
}

export function goToBuyNow(){
    return;
}
