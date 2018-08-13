import api from '../../api';

export async function listCardSets() {
  return (await api.get(`/card-sets`)).data.data;
}
