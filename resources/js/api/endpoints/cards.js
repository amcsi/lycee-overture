import api from '../index';

export async function listCards(params) {
  return (await api.get('/cards', { params })).data;
}

export async function showCard(cardId) {
  return (await api.get(`/cards/${cardId}`)).data;
}
