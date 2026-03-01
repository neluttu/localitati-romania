export function accordion() {
    document.querySelectorAll(".api-accordion").forEach((accordion) => {
        const toggle = accordion.querySelector(".accordion-toggle");
        const content = accordion.querySelector(".accordion-content");
        const chevron = accordion.querySelector(".chevron");

        toggle.addEventListener("click", () => {
            const isOpen =
                content.style.maxHeight && content.style.maxHeight !== "0px";

            if (isOpen) {
                content.style.maxHeight = "0px";
                content.style.opacity = "0";
                chevron.classList.remove("rotate-180");
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
                content.style.opacity = "1";
                chevron.classList.add("rotate-180");
            }
        });
    });
}
