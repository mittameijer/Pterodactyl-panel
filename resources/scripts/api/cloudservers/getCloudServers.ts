import http from '@/api/http';
import { CloudServersResponse } from '@/components/dashboard/cloudservers/CloudServersContainer';

export default async (): Promise<CloudServersResponse> => {
    const { data } = await http.get('/api/client/cloudservers');
    return (data.data || []);
};
