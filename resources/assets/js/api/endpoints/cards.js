import api from '../../api';

export async function listCards(params) {
  return (await api.get('/cards', { params })).data;
}
