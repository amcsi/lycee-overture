import api from '../index';

export async function listSets() {
  return (await api.get(`/sets`)).data.data;
}
