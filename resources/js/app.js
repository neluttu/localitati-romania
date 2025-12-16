import "./bootstrap";

if (document.querySelector("[data-localities-api]")) {
    import("./localities-api.js").then((module) => {
        module.localitiesApi();
    });
}
