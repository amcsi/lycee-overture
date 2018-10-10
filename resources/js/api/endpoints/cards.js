import api from '../index';

export async function listCards(params) {
  return (await api.get('/cards', { params })).data;
}
