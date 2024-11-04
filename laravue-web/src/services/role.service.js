import http from "./http";

export default {
  findById(id) {
    return http.get("/roles/" + id);
  },

  find(page, search) {
    return http.get(
      "/roles?paginate=8&page=" +
        page +
        "&name=" +
        search.name +
        "&active=" +
        search.status
    );
  },

  create(data) {
    return http.post("/roles", data);
  },

  update(id, data) {
    return http.put("/roles/" + id, data);
  },

  delete(id) {
    return http.delete("/roles/" + id);
  },

  getActiveRoles() {
    return http.get("/roles?active=1");
  },
};
