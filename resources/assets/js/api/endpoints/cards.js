import api from '../../api';

export async function listCards(page) {
  return (await api.get(`/cards?page=${page}`)).data;
}
