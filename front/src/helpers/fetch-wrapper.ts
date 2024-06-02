import ValidationError from '@/errors/validation-error';
import { useAuthStore } from '@/stores/auth.store';
import axios, { AxiosError, type AxiosRequestConfig, type AxiosResponse } from 'axios';

export const fetchWrapper = {
    get: request('GET'),
    post: request('POST'),
    put: request('PUT'),
    delete: request('DELETE')
};

function request(method : string) {
    return (url : string, body? : any) => {
        const requestOptions : AxiosRequestConfig = {
            method,
            withCredentials: true,
            withXSRFToken: true,
            headers: Object.assign({
                'Content-Type' : 'application/json',
                'Accept' : 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }, authHeader(url)),
            url: url
        };
        if (body) {
            requestOptions.data = body;
        }

        return axios.request(requestOptions).then(handleResponse).catch(handleCatch);
    }
}

// helper functions

function authHeader(url: string) {
    // return auth header with jwt if user is logged in and request is to the api url
    const { user } = useAuthStore();
    const isLoggedIn = !!user?.token;
    const isApiUrl = url.startsWith(import.meta.env.VITE_API_URL);
    if (isLoggedIn && isApiUrl) {
        return { Authorization: `Bearer ${user.token}` };
    } else {
        return {};
    }
}

async function handleCatch(error: AxiosError) {
    console.log('handleCatch', error);
    const response : AxiosResponse | undefined = error.response;
    if (response) {
        return await handleResponse(response);
    }
    return Promise.reject('Unknown error!!!');
}

async function handleResponse(response: AxiosResponse) {
    console.log('handleResponse', response);
    const data = response.data;

    // check for error response
    if (!data.success) {
        const { user, logout } = useAuthStore();
        if ([401, 403].includes(response.status) && user) {
            // auto logout if 401 Unauthorized or 403 Forbidden response returned from api
            logout();
        }

        if ([422].includes(response.status)) {
            // ValidationError
            return Promise.reject(new ValidationError(data.message||'', data.data));
        }

        // get error message from body or default to response status
        const error = (data && data.message) || response.status;
        return Promise.reject(error);
    }

    return data;
}
