import api from '../../api';

export async function listCards(page = 1) {
  return (await api.get(`/cards?page=${page}`)).data;
}
