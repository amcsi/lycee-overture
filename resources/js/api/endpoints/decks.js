import api from '../index';

export async function listDecks() {
  return (await api.get(`/decks`)).data.data;
}
