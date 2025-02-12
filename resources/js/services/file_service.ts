import * as XLSX from 'xlsx';
import * as FileSaver from 'file-saver';
import {jsPDF as JsPDF} from "jspdf";
import autoTable from 'jspdf-autotable';
import {ToastNotification} from "@js/services/toast_notification";


export class FileService {

    static types = {
        images: 'image/*',
        zip: '.zip'
    }


    static exportExcel(json: any, fileName: string) {
        try {
            const fileType = 'application/vnd.openxmlformats-office-document.spreadsheetml.sheet;charset=UTF-8';
            const fileExtension = '.xlsx';
            const ws = XLSX.utils.json_to_sheet(json);
            ws['!cols'] = [{width: 20}, {width: 20}, {width: 20}, {width: 20}, {width: 20,},];
            ws["!margins"] = {left: 1.0, right: 1.0, top: 1.0, bottom: 1.0, header: 0.5, footer: 0.5}
            const wb = {Sheets: {'data': ws}, SheetNames: ['data'],};
            const excelBuffer = XLSX.write(wb, {bookType: 'xlsx', type: 'array'});
            const data = new Blob([excelBuffer], {type: fileType});
            FileSaver.saveAs(data, fileName + fileExtension);
        } catch (e) {
            ToastNotification.error('Can\'t export Excel');
        }
    }

    static exportCSV(json: any, fileName: string) {
        try {
            var exportedFilename = fileName + '.csv' || 'export.csv';
            var csv = this.convertToCSV(json);

            var blob = new Blob([csv], {type: 'text/csv;charset=utf-8;'});
            const link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = fileName;
            link.style.display = "none";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } catch (e) {
            ToastNotification.error('Can\'t export CSV');
        }
    }

    static exportPDF(json: any[], fileName: string) {
        try {

            if (json != null && json.length > 0) {
                var doc = new JsPDF();
                let head = Object.keys(json[0]);
                let body = json.map((single) => Object.values(single));
                autoTable(doc, {
                    head: [head], body: body
                })
                const fileExtension = '.pdf';

                doc.save(fileName + fileExtension)
            }else{
            }
        } catch (e) {
            ToastNotification.error('Can\'t export PDF');
        }
    }


    private static convertToCSV(data) {
        let csv = data.map(row => Object.values(row));
        csv.unshift(Object.keys(data[0]));
        return csv.join('\n');
    }


}
