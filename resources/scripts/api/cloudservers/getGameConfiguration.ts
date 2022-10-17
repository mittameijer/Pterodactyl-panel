import http from '@/api/http';
import { GameConfigurationResponse } from '@/components/dashboard/cloudservers/GameConfiguration';

export default async (id: string): Promise<GameConfigurationResponse> => {
    const { data } = await http.get(`/api/client/cloudservers/game/configuration/${id}`);
    return (data.data || []);
};
