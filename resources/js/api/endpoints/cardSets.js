import api from '../index';

export async function listCardSets() {
  return (await api.get(`/card-sets`)).data.data;
}
