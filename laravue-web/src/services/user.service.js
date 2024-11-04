import http from "./http";

export default {
  findById(id) {
    return http.get("/users/" + id);
  },

  find(page, search) {
    return http.get(
      "/users?paginate=8&page=" +
        page +
        "&name=" +
        search.name +
        "&active=" +
        search.status
    );
  },

  create(data) {
    return http.post("/users", data);
  },

  update(id, data) {
    return http.put("/users/" + id, data);
  },

  delete(id) {
    return http.delete("/users/" + id);
  },
};
