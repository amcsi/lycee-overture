import api from '../index';

export async function listArticles() {
  return (await api.get('/articles')).data.data;
}
