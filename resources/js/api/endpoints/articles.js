import blogApi from "../blogApi";

const CATEGORY_NEWS = 2;

export async function listArticles(limit) {
  return (
    await blogApi.get("/v2/posts", {
      params: { categories: CATEGORY_NEWS, per_page: limit },
      transformRequest(data, headers) {
        delete headers.common["X-Requested-With"];
        return data;
      },
    })
  ).data;
}
