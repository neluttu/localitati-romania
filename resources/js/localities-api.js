export function localitiesApi() {
    const countySelect = document.getElementById("county");
    const citySelect = document.getElementById("city");
    const postalInput = document.getElementById("postal_code");

    const selectedCountyValue = countySelect?.dataset.selected || "";
    const selectedCity = citySelect?.dataset.selected || "";

    //
    // Helpers SELECT / INPUT
    //
    function enableSelectMode() {
        citySelect.style.display = "block";
        citySelect.disabled = false;
        citySelect.setAttribute("name", "city");
    }

    function enableInputMode() {
        citySelect.style.display = "none";
        citySelect.disabled = true;
        citySelect.removeAttribute("name");
    }

    enableSelectMode();

    //
    // Placeholder județ
    //
    if (!selectedCountyValue) {
        const placeholder = document.createElement("option");
        placeholder.value = "";
        placeholder.textContent = "- Alege un județ -";
        placeholder.disabled = true;
        placeholder.selected = true;
        countySelect.prepend(placeholder);
    } else {
        const selectedOption = [...countySelect.options].find(
            (opt) => opt.value === selectedCountyValue
        );
        const abbr = selectedOption?.dataset.abbr;
        if (abbr) {
            loadCities(abbr, selectedCity);
        }
    }

    //
    // Schimbare județ
    //
    countySelect.addEventListener("change", function () {
        const abbr = this.options[this.selectedIndex].dataset.abbr;

        citySelect.innerHTML = "<option>Încărcare localități...</option>";
        postalInput.value = "";

        loadCities(abbr, null);
    });

    //
    // Change city
    //
    citySelect.addEventListener("change", function () {
        const selectedOption = this.options[this.selectedIndex];
        const postal = selectedOption.dataset.postal;

        postalInput.value = postal && postal !== "000000" ? postal : "";
    });

    //
    // LOAD LOCALITIES – GRUPATE (API NEMODIFICAT)
    //
    function loadCities(countyAbbr, selectedCityValue) {
        if (!countyAbbr) {
            citySelect.innerHTML = "<option>Selectează un județ</option>";
            return;
        }

        // Step 1: Add the selected city (if it exists) directly in the <select>
        if (selectedCityValue) {
            const selectedOption = document.createElement("option");
            selectedOption.value = selectedCityValue;
            selectedOption.textContent = selectedCityValue;
            selectedOption.selected = true;
            citySelect.appendChild(selectedOption);
        }

        citySelect.innerHTML += "<option>Încărcare localități...</option>";

        fetch(`/v1/counties/${countyAbbr}/localities`)
            .then((res) => res.json())
            .then((response) => {
                enableSelectMode();

                const items = response.data;

                // Step 2: Clear all options except the selected one
                citySelect.innerHTML = ""; // Clear previous options
                const placeholder = document.createElement("option");
                placeholder.value = "";
                placeholder.textContent = "- Alege localitatea -";
                placeholder.disabled = true;
                placeholder.selected = !selectedCityValue;
                citySelect.appendChild(placeholder); // Keep the "Alege localitatea" placeholder

                let foundSelected = false;

                // Step 3: Sort the items alphabetically
                items.sort((a, b) => {
                    const labelA =
                        a.parent && a.name !== a.parent.name
                            ? `${a.name} (${a.parent.name})`
                            : a.name;

                    const labelB =
                        b.parent && b.name !== b.parent.name
                            ? `${b.name} (${b.parent.name})`
                            : b.name;

                    return labelA.localeCompare(labelB, "ro", {
                        sensitivity: "base",
                    });
                });

                // Step 4: Add options to select dropdown
                items.forEach((loc) => {
                    const option = document.createElement("option");

                    const text =
                        loc.parent && loc.name !== loc.parent.name
                            ? `${loc.name} (${loc.parent.name})`
                            : loc.name;

                    option.value = text;
                    option.textContent = text;

                    option.dataset.postal = loc.postal_code;

                    if (selectedCityValue && text === selectedCityValue) {
                        option.selected = true;
                        foundSelected = true;
                    }

                    citySelect.appendChild(option);
                });

                // Step 5: Fallback if the city is not found in the API response
                if (selectedCityValue && !foundSelected) {
                    const opt = document.createElement("option");
                    opt.value = selectedCityValue;
                    opt.textContent = selectedCityValue;
                    opt.selected = true;
                    opt.dataset.custom = "true";
                    citySelect.appendChild(opt);
                }
            })
            .catch(() => {
                enableInputMode();
            });
    }
}
