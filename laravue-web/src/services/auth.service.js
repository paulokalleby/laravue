import http from "./http";

export default {
  login(data) {
    return http.post("/auth/login", data);
  },

  logout() {
    return http.post("/auth/logout");
  },

  register(data) {
    return http.post("/auth/register", data);
  },

  forgot(data) {
    return http.post("/auth/password/code", data);
  },

  verify(data) {
    return http.post("/auth/password/verify", data);
  },

  reset(data) {
    return http.post("/auth/password/reset", data);
  },

  profile(data) {
    return http.put("/auth/profile", data);
  },

  me() {
    return http.get("/auth/me");
  },
};
