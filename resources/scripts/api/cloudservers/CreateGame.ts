import http from '@/api/http';

export default (name: string, description: string, egg: string, memory: string, disk: string): Promise<any> => {
    return new Promise((resolve, reject) => {
        http.post(`/api/client/cloudservers/game/create`, {
            name, description, egg, memory, disk,
        }).then((data) => {
            resolve(data.data || []);
        }).catch(reject);
    });
};
