import moment from "moment/moment";
export function formatDate(value) {
    if(value) {
        return moment(String(value)).format('YYYY-MM-DD');
    }
}