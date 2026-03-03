import "./bootstrap";
import Alpine from "alpinejs";

window.Alpine = Alpine;
Alpine.start();

if (document.querySelector("[data-localities-api]")) {
    import("./localities-api.js").then((module) => {
        module.localitiesApi();
    });
}

document.querySelector("[data-accordion]") &&
    import("./accordion.js").then((m) => m.accordion());
