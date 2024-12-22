import axios from "axios";

export async function authRequest (path ='', type='get', data={}){

    let url = process.env.VUE_APP_DOMAIN_BACKEND+'/'+path;
    let token = '';
    let response = 'error';
    if (localStorage.getItem("token") !== null ) {
        token = JSON.parse(localStorage.getItem('token'));
    }


    let headers = {
        Authorization: 'Bearer ' +  token.token
    }

    if (type === 'get') {
        try {
            response = await axios.get(url, {
                headers: headers
            });
        } catch (error) {
            response = error.response;
        }
    }

    if (type === 'post' ) {
        try {
            response = await axios.post(url, data, {
                headers: {
                    'Content-Type' : "multipart/form-data; charset=utf-8;",
                    Authorization: 'Bearer ' +  token.token
                }
            });
        } catch (error) {
            response = error.response;
        }
    }

    if (type === 'put') {
        try {
            response = await axios.put(url, data, {
                headers: {
                    'Cache-Control': null,
                    'X-Requested-With': null,
                    'Content-Type' : "application/json",
                    Authorization: 'Bearer ' + token
                }
            });
        } catch (error) {
            response = error.response;
        }
    }

    if (type === 'delete' ) {
        try {
            response = await axios.delete(url, {
                headers: headers
            });
        } catch (error) {
            response = error.response;
        }
    }

    //токен просрочен
    console.log( response.data)
    if (response && response.data.code === 401){
        localStorage.removeItem('token');
    }

    if (response && typeof response.data.text === 'object'){
        let string = '';

        for (let i in response.data.text) {
            string = string + response.data.text[i]+' ';
        }

        response.data.text = string;

    }
    return response;

}

export async function notAuthRequest (path ='', type='post', data={} ){
    let headers = {
        'Content-Type' : "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2),
    }

    let url = process.env.VUE_APP_DOMAIN_BACKEND+'/'+path;
    let response = 'error';

    if (type === 'get') {
        try {
            response = await axios.get(url);
        } catch (error) {
            response = error.response;
        }
    }

    if ( type === 'post' ) {
        try {
            response = await axios.post(url, data, {
                headers: headers
            });
        } catch (error) {
            response = error.response;
        }
    }


    if (response && typeof response.data.text === 'object'){
        let string = '';

        for (let i in response.data.text) {
            string = string + response.data.text[i]+' ';
        }

        response.data.text = string;

    }
    return response;
}
