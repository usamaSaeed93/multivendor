import axios, {AxiosRequestConfig} from "axios";
import Response from "./response";
import {
    getAdminSelectedModuleId,
    getAuthTokenFromUrl,
    getCustomerSelectedModuleId
} from "@js/services/state/state_helper";


export default class Request {

    // static BASE_URL = 'http://192.168.1.72:8000/api/v1/';
    static BASE_URL = '/api/v1/';

    static async get<T = any>(url: string, config?: AxiosRequestConfig) {
        await this.sleep();

        return new Response<T>(await axios.get(this.BASE_URL + this.getCleanedUrl(url), config));
    }

    static async post<T = any>(url: string, data: any, config?: AxiosRequestConfig) {
        await this.sleep();
        return new Response<T>(await axios.post(this.BASE_URL + this.getCleanedUrl(url), data, config));
    }

    static async patch<T = any>(url: string, data: any, config?: AxiosRequestConfig) {
        await this.sleep();
        return new Response<T>(await axios.patch(this.BASE_URL + this.getCleanedUrl(url), data, config));
    }

    static async put<T = any>(url: string, data: any, config?: AxiosRequestConfig) {
        await this.sleep();
        return new Response<T>(await axios.put(this.BASE_URL + this.getCleanedUrl(url), config));
    }


    static async delete<T = any>(url: string, config?: AxiosRequestConfig) {
        await this.sleep();
        return new Response<T>(await axios.delete(this.BASE_URL + this.getCleanedUrl(url), config));
    }


    static getAuth<T>(url: string) {
        return this.get<T>(url, this._getAuthHeaderConfig(url));
    }

    static postAuth<T>(url: string, data: any, config?: AxiosRequestConfig) {
        return this.post<T>(url, data, this._getAuthHeaderConfig(url, config));
    }

    static patchAuth<T>(url: string, data?: any, config?: AxiosRequestConfig) {
        return this.patch<T>(url, data, this._getAuthHeaderConfig(url, config));
    }

    static putAuth<T>(url: string, data: any, config?: AxiosRequestConfig) {
        return this.put<T>(url, data, this._getAuthHeaderConfig(url, config));
    }

    static deleteAuth<T>(url: string, config?: AxiosRequestConfig) {
        return this.delete<T>(url, this._getAuthHeaderConfig(url, config));
    }


    static _getAuthHeaderConfig(url: string, config?: AxiosRequestConfig) {
        const token = getAuthTokenFromUrl(url);
        return {
            ...config, headers: {
                Authorization: 'Bearer ' + token ?? "",
            }
        }
    }

    static getImageData(imageData: string): string | null {
        if (imageData == null) return null;
        return imageData.toString().split(";base64,").pop();
    }

    static sleep() {
        return;
        return new Promise(resolve => setTimeout(resolve, 1000));
    }

    static getCleanedUrl(url: string){
        return url.endsWith('/') ?
            url.slice(0, -1) :
            url;
    }

    static addCustomerModule(url: string) {
        let id = getCustomerSelectedModuleId();
        return id != null ? url + "?module_id=" + id : url;
    }

    static addAdminModule(url: string) {
        let id = getAdminSelectedModuleId();
        return url +"?"+ ( id != null ? "module_id=" + id +"&": "");
    }

    static getClearBody(body: Object) {
        let newBody = {};
        Object.entries(body).map(([key, val]) => {
            if (val != null) {
                newBody[key] = val;
            }
        });

        return newBody
    }

}
